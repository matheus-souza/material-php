<?php

namespace models;

use \Db\Sql;
use \Models\Cart;

class Order extends Model {
    const ERROR = 'OrderError';
    const SUCCESS = 'OrderSucces';

    public function save() {
        $sql = new Sql();

        $results = $sql->select("CALL sp_orders_save(:idorder, :idcart, :iduser, :idstatus, :idaddress, :vltotal)", [
            ':idorder' => $this->getidorder(),
            ':idcart' => $this->getidcart(),
            ':iduser' => $this->getiduser(),
            ':idstatus' => $this->getidstatus(),
            ':idaddress' => $this->getidaddress(),
            ':vltotal' => $this->getvltotal()
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function get($idorder) {
        $sql = new Sql();

        $results = $sql->select("SELECT * 
                                            FROM tb_orders a
                                      INNER JOIN tb_ordersstatus b
                                           USING (idstatus)
                                      INNER JOIN tb_carts c
                                           USING (idcart)
                                      INNER JOIN tb_users d
                                              ON d.iduser = a.iduser
                                      INNER JOIN tb_addresses e
                                           USING (idaddress)
                                      INNER JOIN tb_persons f
                                              ON f.idperson = d.idperson
                                           WHERE a.idorder = :idorder", [
            ':idorder' => $idorder
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function delete() {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_orders WHERE idorder = :idorder", [
            ':idorder' => $this->getidorder()
        ]);
    }

    public static function listAll() {
        $sql = new Sql();

        $results = $sql->select("SELECT * 
                                            FROM tb_orders a
                                      INNER JOIN tb_ordersstatus b
                                           USING (idstatus)
                                      INNER JOIN tb_carts c
                                           USING (idcart)
                                      INNER JOIN tb_users d
                                              ON d.iduser = a.iduser
                                      INNER JOIN tb_addresses e
                                           USING (idaddress)
                                      INNER JOIN tb_persons f
                                              ON f.idperson = d.idperson
                                        ORDER BY a.dtregister DESC");

        return $results;
    }

    public function getCart():Cart {
        $cart = new Cart();

        $cart->get((int)$this->getidcart());

        return $cart;
    }

    public static function setError($msg) {
        $_SESSION[self::ERROR] = (string)$msg;
    }

    public static function getError() {
        $msg = $_SESSION[self::ERROR] ?? '';

        self::clearError();

        return $msg;
    }

    public static function clearError() {
        $_SESSION[self::ERROR] = null;
    }

    public static function setSuccess($msg) {
        $_SESSION[self::SUCCESS] = (string)$msg;
    }

    public static function getSuccess() {
        $msg = $_SESSION[self::SUCCESS] ?? '';

        self::clearSuccess();

        return $msg;
    }

    public static function clearSuccess() {
        $_SESSION[self::SUCCESS] = null;
    }

    public static function getPage($page = 1, $itemsPerPage = 10, $search = null) {
        $start = ($page-1)*$itemsPerPage;

        $sql = new Sql();

        if (strlen($search) > 0) {
            $searchSql = " WHERE a.idorder = :id
                              OR f.desperson LIKE :search ";
        }

        $results = $sql->select("
            SELECT SQL_CALC_FOUND_ROWS *
              FROM tb_orders a
        INNER JOIN tb_ordersstatus b
             USING (idstatus)
        INNER JOIN tb_carts c
             USING (idcart)
        INNER JOIN tb_users d
                ON d.iduser = a.iduser
        INNER JOIN tb_addresses e
             USING (idaddress)
        INNER JOIN tb_persons f
                ON f.idperson = d.idperson "
            . $searchSql .
            " ORDER BY a.dtregister DESC
             LIMIT $start, $itemsPerPage", [
            ':search' => '%'.$search.'%',
            ':id' => $search
        ]);

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data' => $results,
            'total' => (int)$resultsTotal[0]['nrtotal'],
            'pages' => ceil($resultsTotal[0]['nrtotal'] / $itemsPerPage),
        ];
    }

}
?>