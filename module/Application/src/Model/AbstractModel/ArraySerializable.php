<?php

namespace Application\Model\AbstractModel;

/**
 * Class ArraySerializable
 * @package Application\Model\AbstractModel
 */
abstract class ArraySerializable
{

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        if (static::class === ($class = get_parent_class($this))) {
            $class = get_class($this);
        }

        $properties = (new \ReflectionClass($class))
            ->getProperties(\ReflectionProperty::IS_PRIVATE);

        foreach ($properties as $property) {
            if (1 === preg_match('/@ORM\\\Column/', $property->getDocComment())) {
                $property->setAccessible(true);
                $result[$property->getName()] = $property->getValue($this);
                $property->setAccessible(false);
            }
        }

        return $result;
    }

}