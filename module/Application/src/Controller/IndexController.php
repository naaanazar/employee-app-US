<?php

namespace Application\Controller;

use Application\Back\Map\Agregator;
use Application\Model\Coordinates;
use Application\Model\Repository\CoordinatesRepository;
use Zend\Http\Header\Referer;
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
        $storage->offsetSet('language', $this->getRequest()->getQuery('language', 'en_US'));

        /** @var Referer $referer */
        $referer = $this->getRequest()->getHeader('Referer');

        $this->redirect()->toUrl($referer->uri()->getPath());
    }


    public function testAction()
    {
        /** @var CoordinatesRepository $coordinatesRepository */
        $coordinatesRepository = $this->getEntityManager()
            ->getRepository(Coordinates::class);

        $coordinate = $coordinatesRepository->find(1);

        var_dump(count($coordinatesRepository->getCoordinatesInRange($coordinate, 500)));die;
    }
}
