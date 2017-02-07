<?php

namespace Application\Controller;

use Application\Back\Form\Login;
use Application\Back\Form\Register;
use Application\Model\User;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class UserController
 * @package Application\Controller
 */
class UserController extends AbstractController
{

    public function init()
    {
        if ($this->getUser() !== null) {
            $this->redirect()->toRoute('home');
        }
    }

    /**
     * Login action
     * @return ViewModel
     */
    public function loginAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost() && true === $request->isXmlHttpRequest()) {

            $json = new JsonModel();

            $form = new Login(
                [
                    'email'         => $request->getPost('email'),
                    'entityManager' => $this->getEntityManager()
                ]
            );

            $data = $request->getPost();

            if ($form->setData($data)->isValid() === true) {
                $json->setVariable('redirect', $this->url()->fromRoute('home'));
                $this->getAuth()->getStorage()->write($form->getIdentity());
            } else {
                $json->setVariable('errors', $form->getMessages());
            }

            return $json;
        }

        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function registerAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost() && true === $request->isXmlHttpRequest()) {

            $json = new JsonModel();

            $form = new Register(
                [
                    'email'         => $request->getPost('email'),
                    'entityManager' => $this->getEntityManager()
                ]
            );

            $data = $request->getPost();

            if ($form->setData($data)->isValid() === true) {

                $user = new User();
                $user->setEmail($form->get('email')->getValue());
                $user->setName($form->get('name')->getValue());
                $user->setPassword(User::hashPassword($form->get('password')->getValue()));
                $user->setRole(0);

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $json->setVariable('message', $this->translate('Successfully registered'));
            } else {
                $json->setVariable('errors', $form->getMessages());
            }

            return $json;
        }

        return new ViewModel();
    }
    
}