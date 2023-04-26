<?php

namespace handler\Listener;

use Phalcon\Di\Injectable;
use Phalcon\Escaper;

class Listener extends Injectable
{

    public function dbEvent()
    {
        $escaper = new Escaper();
        $name = $escaper->escapeHtml("$_POST[name]");
        $email = $escaper->escapeHtml("$_POST[email]");
        $pswd = $escaper->escapeHtml("$_POST[pswd]");
        $this->insertLog($_POST['name'], $name);
        $this->insertLog($_POST['email'], $email);
        $this->insertLog($_POST['pswd'], $pswd);
        $_POST['name'] = $name;
        $_POST['email'] = $email;
        $_POST['pswd'] = $pswd;
    }
    public function insertLog($data, $escapedData)
    {
        if ($data != $escapedData) {
            $this->logger->info($data . "<= injected script");
            $this->response->redirect('signup');
        }
    }

}
