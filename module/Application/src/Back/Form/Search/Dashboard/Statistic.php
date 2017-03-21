<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Paginator\Doctrine;
use Application\Model\Employee;
use Application\Model\Repository\EmployeeRepository;
use Application\Module;
use Zend\Paginator\Paginator;
use Application\Back\Form\Search\Sort;

/**
 * Class Statistic
 * @package Application\Back\Form\Search\Dashboard
 */
class Statistic extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        /** @var EmployeeRepository $employeesRepository */
        $employeesRepository = Module::entityManager()->getRepository(Employee::class);

        if (false === empty($this->data['statistic_date'])) {
            $dateEnd = new \DateTime ();
            $dateStart = new \DateTime (date('Y-m-d', strtotime("-". $this->data['statistic_date'] ." days")));

            $employeesRepository
                ->addExpression('gt', 'created', $dateStart)
                ->addExpression('lt', 'created', $dateEnd);
        }

        $criteria = $employeesRepository->buildCriteria();

        $sortValue = (new Sort())->getSortValue($this->data['sort_name'], $this->data['sort_order']);

        if (false !== $sortValue) {
            $criteria->orderBy($sortValue);
        }

        return (new Doctrine(Employee::class, $criteria))
            ->setLimit(20, $this->data('page', 1));
    }

}