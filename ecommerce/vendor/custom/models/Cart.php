<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;
use \Models\Product;
use \Models\User;

class Cart extends Model {

    const SESSION = 'Cart';

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
    }
}
?>