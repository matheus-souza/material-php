<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;
use \Models\Product;
use \Models\User;

class Cart extends Model {

    
    public function getFromSessionId($idcart) {

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
}
?>