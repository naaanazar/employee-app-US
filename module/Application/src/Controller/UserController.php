<?php

namespace Application\Controller;

use Application\Back\Form\ForgotPassword;
use Application\Back\Form\Login;
use Application\Back\Form\RecoverPassword;
use Application\Back\Form\Register;
use Application\Model\PasswordRecovery;
use Application\Model\RegisterKey;
use Application\Model\User;
use Application\Module;
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
                    && $registerKey = $repo->findOneBy(['value' => $registerKey, 'user' => null])
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

    /**
     * @return JsonModel|ViewModel
     */
    public function forgotPasswordAction()
    {
        if (true === $this->getRequest()->isPost()) {
            $result = new JsonModel();

            $form = new ForgotPassword();
            $form->setData($this->getRequest()->getPost()->toArray());

            if (true === $form->isValid()) {
                /** @var User $user */
                $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['email' => $form->get('email')->getValue()]);

                $recovery = new PasswordRecovery();
                $recovery->setUser($user);
                $recovery->setHash(PasswordRecovery::generateHash());
                $recovery->setActive(true);

                $this->getEntityManager()->persist($recovery);
                $this->getEntityManager()->flush($recovery);

                Module::getMailSender()->sendMail(
                    Module::translator()->translate('Password recovery'),
                    $user->getEmail(),
                    'user/forgot-password',
                    [
                        'email' => $user->getEmail(),
                        'hash'  => $recovery->getHash()
                    ]
                );

                $result->setVariable('redirect', $this->url()->fromRoute('index', ['action' => 'information']));

            } else {
                $result->setVariable('errors', $form->getMessages());
            }

            return $result;
        }

        return new ViewModel();
    }

    /**
     * @return array|JsonModel|ViewModel
     */
    public function recoverPasswordAction()
    {
        if (true === $this->getRequest()->isPost()) {
            $form = new RecoverPassword();
            $form->setData($this->getRequest()->getPost()->toArray());

            $result = new JsonModel();

            if (false === $form->isValid()) {
                $result->setVariable('errors', $form->getMessages());
            } else {
                /** @var PasswordRecovery $recoverModel */
                $recoverModel = $this->getEntityManager()
                    ->getRepository(PasswordRecovery::class)
                    ->findOneBy(
                        [
                            'hash' => $this->getRequest()->getPost('hash'),
                            'active' => true
                        ]
                    );

                if ($recoverModel === null) {
                    $result->setVariable('errors', ['password' => [Module::translator()->translate('Recovery password link is not valid')]]);
                } else {
                    $recoverModel->setActive(false);
                    $this->getEntityManager()->persist($recoverModel);

                    $user = $recoverModel->getUser();
                    $user->setPassword(User::hashPassword($form->get('password')->getValue()));

                    $this->getEntityManager()->flush();

                    $result->setVariable('redirect', $this->url()->fromRoute('user', ['action' => 'login']));
                }
            }

            return $result;

        } else {
            $hash = $this->params('hash');
            $view = new ViewModel();

            if (true === empty($hash)) {
                return $this->notFoundAction();
            }

            /** @var PasswordRecovery $recoverModel */
            $recoverModel = $this->getEntityManager()
                ->getRepository(PasswordRecovery::class)
                ->findOneBy(
                    [
                        'hash' => $hash,
                        'active' => true
                    ]
                );

            if (null === $recoverModel) {
                return $this->notFoundAction();
            }

            $view->setVariable('hash', $hash);

            return $view;
        }
    }

    /**
     * @return \Zend\Http\Response
     */
    public function recoverPasswordCancelAction()
    {
        /** @var PasswordRecovery $recoverModel */
        $recoverModel = $this->getEntityManager()
            ->getRepository(PasswordRecovery::class)
            ->findOneBy(
                [
                    'hash' => $this->params('hash'),
                    'active' => true
                ]
            );

        if ($recoverModel !== null) {
            $recoverModel->setActive(false);
            $this->getEntityManager()->persist($recoverModel);
            $this->getEntityManager()->flush();
        }

        return $this->redirect()->toRoute('home');
    }
    
}