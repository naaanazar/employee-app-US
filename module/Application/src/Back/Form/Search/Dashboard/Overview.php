<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Form\Search\Sort;
use Application\Back\Paginator\Doctrine;
use Application\Model\Employee;
use Application\Model\Repository\EmployeeRepository;
use Application\Module;
use Zend\Paginator\Paginator;

/**
 * Class Statistic
 * @package Application\Back\Form\Search\Dashboard
 */
class Overview extends AbstractSearch
{


    /**
     * @return Paginator
     */
    public function getResult()
    {
        /** @var EmployeeRepository $employeesRepository */
        $employeesRepository = Module::entityManager()->getRepository(Employee::class);

        $employeesRepository
            ->addExpression('contains', 'name', $this->data['name'])
            ->addExpression('contains', 'surname', $this->data['surname'])
            ->addExpression('contains', 'city', $this->data['city'])
            ->addExpression('contains', 'zip', $this->data['zip'])
            ->addExpression('eq', 'carAvailable', $this->data['car_available'])
            ->addExpression('eq', 'drivingLicence',$this->data['driving_license']);

        if (!empty($this->data['start'])) {
            $employeesRepository
                ->addExpression('gt', 'startDate', (new \DateTime ($this->data['start'])))
                ->addExpression('lt', 'startDate', (new \DateTime ($this->data['end'])));
        }

        $criteria = $employeesRepository->buildCriteria();
        $sort= new Sort;
        $criteria->orderBy(
            $sort->getSortValue(
                $this->data['column_sort_name'],
                $this->data['column_sort_order']
            )
        );


        return (new Doctrine(Employee::class, $criteria))
            ->setLimit(20, $this->data('page', 1));
    }

}