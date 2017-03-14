<?php

namespace Application\Controller;

use Application\Model\Coordinates;
use Application\Model\Repository\CoordinatesRepository;
use Zend\Http\Header\Referer;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractController
{

    /**
     * Index action
     * @return ViewModel
     */
    public function indexAction()
    {
        return $this->redirect()
            ->toRoute(
                'employee',
                ['action' => 'index']
            );
    }

    /**
     * Change language actions
     */
    public function langAction()
    {
        $storage = new Container('language');
        $storage->offsetSet('language', $this->getRequest()->getQuery('language', 'de_DE'));

        /** @var Referer $referer */
        $referer = $this->getRequest()->getHeader('Referer');

        $this->redirect()->toUrl($referer->uri()->getPath());
    }

    /**
     * get language
     */
    public function getLangAction()
    {
        $storage = new Container('language');


        if (false === $storage->offsetExists('language')) {
            $lang = 'de_DE';
        } else {
            $lang = $storage->offsetGet('language');
        }


        $view = new JsonModel(['lang' => $lang]);

        return $view;
    }



    /**
     * Information action
     */
    public function informationAction()
    {
    }

    /**
     * return path to file
     *
     * @return JsonModel
     */
    public function basePathAction()
    {
        if (true === $this->getRequest()->isPost() && true === $this->getRequest()->isXmlHttpRequest()) {
            $helper = $this->getEvent()
                ->getApplication()
                ->getServiceManager()
                ->get('ViewHelperManager')
                ->get('BasePath');

            $path = $helper($this->getRequest()->getPost('path'));

            $view = new JsonModel(['path' => $path]);

            return $view;
        }

        return $this->notFoundAction();
    }
}
