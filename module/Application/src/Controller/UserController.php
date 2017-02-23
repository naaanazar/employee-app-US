<?php

namespace Application\Controller;

use Application\Back\Form\Login;
use Application\Back\Form\Register;
use Application\Model\RegisterKey;
use Application\Model\User;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class UserController
 * @package Application\Controller
 */
class UserController extends AbstractController
{

    public function restrictLoggedIn()
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
        $this->restrictLoggedIn();

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
     * @return ViewModel|array
     */
    public function registerAction()
    {
        $this->restrictLoggedIn();

        /** @var Request $request */
        $request = $this->getRequest();
        $repo = $this->getEntityManager()->getRepository(RegisterKey::class);

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

                if (false !== ($registerKey = $this->params('key', false))
                    && $registerKey = $repo->findOneBy(['value' => $registerKey])
                ) {
                    /** @var RegisterKey $role */
                    $role = $registerKey->getRole();
                } else {
                    $role = User::ROLE_USER;
                }

                $user->setRole($role);

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                if ($registerKey !== false && $registerKey !== null) {
                    $registerKey->setUsed(true);
                    $registerKey->setUser($user);

                    $this->getEntityManager()->persist($registerKey);
                    $this->getEntityManager()->flush();
                }

                $this->getAuth()->getStorage()->write($user->getId());

                $json->setVariables(
                    [
                        'redirect' => $this->url()->fromRoute('home'),
                        'message'  => $this->translate('Successfully registered')
                    ]
                );
            } else {
                $json->setVariable('errors', $form->getMessages());
            }

            return $json;
        }

        return new ViewModel();
    }

    /**
     * @return void
     */
    public function logoutAction()
    {
        $this->getAuth()
            ->getStorage()
            ->clear();

        $this->redirect()->toRoute('user', ['action' => 'login']);
    }

    /**
     * Show one Employee action
     *
     * @return ViewModel
     */
    public function showAction()
    {
        $id = $this->params('id');
        $view = new ViewModel();
        $view->setTemplate('application/user/show.phtml');

        if (null === $id
            || null === (
            $employee = $this->getEntityManager()
                ->getRepository(User::class)
                ->find($id)
            )
        ) {
            $this->notFoundAction();
        } else {
            $view->setVariable('user', $employee);
        }

        return $view;
    }
    
}