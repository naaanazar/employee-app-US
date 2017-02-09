<?php

namespace Application\Back\Form\Validator;

use Application\Module;
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
        self::NOT_FOUND => /*translate*/'Incorrect value'/*translate*/
    ];

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = false;

        try {
            /** @var EntityRepository $repository */
            $repository = Module::entityManager()->getRepository(Area::class);

            $areaAround = $repository->find($value);

            if($areaAround !== null){
                $result = true;
            } else {
                $this->error(static::NOT_FOUND);
            }
        } catch (\Exception $exception) {
            $this->error(static::EXCEPTION);
        }

        return $result;
    }

}