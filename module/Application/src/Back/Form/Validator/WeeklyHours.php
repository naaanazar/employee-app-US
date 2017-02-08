<?php

namespace Application\Back\Form\Validator;

use Application\Module;
use Application\Model\WeeklyHours as WHours;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Validator\AbstractValidator;

/**
 * Class Password
 * @package Application\Back\Form\Validator
 */
class WeeklyHours extends AbstractValidator
{
    /**
     * Messages keys
     */
    const NOT_FOUND = 'not_found';
    const EXCEPTION = 'exception';
    /**
     * Messages templates
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_FOUND => 'Incorect value'
    ];

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = false;

        try {
            $entityManager = Module::entityManager();

            if (true === $entityManager instanceof EntityManager) {
                /** @var EntityManager $entityManager*/

                /** @var EntityRepository $repository */
                $repository = $entityManager->getRepository(WHours::class);

                $criteria = [
                    'value'    => $value,
                ];

                $res = $repository->findOneBy($criteria);

                if($res !== null){
                    $result = true;
                } else {
                    $this->error(static::NOT_FOUND);
                }
            }
        } catch (\Exception $exception) {
            $this->error(static::EXCEPTION);
        }

        return $result;
    }

}