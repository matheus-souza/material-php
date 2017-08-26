<?php

namespace models;

use \Mail\Mailer;
use \Db\Sql;
use \Models\Model;

class User extends Model {

    const SESSION = "User";
    const SECRET = "d41d8cd98f00b204e9800998ecf8427e";

    public static function login($login, $password) {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
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
        if (!isset($_SESSION[User::SESSION]) ||
            !$_SESSION[User::SESSION] ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0 ||
            (bool)$_SESSION[User::SESSION]["inadmin"] != $inAdmin) {
            header("Location: /admin/login");
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

    public static function getForgot($email) {
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

                $link = "localhost/admin/forgot/reset?code=$code";

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

}


?>