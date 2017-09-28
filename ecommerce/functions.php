<?php

use \Models\User;
use \Db\Sql;

function formatPrice(float $vlprice) {
    return number_format($vlprice, 2, ',', '.');
}

function checkLogin($inadmin = true)
{
    return User::checkLogin($inadmin);
}
function getUserName()
{
    $user = User::getFromSession();
    $sql = new Sql();

    $result = $sql->select("SELECT desperson FROM tb_persons WHERE idperson = :idperson", [
        ':idperson' => $user->getidperson()
    ]);

    return $result[0]['desperson'];
}

?>