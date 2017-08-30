<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;

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
    public static function updateFile() {
        $categories = self::listAll();

        $html = array();

        foreach ($categories as $value) {
            array_push($html, "<li><a href='/categories/{$value['idcategory']}'>{$value['descategory']}</a></li>");

            file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html", implode('', $html));
        }
    }
}
?>