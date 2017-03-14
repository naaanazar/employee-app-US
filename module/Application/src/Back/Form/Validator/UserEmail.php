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
    const FOUND     = 'found';
    /**
     * Messages templates
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_FOUND => /*translate*/'User with such email does not exist'/*translate*/,
        self::FOUND => /*translate*/'User with such email exists'/*translate*/,
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

            if (true === in_array($value, $this->getOption('allowed_emails'))) {
                $result = true;
            } else {
                $entity = $repository->findOneBy(
                    ['email' => $value]
                );

                if (true === $this->getOption('direction')) {
                    if ($entity !== null){
                        $result = true;
                    } else {
                        $this->error(static::NOT_FOUND);
                    }
                } else {
                    if ($entity === null){
                        $result = true;
                    } else {
                        $this->error(static::FOUND);
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->error(static::EXCEPTION);
        }

        return $result;
    }

}