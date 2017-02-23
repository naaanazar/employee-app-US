<?php

namespace Application\Model\Repository;

use Application\Back\Form\Search\Sort;
use Application\Back\Paginator\Adapter\Doctrine;
use Application\Model\Area;
use Application\Model\Coordinates;
use Application\Model\Employee;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Zend\Paginator\Paginator;

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

            if ((false === $expression['value'] || false === empty($expression['value'])) || $expression['expression'] === 'in') {

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

    /**
     * @param $params
     * @param bool $paginator
     * @return Employee[]|Paginator
     */
    public function searchByParams($params, $paginator = false)
    {
        $this
            ->addExpression('eq', 'deleted', false)
            ->addExpression('contains', 'name', $params['name'])
            ->addExpression('contains', 'surname', $params['surname'])
            ->addExpression('contains', 'city', $params['city'])
            ->addExpression('contains', 'zip', $params['zip'])
            ->addExpression('eq', 'carAvailable', $params['car_available'])
            ->addExpression('eq', 'drivingLicence', $params['driving_license'])
            ->addExpression('eq', 'areaAround', $this->getEntityManager()->getRepository(Area::class)->find($params['area_around']));


        if (false === empty($post['start'])) {
            $this
                ->addExpression('gt', 'startDate', (new \DateTime ($params['start'])))
                ->addExpression('lt', 'startDate', (new \DateTime ($params['end'])));
        }

        if (false === empty($params['latitude']) && false === empty($params['longitude'])) {
            $coordinates = (new Coordinates())
                ->setLatitude($params['latitude'])
                ->setLongitude($params['longitude']);

            /** @var CoordinatesRepository $coordinatesRepo */
            $coordinatesRepo = $this->getEntityManager()->getRepository(Coordinates::class);

            $coordinates = $coordinatesRepo->getCoordinatesInRange($coordinates);

            $employeesIds = array_map(
                function ($coordinate) {
                    /** @var Coordinates $coordinate */
                    return $coordinate->getEmployee()->getId();
                },
                $coordinates
            );

            $this->addExpression('in', 'id', $employeesIds);
        }


        $criteria = $this->buildCriteria();
        $sortValue = (new Sort())->getSortValue($params['sort_name'], $params['sort_order']);

        if (false !== $sortValue) {
            $criteria->orderBy($sortValue);
        }

        if (true === $paginator) {
            return new Paginator(new Doctrine(Employee::class, $criteria));
        } else {
            return $this->matching($criteria)->toArray();
        }
    }

}