<?php

namespace Application\Model\AbstractModel;

/**
 * Class ArraySerializable
 * @package Application\Model\AbstractModel
 */
abstract class ArraySerializable
{

    /**
     * @param $model
     * @return array
     */
    public function toArray($model)//: array
    {
        $array = [];

        $properties = (new ReflectionClass(\Application\Model\$model))
            ->getProperties(\ReflectionProperty::IS_PRIVATE);

        foreach ($properties as $property) {
            if (1 === preg_match('/[\s\t]+@ORM\Column[\s\t]+/', $property->getDocComment())) {
                $array[] = $property;
            }
        }

        return $array;
    }

}