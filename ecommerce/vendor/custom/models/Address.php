<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;
use \Models\Product;

class Address extends Model {
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

}
?>