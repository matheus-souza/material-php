<?php

namespace mail;

use Rain\Tpl;

class Mailer
{
    const HOST = "smtp.mailtrap.io";
    const PORT = 465;
    const SMTP_AUTH = true;
    const USERNAME = "mail@mail.com";
    const PASSWORD = "password";
    const NAME_FROM = "Ecommerce";

    private $mail;

    public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
    {
        $config = array(
            "tpl_dir" => $_SERVER["DOCUMENT_ROOT"] . "/views/email/",
            "cache_dir" => "views-cache/",
            "debug" => true,
        );

        Tpl::configure($config);

        $tpl = new Tpl;

        foreach ($data as $key => $value) {
            $tpl->assign($key, $value);
        }

        $html = $tpl->draw($tplName, true);

        $this->mail = new \PHPMailer;

        $this->mail->isSMTP();


        $this->mail->SMTPDebug = 0;

        $this->mail->Debugoutput = 'html';

        $this->mail->Host = self::HOST;

        $this->mail->Port = self::PORT;

        $this->mail->SMTPSecure = 'tls';

        $this->mail->SMTPAuth = self::SMTP_AUTH;

        $this->mail->Username = self::USERNAME;

        $this->mail->Password = self::PASSWORD;

        $this->mail->setFrom(self::USERNAME, self::NAME_FROM);

        $this->mail->addReplyTo(self::NAME_FROM, self::NAME_FROM);

        $this->mail->addAddress($toAddress, $toName);

        $this->mail->Subject = $subject;

        $this->mail->msgHTML($html);

        $this->mail->AltBody = 'E-mail de redefinição de senha';
    }

    public function send()
    {
        return $this->mail->send();
    }
}

?>