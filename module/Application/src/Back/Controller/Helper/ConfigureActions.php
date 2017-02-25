<?php

namespace Application\Back\Controller\Helper;

use Application\Module;
use Zend\View\Model\JsonModel;

/**
 * Class ConfigureActions
 * @package Application\Back\Controller\Helper
 * @method \Zend\Mvc\Controller\Plugin\Url url()
 */
class ConfigureActions
{
    /**
     * @param $model
     * @param $id
     * @return JsonModel
     */
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

        if ($field) {

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

    /**
     * @param $data
     * @param $model
     * @param $redirect
     * @param $method
     * @param null $intIndex
     * @return JsonModel
     */
    public function store($data, $model, $redirect, $method,  $intIndex = null){

        $json = new JsonModel();

        if (isset($data['id'])){
            $model = Module::entityManager()
                ->getRepository($model)
                ->findOneBy(
                    [
                        'id' => $data['id']
                    ]
                );
        } else {
            $model = new $model;
        }

        try {
            if (isset($data['id'])){
                Module::entityManager()->merge($this->$method($model, $data, $intIndex));
            } else {
                Module::entityManager()->persist($this->$method($model, $data, $intIndex));
            }
            Module::entityManager()->flush();

            $json->setVariable(
                'redirect', $redirect);
        } catch (ORMInvalidArgumentException $exception) {
            $json->setVariable('message', 'Invalid data to save area around');
        } catch (OptimisticLockException $exception) {
            $json->setVariable('message', 'Can not save area around to database');
        }

        return $json;
    }

    /**
     * @param $model
     * @param $data
     * @param $intIndex
     * @return mixed
     */
    protected function setNumberConfiguration($model, $data, $intIndex){
        $model->setIntValue($this->valueToInt($data['value'], $intIndex));
        $model->setValue($data['value']);

        return $model;
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    protected function setTextConfiguration($model, $data){
        $model->setCode($this->valueToCode($data['value']));
        $model->setName($data['value']);

        return $model;
    }

    /**
     * @param $value
     * @param $intIndex
     * @return mixed
     */
    protected function valueToInt($value, $intIndex){
        $intValue = preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$value) * $intIndex;

        return $intValue;
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function valueToCode($value){
        $code  = str_replace(" ", "-", preg_replace('/\s\s+/', ' ', $value));

        return $code;

    }
}

