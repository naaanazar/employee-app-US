<?php

namespace Application\Back\Form\Validator;

use Application\Module;
use Application\Model\Contract;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Validator\AbstractValidator;

/**
 * Class Password
 * @package Application\Back\Form\Validator
 */
class ContractType extends AbstractValidator
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
            $contract = Module::entityManager()
                ->getRepository(Contract::class)
                ->find($value);

            if($contract !== null){
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