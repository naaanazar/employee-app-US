<?php

namespace Application\Controller;

use Application\Model\User;
use Application\Module;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\View\Renderer\RendererInterface;

/**
 * Class AbstractController
 * @package Application\Controller
 */
abstract class AbstractController extends AbstractActionController
{

    /**
     * Initial method for controllers
     */
    public function init()
    {
    }

    /**
     * @param MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $e->getViewModel()->setVariable('user', $this->getUser());
        $this->init();

        $result = parent::onDispatch($e);

        if (true === $this->getRequest()->isXmlHttpRequest()
            && get_class($result) === ViewModel::class
        ) {
            $result = new JsonModel(
                [
                    'html' => $this->getRenderer()->render($result)
                ]
            );

            $e->setResult($result);
        }

        return $result;
    }

    /**
     * @return EntityManager mixed
     */
    public function getEntityManager()
    {
        return Module::entityManager();
    }

    /**
     * @return AuthenticationService
     */
    public function getAuth()
    {
        return $this->getEvent()
            ->getApplication()
            ->getServiceManager()
            ->get('auth');
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->getAuth()->getStorage()->read();
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function translate($string)
    {
        return Module::translator()->translate($string);
    }

    /**
     * @param string $rendererName
     * @return RendererInterface
     */
    public function getRenderer($rendererName = 'PhpRenderer')
    {
        $renderer = $this->getEvent()
            ->getApplication()
            ->getServiceManager()
            ->get('Zend\View\Renderer\\' . $rendererName);

        return $renderer;
    }

    /**
     * @param bool $role
     * @return \Zend\Http\Response
     */
    public function restrictNonLoggedIn($role = false)
    {

        $user = $this->getUser();

        if (null === $user || (false !== $role && true === in_array($user->getRole(), (array)($role)))) {
            return $this->redirect()->toRoute('user', ['action' => 'login']);
        }
    }

}