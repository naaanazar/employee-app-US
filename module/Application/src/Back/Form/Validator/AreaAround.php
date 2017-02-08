<?php

namespace Application\Back\Form\Validator;

use Application\Model\Area;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Validator\AbstractValidator;

/**
 * Class Password
 * @package Application\Back\Form\Validator
 */
class AreaAround extends AbstractValidator
{
    /**
     * Messages keys
     */
    const EXCEPTION = 'exception';
    const NOT_FOUND = 'not_found';
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

            $entityManager = $this->getOption('entityManager');

            if (true === $entityManager instanceof EntityManager) {
                /** @var EntityManager $entityManager*/

                /** @var EntityRepository $repository */
                $repository = $entityManager->getRepository(Area::class);

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