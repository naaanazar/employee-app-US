<?php

namespace Application\Back\Form\Validator;

use Application\Back\Form\Login;
use Application\Model\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Validator\AbstractValidator;

/**
 * Class Password
 * @package Application\Back\Form\Validator
 */
class Password extends AbstractValidator
{

    /**
     * Validator check constants
     */
    const CHECK_LOGIN    = 'login';
    const CHECK_REGISTER = 'password';

    /**
     * Messages keys
     */
    const FOUND     = 'found';
    const NOT_FOUND = 'not_found';
    const EXCEPTION = 'exception';
    const BLOCKED = 'blocked';

    /**
     * Messages templates
     * @var array
     */
    protected $messageTemplates = [
        self::FOUND     => /*translate*/'User with same email found'/*translate*/,
        self::NOT_FOUND => /*translate*/'Invalid email or/and password'/*translate*/,
        self::EXCEPTION => /*translate*/'Some error occurred while logging in'/*translate*/,
        self::BLOCKED   => /*translate*/'User is deleted'/*translate*/,
    ];

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = false;

        try {

            $email = $this->getOption('email');
            $entityManager = $this->getOption('entityManager');
            $check = $this->getOption('check');

            if (true === $entityManager instanceof EntityManager) {
                /** @var EntityManager $entityManager*/

                /** @var EntityRepository $repository */
                $repository = $entityManager->getRepository(User::class);

                $criteria = [
                    'email'    => $email,
                ];

                if ($check === static::CHECK_LOGIN) {
                    $criteria['password'] = User::hashPassword($value);
                }

                $user = $repository->findOneBy($criteria);

                switch (true) {
                    case $user !== null && $user->getRole() === User::ROLE_BLOCKED:
                        /** @var Login $form */
                        $this->error(static::BLOCKED);
                        break;
                    case $user !== null && $check === static::CHECK_LOGIN:
                        /** @var Login $form */
                        $form = $this->getOption('form');
                        $form->setIdentity($user->getId());
                        $result = true;
                        break;
                    case $user === null && $check === static::CHECK_REGISTER:
                        $result = true;
                        break;
                    case $user === null && $check === static::CHECK_LOGIN:
                        $this->error(static::NOT_FOUND);
                        break;
                    case $user !== null && $check === static::CHECK_REGISTER:
                        $this->error(static::FOUND);
                        break;
                }
            }

        } catch (\Exception $exception) {
            $this->error(static::EXCEPTION);
        }

        return $result;
    }

}