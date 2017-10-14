<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;
use \Models\Product;

class Category extends Model {
    public static function listAll() {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save() {
        $sql = new Sql();

        $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
            ":idcategory" => $this->getidcategory(),
            ":descategory" => $this->getdescategory()
        ));

        $this->setData($results[0]);

        self::updateFile();
    }

    public function get($idcategory) {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", array(
            ":idcategory" => $idcategory
        ));

        $this->setData($results[0]);
    }

    public function delete() {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", array(
            ":idcategory" => $this->getidcategory()
        ));

        self::updateFile();
    }

    public static function updateFile() {
        $categories = self::listAll();

        $html = array();

        foreach ($categories as $value) {
            array_push($html, "<li><a href='/categories/{$value['idcategory']}'>{$value['descategory']}</a></li>");

            file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html", implode('', $html));
        }
    }

    public function getProducts($related = true) {
        $sql = new Sql();

        if ($related) {
            return $sql->select("SELECT *
                                     FROM tb_products
                                     WHERE idproduct IN (
                                           SELECT a.idproduct
                                             FROM tb_products a 
                                       INNER JOIN tb_productscategories b
                                            USING (idproduct)
                                            WHERE b.idcategory = :idcategory)",
                array(
                    ":idcategory" => $this->getidcategory()
                ));
        } else {
            return $sql->select("SELECT *
                                     FROM tb_products
                                     WHERE idproduct NOT IN (
                                           SELECT a.idproduct
                                             FROM tb_products a 
                                       INNER JOIN tb_productscategories b
                                            USING (idproduct)
                                            WHERE b.idcategory = :idcategory)",
                array(
                    ":idcategory" => $this->getidcategory()
                ));
        }
    }

    public function getProductsPage($page = 1, $itemsPerPage = 8) {
        $start = ($page-1)*$itemsPerPage;

        $sql = new Sql();

        $results = $sql->select("
            SELECT SQL_CALC_FOUND_ROWS *
            FROM tb_products a
            INNER JOIN tb_productscategories b
            USING (idproduct)
            INNER JOIN tb_categories c
            USING (idcategory)
            WHERE c.idcategory = :idcategory
            LIMIT $start, $itemsPerPage", [
            ':idcategory' => $this->getidcategory()
        ]);

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data' => Product::checkList($results),
            'total' => (int)$resultsTotal[0]['nrtotal'],
            'pages' => ceil($resultsTotal[0]['nrtotal'] / $itemsPerPage),
        ];
    }

    public static function getPage($page = 1, $itemsPerPage = 10, $search = null) {
        $start = ($page-1)*$itemsPerPage;

        $sql = new Sql();

        if (strlen($search) > 0) {
            $searchSql = " WHERE descategory LIKE :search ";
        }

        $results = $sql->select("
            SELECT SQL_CALC_FOUND_ROWS *
              FROM tb_categories "
            . $searchSql .
            "ORDER BY descategory
             LIMIT $start, $itemsPerPage", [
            ':search' => '%'.$search.'%'
        ]);

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data' => $results,
            'total' => (int)$resultsTotal[0]['nrtotal'],
            'pages' => ceil($resultsTotal[0]['nrtotal'] / $itemsPerPage),
        ];
    }

    public function addProduct(Product $product) {
        $sql = new Sql();

        $sql->query("INSERT INTO tb_productscategories(idcategory, idproduct) VALUES (:idcategory, :idproduct)",[
            ':idcategory' => $this->getidcategory(),
            ':idproduct' =>$product->getidproduct()
        ]);
    }

    public function removeProduct(Product $product) {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct",[
            ':idcategory' => $this->getidcategory(),
            ':idproduct' =>$product->getidproduct()
        ]);
    }
}
?>