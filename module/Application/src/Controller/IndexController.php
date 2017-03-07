<?php

namespace Application\Controller;

use Application\Back\Map\Agregator;
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

        $result = $coordinatesRepository->getCoordinatesInRange($coordinate, 40000);
        $result = array_map(
            function ($coord) {
                /** @var Coordinates $coord */
                return $coord->getEmployee()->getName();
            },
            $result
        );

        var_dump($result);die;
    }

    /**
     * return patch to file
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

            $patch = $helper($this->getRequest()->getPost('patch'));

            $view = new JsonModel(['patch' => $patch]);

            return $view;
        }

        return $this->notFoundAction();
    }
}
