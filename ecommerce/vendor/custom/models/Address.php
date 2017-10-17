<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;
use \Models\Product;

class Address extends Model {
    const SESSION_ERROR_CHECKOUT = 'SESSION_ERROR_CHECKOUT_CHECKOUT';
    
    public static function getCep($nrcep) {
        $nrcep = str_replace('-', '', $nrcep);
        //http://viacep.com.br/ws/01001000/json/

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/$nrcep/json/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $data;
    }

    public function loadFromCep($nrcep) {
        $data = self::getCep($nrcep);

        $this->setdesaddress($data['logradouro']);
        $this->setdescomplement($data['complemento']);
        $this->setdesdistrict($data['bairro']);
        $this->setdescity($data['localidade']);
        $this->setdesstate($data['uf']);
        $this->setdescountry('Brasil');
        $this->setdeszipcode($nrcep);
    }

    public function save() {
        $sql = new Sql();

        $results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :desnumber, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [
            ':idaddress' => $this->getidaddress(),
            ':idperson' => $this->getidperson(),
            ':desaddress' => $this->getdesaddress(),
            ':desnumber' => $this->getdesnumber(),
            ':descomplement' => $this->getdescomplement(),
            ':descity' => $this->getdescity(),
            ':desstate' => $this->getdesstate(),
            ':descountry' => $this->getdescountry(),
            ':deszipcode' => $this->getdeszipcode(),
            ':desdistrict' => $this->getdesdistrict(),
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public static function setMsgError($msg) {
        $_SESSION[self::SESSION_ERROR_CHECKOUT] = (string)$msg;
    }

    public static function getMsgError() {
        $msg = $_SESSION[self::SESSION_ERROR_CHECKOUT] ?? '';

        self::clearMsgError();

        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[self::SESSION_ERROR_CHECKOUT] = null;
    }
}
?>