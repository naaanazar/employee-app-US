<?php

namespace Application\Model\Repository;

use Application\Model\Employee;
use Doctrine\ORM\EntityRepository;

/**
 * Class CommentRepository
 * @package Application\Model\Repository
 */
class CommentRepository extends EntityRepository
{

    /**
     * @param Employee $employee
     * @return array
     */
    public function getCommentsByEmployee(Employee $employee)
    {
        return $this->findBy(
            [
                'employee' => $employee
            ]
        );
    }

}