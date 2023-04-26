<?php

use Phalcon\Mvc\Controller;
use handler\Aware\Aware;
use handler\Listener\Listener;
use Phalcon\Events\Manager as EventsManager;
// sign up controller class
class SignupController extends Controller
{
    public function IndexAction()
    {
        // default login view
    }
    public function registerAction()
    {

        $eventsManager = new EventsManager();
        $componant = new Aware();
        $componant->setEventsManager($eventsManager);
        $eventsManager->attach(
            'application',
            new Listener()
        );
        $componant->process();


        $escaper = new Myescaper();
        $user = new Users();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' =>$this->request->getPost('email'),
            'pswd' => $this->request->getPost('pswd')
        ];
        $user->assign(
            $data,
            ['name', 'email', 'pswd']
        );
        $success = $user->save();
        if ($success) {
            // setting the session
            $this->session->set("user_email", $user->email);
            $this->session->set("user_pswd", $user->pswd);
            if ($this->request->getPost('rememberMe') == "on") {
                // setting cookies
                $this->cookies->set("user_email", $this->session->get('user_email'), time() + 15 * 86400);
                $this->cookies->set("user_pswd", $this->session->get('user_pswd'), time() + 15 * 86400);
            } else {
                $this->cookies->set("user_email", $this->session->get('user_email'), time() - 15 * 86400);
                $this->cookies->set("user_pswd", $this->session->get('user_pswd'), time() - 15 * 86400);
            }
            $this->view->message = true;
        } else {
            $this->logger
                ->excludeAdapters(['login'])
                ->info(implode("<br>", $user->getMessages()));
            $fullMsg = "Not Register succesfully due to following reason: <br>" . implode("<br>", $user->getMessages());
            $this->view->message = false;
            $this->view->fullMsg = $fullMsg;
        }
        unset($success);
    }
}
