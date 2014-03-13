<?php

namespace MuServer\Repository;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Zend\Crypt\Password\Bcrypt;

class Account extends EntityRepository
{
    public function authenticate($login, $password)
    {
        /** @var \MuServer\Entity\Account $account */
        $account = current($this->findBy(['login' => $login]));

        if (empty($account)) {
            throw new EntityNotFoundException;
        }

        if (!$this->getBCrypt()->verify($password, $account->getPassword())) {
            throw new InvalidPasswordException;
        }

        return $account;
    }

    private function getBCrypt()
    {
        $bCrypt = new Bcrypt();
        $bCrypt->setCost(10);
        $bCrypt->setSalt(sha1(uniqid()));

        return $bCrypt;
    }
}