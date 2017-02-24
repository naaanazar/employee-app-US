<?php

namespace Application\Back\Controller\Helper;

use Application\Module;
use Zend\View\Model\JsonModel;

/**
 * Class ConfigureActions
 * @package Application\Back\Form\Search
 */
class ConfigureActions
{

    public function detete($model, $id)
    {
        $result = new JsonModel();

        $field = Module::entityManager()
            ->getRepository($model)
            ->findOneBy(
                [
                    'id' => $id
                ]
            );

        if ($field !== null) {

            Module::entityManager()->remove($field);
            Module::entityManager()->flush();

            $result->setVariables(
                [
                    'result' => true
                ]
            );
        }

        return $result;
    }
}

