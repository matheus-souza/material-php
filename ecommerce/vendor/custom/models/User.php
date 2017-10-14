<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;

class User extends Model {

    const SESSION = "User";
    const SECRET = "d41d8cd98f00b204e9800998ecf8427e";
    const ERROR = "UserError";
    const REGISTER_ERROR = "UserRegisterError";
    const SUCCESS = "UserSuccess";

    public static function login($login, $password) {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson WHERE a.deslogin = :LOGIN", array(
            ":LOGIN" => $login
        ));

        if (count($results) === 0) {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

        $data = $results[0];

        if (password_verify($password, $data["despassword"])) {
            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;
        } else {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }
    }

    public static function verifyLogin($inAdmin = true) {
        if (!self::checkLogin($inAdmin)) {
            if ($inAdmin) {
                header("Location: /admin/login");
            } else {
                header("Location: /login");
            }
            exit;
        }
    }

    public static function logout() {
        $_SESSION[User::SESSION] = NULL;
    }

    public static function listAll() {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING (idperson) ORDER BY b.desperson");
    }

    public function save() {
        $sql = new Sql();

        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":desperson" => $this->getdesperson(),
            ":deslogin" => $this->getdeslogin(),
            ":despassword" => password_hash($this->getdespassword(), PASSWORD_DEFAULT),
            ":desemail" => $this->getdesemail(),
            ":nrphone" => $this->getnrphone(),
            ":inadmin" => $this->getinadmin()
        ));

        $this->setData($results[0]);
    }

    public function get($iduser) {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING (idperson) WHERE a.iduser = :iduser", array(
            ":iduser" => $iduser
        ));

        $this->setData($results[0]);
    }

    public static function getFromSession() {
        $user = new User();

        if (isset($_SESSION[self::SESSION]) && (int)$_SESSION[self::SESSION]['iduser'] > 0) {
            $user->setData($_SESSION[self::SESSION]);
        }

        return $user;
    }

    public static function checkLogin($inAdmin = true) {
        if (!isset($_SESSION[User::SESSION]) ||
            !$_SESSION[User::SESSION] ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0) {
            return false;
        } else {
            if ($inAdmin === true && (boolean)$_SESSION[User::SESSION]['inadmin'] === true) {
                return true;
            } else if ($inAdmin === false) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function checkLoginExist($login)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :deslogin", [
            ':deslogin' => $login
        ]);
        return (count($results) > 0);
    }

    public function update() {
        $sql = new Sql();

        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser" => $this->getiduser(),
            ":desperson" => $this->getdesperson(),
            ":deslogin" => $this->getdeslogin(),
            ":despassword" => $this->getdespassword(),
            ":desemail" => $this->getdesemail(),
            ":nrphone" => $this->getnrphone(),
            ":inadmin" => $this->getinadmin()
        ));

        $this->setData($results[0]);
    }

    public function delete() {
        $sql = new Sql();

        $sql->query("CALL sp_users_delete(:iduser)", array(
            ":iduser" => $this->getiduser()
        ));
    }

    public static function getForgot($email, $inAdmin = true) {
        $sql = new Sql();

        $results = $sql->select("SELECT *
                                  FROM tb_persons a
                                  INNER JOIN tb_users b
                                  USING (idperson)
                                  WHERE a.desemail = :email", array(":email" => $email));

        if (count($results) === 0) {
            throw new \Exception("Não é possível recuperar a senha.");
        } else {
            $user = $results[0];

            $result = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip);", array(
                ":iduser" => $user["iduser"],
                ":desip" => $_SERVER["REMOTE_ADDR"]
            ));

            if (count($result) === 0) {
                throw new \Exception("Não é possível recuperar a senha.");
            } else {
                $dataRecovery = $result[0];

                $code = base64_encode(openssl_encrypt($dataRecovery["idrecovery"], "AES-256-CBC", self::SECRET));

                if ($inAdmin) {
                    $link = "localhost/admin/forgot/reset?code=$code";
                } else {
                    $link = "localhost/forgot/reset?code=$code";
                }


                $mailer = new Mailer($user["desemail"], $user["desperson"], "Redefinir senha", "forgot", array(
                    "name" => $user["desperson"],
                    "link" => urldecode($link)
                ));

                $mailer->send();
                return $result;
            }
        }
    }

    public static function validForgotDecryp($code) {
        $idRecovery = openssl_decrypt(base64_decode($code), "AES-256-CBC", self::SECRET);

        $sql = new Sql();

        $results = $sql->select("
            SELECT *
            FROM tb_userspasswordsrecoveries a
            INNER JOIN tb_users b
            USING (iduser)
            INNER JOIN tb_persons c
            USING (idperson)
            WHERE a.idrecovery = :idrecovevy
            AND a.dtrecovery IS NULL 
            AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();", array(
                ":idrecovevy" => $idRecovery
        ));

        if (count($results) === 0) {
            throw new \Exception("Não foi possível recuperar a senha.");
        } else {
            return $results[0];
        }
    }

    public static function setForgotUsed($idRecovery) {
        $sql = new Sql();

        $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
            ":idrecovery"=>$idRecovery
        ));
    }

    public function setPassword($password) {
        $sql = new Sql();

        $sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
            ":password"=>password_hash($password, PASSWORD_DEFAULT),
            ":iduser"=>$this->getiduser()
        ));
    }

    public static function setMsgError($msg) {
        $_SESSION[self::ERROR] = (string)$msg;
    }

    public static function getMsgError() {
        $msg = $_SESSION[self::ERROR] ?? '';

        self::clearMsgError();

        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[self::ERROR] = null;
    }

    public static function setMsgRegisterError($msg) {
        $_SESSION[self::REGISTER_ERROR] = (string)$msg;
    }

    public static function getMsgRegisterError() {
        $msg = $_SESSION[self::REGISTER_ERROR] ?? '';

        self::clearMsgError();

        return $msg;
    }

    public static function clearMsgRegisterError() {
        $_SESSION[self::REGISTER_ERROR] = null;
    }

    public static function setMsgSuccess($msg) {
        $_SESSION[self::SUCCESS] = (string)$msg;
    }

    public static function getMsgSuccess() {
        $msg = $_SESSION[self::SUCCESS] ?? '';

        self::clearMsgSuccess();

        return $msg;
    }

    public static function clearMsgSuccess() {
        $_SESSION[self::SUCCESS] = null;
    }

    public function getOrders() {
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
                                           WHERE a.iduser = :iduser", [
            ':iduser' => $this->getiduser()
        ]);

        return $results;
    }

    public static function getPage($page = 1, $itemsPerPage = 10, $search = null) {
        $start = ($page-1)*$itemsPerPage;

        $sql = new Sql();

        if (strlen($search) > 0) {
            $searchSql = " WHERE b.desperson LIKE :search
                             OR b.desemail LIKE :search
                             OR a.deslogin LIKE :search ";
        }

        $results = $sql->select("
            SELECT SQL_CALC_FOUND_ROWS *
              FROM tb_users a
        INNER JOIN tb_persons b
             USING (idperson)"
             . $searchSql .
         "ORDER BY b.desperson
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