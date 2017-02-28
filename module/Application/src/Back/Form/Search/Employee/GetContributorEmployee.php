<?php

namespace Application\Back\Form\Search\Employee;

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
class GetContributorEmployee extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        /** @var EmployeeRepository $employeesRepository */
        $employeesRepository = Module::entityManager()->getRepository(Employee::class);
        $employeesRepository
            ->addExpression('eq', 'user', $this->data['user']);

        $criteria = $employeesRepository->buildCriteria();

        $criteria->orderBy(['created' => 'DESC']);

        return (new Doctrine(Employee::class, $criteria))
            ->setLimit(20, $this->data('page', 1));
    }

}