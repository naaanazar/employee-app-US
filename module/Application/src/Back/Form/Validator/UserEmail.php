<?php

namespace Application\Back\Form\Validator;

use Application\Module;
use Doctrine\ORM\EntityRepository;
use Zend\Validator\AbstractValidator;

/**
 * Class Password
 * @package Application\Back\Form\Validator
 */
class UserEmail extends AbstractValidator
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
        self::NOT_FOUND => /*translate*/'User with such email does not exist'/*translate*/,
        self::EXCEPTION => /*translate*/'Some error occurred while validating email'/*translate*/
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
            $repository = Module::entityManager()->getRepository($this->getOption('entity'));

            $areaAround = $repository->findOneBy(
                ['email' => $value]
            );

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