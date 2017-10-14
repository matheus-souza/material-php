<?php

use \Models\User;
use \Models\Cart;
use \Db\Sql;

function formatPrice($vlprice) {
    if (!$vlprice > 0) $vlprice = 0;

    return number_format($vlprice, 2, ',', '.');
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
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

function getCartNrQtd() {
    $cart = Cart::getFromSession();

    $totals = $cart->getProductsTotals();

    return $totals['nrqtd'];
}

function getCartVlSubtotal() {
    $cart = Cart::getFromSession();

    $price = $cart->getProductsTotals();

    return formatPrice($price['vlprice']);
}

?>