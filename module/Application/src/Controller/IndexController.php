<?php

namespace Application\Controller;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;

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
        $this->redirect()
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
        $storage->offsetSet('language', $this->getRequest()->getQuery('language', 'en_US'));
        $this->redirect()->toUrl($this->getRequest()->getHeader('Referer')->uri()->getPath());
    }
}
