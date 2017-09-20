<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;
use \Models\Product;

class Cart extends Model {

    
    public function save() {
        $sql = new Sql();

        $results = $sql->getselect("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", [
            ':idcart'=> $this->getidcart()(),
            ':dessessionid'=> $this->getdessessionid(),
            ':iduser'=> $this->getiduser(),
            ':deszipcode'=> $this->getdeszipcode(),
            ':vlfreight'=> $this->getvlfreight(),
            ':nrdays' => $this->getnrdays(),
        ]);

        $this->setData($results[0]);
    }
}
?>