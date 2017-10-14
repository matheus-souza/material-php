<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;

class Product extends Model {
    public static function listAll() {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
    }

    public static function checkList($list) {
        foreach ($list as &$item) {
            $row = new Product();
            $row->setData($item);
            $item = $row->getValues();
        }

        return $list;
    }

    public function save() {
        $sql = new Sql();

        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct" => $this->getidproduct(),
            ":desproduct" => $this->getdesproduct(),
            ":vlprice" => $this->getvlprice(),
            ":vlwidth" => $this->getvlwidth(),
            ":vlheight" => $this->getvlheight(),
            ":vllength" => $this->getvllength(),
            ":vlweight" => $this->getvlweight(),
            ":desurl" => $this->getdesurl()
        ));

        $this->setData($results[0]);
    }

    public function get($idproduct) {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct" => $idproduct
        ));

        $this->setData($results[0]);
    }

    public function delete() {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct" => $this->getidproduct()
        ));
    }

    public function checkPhoto() {
        if (file_exists(
            '.' .
            DIRECTORY_SEPARATOR . 'res' .
            DIRECTORY_SEPARATOR . 'site' .
            DIRECTORY_SEPARATOR . 'img' .
            DIRECTORY_SEPARATOR . 'products' .
            DIRECTORY_SEPARATOR . $this->getidproduct() . '.jpg')) {
            $url = '../../res/site/img/products/' . $this->getidproduct() . '.jpg';
        } else {
            $url =  '../../res/site/img/default/product.jpg';
        }
        return $this->setdesphoto($url);
    }

    public function getValues()
    {
        $this->checkPhoto();

        $values = parent::getValues();

        return $values;
    }

    public function setPhoto($file) {

        $extension = explode('.', $file['name']);
        $extension = end($extension);

        switch ($extension) {
            case 'jpg':case 'jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'gif':
                $image = imagecreatefromgif($file['tmp_name']);
                break;
            case 'png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
        }

        $dist = '.' .
            DIRECTORY_SEPARATOR . 'res' .
            DIRECTORY_SEPARATOR . 'site' .
            DIRECTORY_SEPARATOR . 'img' .
            DIRECTORY_SEPARATOR . 'products' .
            DIRECTORY_SEPARATOR . $this->getidproduct() . '.jpg';
        imagejpeg($image, $dist);

        imagedestroy($image);

        $this->checkPhoto();
    }

    public function getFromUrl($desurl) {
        $sql = new Sql();

        $rows = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1", [
            ':desurl' => $desurl
        ]);

        $this->setData($rows[0]);
    }

    public function getCategories() {
        $sql = new Sql();

        return $sql->select("
            SELECT *
            FROM tb_categories a
            INNER JOIN tb_productscategories b
            USING (idcategory)
            WHERE b.idproduct = :idproduct", [
                ':idproduct' => $this->getidproduct()
        ]);
    }

    public static function getPage($page = 1, $itemsPerPage = 10, $search = null) {
        $start = ($page-1)*$itemsPerPage;

        $sql = new Sql();

        if (strlen($search) > 0) {
            $searchSql = " WHERE desproduct LIKE :search ";
        }

        $results = $sql->select("
            SELECT SQL_CALC_FOUND_ROWS *
              FROM tb_products "
            . $searchSql .
        " ORDER BY desproduct
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

}
?>