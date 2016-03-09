<?php

namespace MuServer\Repository;

use Doctrine\ORM\EntityRepository;
use MuServer\Entity\Account as AccountEntity;
use MuServer\Entity\Character as CharacterEntity;

class Character extends EntityRepository
{
    public function createCharacter(AccountEntity $account, $name, $class = 0)
    {
        $character = new CharacterEntity();
        $character->setAccount($account);
        $character->setName($name);
        $character->setClass($class);
        $character->setLevel(1);
        $character->setCode(0);
        $character->setIndex(count($account->getCharacters()));

        $this->getEntityManager()->persist($character);
        $this->getEntityManager()->flush($character);
        $this->getEntityManager()->refresh($account);
        $this->getEntityManager()->refresh($character);

        return $character;
    }
}