<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;
use \Models\Product;
use \Models\User;

class Cart extends Model {

    const SESSION = 'Cart';
    const SESSION_ERROR = 'CartError';

    public static function getFromSession() {
        $cart = new Cart();

        if (isset($_SESSION[self::SESSION]) && (int)$_SESSION[self::SESSION]['idcart'] > 0) {
            $cart->get((int)$_SESSION[self::SESSION]['idcart']);
        } else {
            $cart->getFromSessionId();

            if (!(int)$cart->getidcart() > 0) {
                $data = [
                    'dessessionid' => session_id()
                ];

                if (User::checkLogin(false)) {
                    $user = User::getFromSession();

                    $data['iduser'] = $user->getiduser();
                }

                $cart->setData($data);

                $cart->save();

                $cart->setToSession();

            }

        }

        return $cart;
    }

    public function setToSession() {
        $_SESSION[self::SESSION] = $this->getValues();
    }

    public function getFromSessionId() {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid = :dessessionid", [
            ':dessessionid' => session_id()
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function save() {
        $sql = new Sql();

        $results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", [
            ':idcart'=> $this->getidcart(),
            ':dessessionid'=> $this->getdessessionid(),
            ':iduser'=> $this->getiduser(),
            ':deszipcode'=> $this->getdeszipcode(),
            ':vlfreight'=> $this->getvlfreight(),
            ':nrdays' => $this->getnrdays(),
        ]);

        $this->setData($results[0]);
    }

    public function get(int $idcart) {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart", [
            ':idcart' => $idcart
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function addProduct(Product $product) {
        $sql = new Sql();

        $sql->query("INSERT INTO tb_cartsproducts (idcart, idproduct) VALUES (:idcart, :idproduct)", [
            ":idcart" => $this->getidcart(),
            ":idproduct" => $product->getidproduct(),
        ]);

        $this->getCalculateTotal();
    }

    public function removeProduct(Product $product, $all = false) {
        $sql = new Sql();

        if ($all) {
            $sql->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL", [
                ":idcart" => $this->getidcart(),
                ":idproduct" => $product->getidproduct()
            ]);
        } else {
            $sql->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL LIMIT 1", [
                ":idcart" => $this->getidcart(),
                ":idproduct" => $product->getidproduct()
            ]);
        }

        $this->getCalculateTotal();
    }

    public function getProducts() {
        $sql = new Sql();

        return Product::checkList($sql->select("SELECT b.idproduct, b.desproduct, b.vlprice, b.vlwidth, b.vlheight, b.vllength, b.vlweight, b.desurl, COUNT(*) AS nrqtd, SUM(b.vlprice) AS vltotal
                                        FROM tb_cartsproducts a
                                  INNER JOIN tb_products b
                                       USING (idproduct)
                                       WHERE a.idcart = :idcart
                                         AND a.dtremoved IS NULL
                                    GROUP BY b.idproduct, b.desproduct, b.vlprice, b.vlwidth, b.vlheight, b.vllength, b.vlweight, b.desurl
                                    ORDER BY b.desproduct", [
            ":idcart" => $this->getidcart()
        ]));
    }

    public function getProductsTotals() {
        $sql = new Sql();

        $results = $sql->select("SELECT SUM(vlprice) AS vlprice,
                                      SUM(vlwidth) AS vlwidth,
                                      SUM(vlheight) AS vlheight,
                                      MAX(vllength) AS vllength,
                                      SUM(vlweight) AS vlweight,
                                      COUNT(*) AS nrqtd
                                 FROM tb_products a
                           INNER JOIN tb_cartsproducts b
                                USING (idproduct)
                                WHERE b.idcart = :idcart
                                  AND dtremoved IS NULL", [
            ':idcart' => $this->getidcart()
        ]);

        if (count($results) > 0) {
            return $results[0];
        } else {
            return [];
        }
    }

    public function setFreight($nrzipcode) {
        $nrzipcode = str_replace('-', '', $nrzipcode);

        $totals = $this->getProductsTotals();

        if ($totals['vlheight'] < 2) $totals['vlheight'] = 2;
        if ($totals['vlheight'] > 105) $totals['vlheight'] = 105;
        if ($totals['vllength'] < 16) $totals['vllength'] = 16;
        if ($totals['vllength'] > 105) $totals['vllength'] = 105;
        if ($totals['vlwidth'] > 100) $totals['vlwidth'] = 100;
        if (($totals['vllength'] + $totals['vlwidth'] + $totals['vlheight']) > 200) {
            $totals['vllength'] = 66.66;
            $totals['vlwidth'] = 66.66;
            $totals['vlheight'] = 66.66;
        }

        if ($totals['nrqtd'] > 0) {
            $qs = http_build_query([
                'nCdEmpresa'=>'',
                'sDsSenha'=>'',
                'nCdServico'=>'40010',
                'sCepOrigem'=>'95800000',
                'sCepDestino'=>$nrzipcode,
                'nVlPeso'=>($totals['vlweight']/1000),
                'nCdFormato'=>'1',
                'nVlComprimento'=>$totals['vllength'],
                'nVlAltura'=>$totals['vlheight'],
                'nVlLargura'=>$totals['vlwidth'],
                'nVlDiametro'=>'0',
                'sCdMaoPropria'=>'S',
                'nVlValorDeclarado'=>$totals['vlprice'],
                'sCdAvisoRecebimento'=>'S',
            ]);

            $xml = simplexml_load_file("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?".$qs);

            $result = $xml->Servicos->cServico;

            if ($result->MsgErro != '') {
                self::setMsgError($result->MsgErro);
            } else {
                self::clearMsgError();
            }

            $this->setnrdays((int)$result->PrazoEntrega);
            $this->setvlfreight(self::formatValueToDecimal($result->Valor));
            $this->setdeszipcode($nrzipcode);

            $this->save();

            return $result;
        } else {

        }
    }

    public static function formatValueToDecimal($value):float {
        $value = str_replace('.', '', $value);

        return str_replace(',', '.', $value);
    }

    public static function setMsgError($msg) {
        $_SESSION[self::SESSION_ERROR] = (string)$msg;
    }

    public static function getMsgError() {
        $msg = $_SESSION[self::SESSION_ERROR] ?? '';

        self::clearMsgError();

        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[self::SESSION_ERROR] = null;
    }

    public function updateFreight() {
        if ($this->getdeszipcode() != '') {
            $this->setFreight($this->getdeszipcode());
        }
    }

    public function getValues()
    {
        $this->getCalculateTotal();

        return parent::getValues();
    }

    public function getCalculateTotal() {
        $this->updateFreight();

        $totals = $this->getProductsTotals();

        $this->setvlsubtotal($totals['vlprice']);
        $this->setvltotal($totals['vlprice'] + $this->getvlfreight());
    }
}
?>