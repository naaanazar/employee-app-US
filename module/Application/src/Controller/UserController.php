<?php

namespace Application\Controller;

use Application\Back\Form\Login;
use Application\Back\Form\Register;
use Application\Model\User;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

/**
 * Class UserController
 * @package Application\Controller
 */
class UserController extends AbstractController
{

    /**
     * Login action
     * @return ViewModel
     */
    public function loginAction()
    {
        $view = new ViewModel();

        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost() && true === $request->isXmlHttpRequest()) {

            $form = new Login(
                [
                    'email'         => $request->getPost('email'),
                    'entityManager' => $this->getEntityManager()
                ]
            );
            $data = $request->getPost();

            if ($form->setData($data)->isValid() === true) {
                $this->redirect()->toRoute('home');
            } else {
                $view->setVariable('messages', $form->getMessages());
            }
        }

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function registerAction()
    {
        $view = new ViewModel();

        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost() && true === $request->isXmlHttpRequest()) {

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

                $this->redirect()->toRoute('user', ['action' => 'login']);
            } else {
                $view->setVariable('messages', $form->getMessages());
            }
        }

        return $view;
    }
    
}