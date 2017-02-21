<?php

namespace Application\Model\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

/**
 * Class EmployeeRepository
 * @package Application\Model\Repository
 */
class EmployeeRepository extends EntityRepository
{

    /**
     * @var array
     */
    protected $expressions = [];


    /**
     * @return Criteria
     */
    public function buildCriteria()
    {
        $criteria = new Criteria();

        foreach ($this->expressions as $expression) {

            if (false === empty($expression['value']) || $expression['expression'] === 'in') {

                $criteriaExpression = $criteria->expr()
                    ->{$expression['expression']}($expression['name'], $expression['value']);

                if (null !== $expression['priority']) {
                    $criteria->{$expression['priority'] . 'Where'}($criteriaExpression);
                } else {
                    $criteria->where($criteriaExpression);
                }

            }
        }

        return $criteria;
    }

    /**
     * @param $expression
     * @param $name
     * @param $value
     * @param string $priority
     * @return $this
     */
    public function addExpression($expression, $name, $value, $priority = 'and')
    {
        if (true === empty($this->expressions)) {
            $priority = null;
        }

        $this->expressions[] =
            [
                'expression' => $expression,
                'name'       => $name,
                'value'      => $value,
                'priority'   => $priority
            ];

        return $this;
    }

}