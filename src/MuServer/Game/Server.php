<?php

namespace MuServer\Game;

use Doctrine\ORM\EntityNotFoundException;
use MuServer\Entity\Account;
use MuServer\Entity\Character;
use MuServer\Protocol\Debug;
use MuServer\Protocol\Season097\ClientServer\CharListRequest;
use MuServer\Protocol\Season097\ClientServer\CheckSum;
use MuServer\Protocol\Season097\ClientServer\ClientClose;
use MuServer\Protocol\Season097\ClientServer\CreateCharacter;
use MuServer\Protocol\Season097\ClientServer\DeleteCharacter;
use MuServer\Protocol\Season097\ClientServer\Factory;
use MuServer\Protocol\Season097\ClientServer\LoginRequest;
use MuServer\Protocol\Season097\ClientServer\MapJoinRequest;
use MuServer\Protocol\Season097\ClientServer\Ping;
use MuServer\Protocol\Season097\ServerClient\AbstractPacket as SCPacket;
use MuServer\Protocol\Season097\ClientServer\AbstractPacket as CSPacket;
use MuServer\Protocol\Season097\ServerClient\CharCreateResult;
use MuServer\Protocol\Season097\ServerClient\CharDeleteResult;
use MuServer\Protocol\Season097\ServerClient\CharList;
use MuServer\Protocol\Season097\ServerClient\CharListCount;
use MuServer\Protocol\Season097\ServerClient\CharListResult;
use MuServer\Protocol\Season097\ServerClient\CheckExe;
use MuServer\Protocol\Season097\ServerClient\CheckSumResult;
use MuServer\Protocol\Season097\ServerClient\Hp;
use MuServer\Protocol\Season097\ServerClient\InventoryListCount;
use MuServer\Protocol\Season097\ServerClient\Mana;
use MuServer\Protocol\Season097\ServerClient\Message;
use MuServer\Protocol\Season097\ServerClient\StatsInfo;
use MuServer\Protocol\Season097\ServerClient\JoinResult;
use MuServer\Protocol\Season097\ServerClient\LoginResult;
use MuServer\Protocol\Season097\ServerClient\Weather;
use MuServer\Repository\Account as AccountRepository;
use MuServer\Repository\AccountNotFoundException;
use MuServer\Repository\Character as CharacterRepository;
use MuServer\Repository\InvalidPasswordException;
use MuServer\Security;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Server as SocketServer;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Server extends SocketServer implements ServiceLocatorAwareInterface
{
    /** @var ServiceLocatorInterface */
    private $serviceLocator;

    /** @var \SplObjectStorage  */
    private $clients;
    /** @var \SplObjectStorage  */
    private $players;

    /** @var AccountRepository */
    private $accountRepository;
    /** @var CharacterRepository */
    private $characterRepository;

    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);

        $this->clients = new \SplObjectStorage();
        $this->players = new \SplObjectStorage();
    }

    public function init()
    {
        $this->accountRepository = $this->serviceLocator->get('orm_em')->getRepository('MuServer\Entity\Account');
        $this->characterRepository = $this->serviceLocator->get('orm_em')->getRepository('MuServer\Entity\Character');
    }

    public function receive($data, ConnectionInterface $connection)
    {
        Debug::dump($data, 'Received: ');

        try {
            $packet = Factory::buildPacket($data);
        } catch (\RuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }


        if ($packet instanceof Ping) {
            // count ticks
            Debug::dump($data);
        } elseif ($packet instanceof CheckSum) {
//            $account = $this->players->offsetGet($connection);
//
//            if ($packet->getKey() !== 0) {
//                $tNum = $account->getId() % 1024;
//                $wRand = mt_rand(1, 40);
//                $wAcc = (($tNum & 0x3F0) << 6) | ($wRand << 4) | ($tNum & 0xF);
//                $wAcc ^= 0xB479;
//                var_dump($packet->getKey(), $wAcc);
//
//                $result = new CheckExe($wAcc);
//                $this->send($connection, $result);
//                $hp = new Hp(10);
//                $this->send($connection, $hp);
//            } else {
                var_dump($packet);
//            }
        } elseif ($packet instanceof LoginRequest) {
            $result = new LoginResult(LoginResult::SUCCESS);

            try {
                $account = $this->accountRepository->authenticate($packet->getLogin(), $packet->getPassword());

                $this->players->attach($connection, $account);
                $this->send($connection, $result);
            } catch (AccountNotFoundException $e) {
                $result->setResult(LoginResult::INVALID_ACCOUNT);
                $this->send($connection, $result);

                echo "Disconnected [{$connection->getRemoteAddress()}]: invalid account '{$packet->getLogin()}'\n";
                $connection->close();
            } catch (InvalidPasswordException $e) {
                $result->setResult(LoginResult::BAD_PASSWORD);
                $this->send($connection, $result);

                echo "Disconnected [{$connection->getRemoteAddress()}]: invalid password '{$packet->getPassword()}'\n";
                $connection->close();
            }
        } elseif ($packet instanceof CharListRequest) {
            /** @var Account $account */
            $account = $this->players->offsetGet($connection);

            $result = new CharListResult($this->clients->getHash($connection), $account->getId());
            $this->send($connection, $result);

            $chars = [];

            foreach ($account->getCharacters() as $character) {
                $char = new CharList($character);

                $chars[] = $char;
            }

            $result = new CharListCount($chars);
            $this->send($connection, $result);
        } elseif ($packet instanceof MapJoinRequest) {
            /** @var Account $account */

            $account = $this->players->offsetGet($connection);

            $result = new StatsInfo($account->getCharacters()->first());
            $this->send($connection, $result);

            /*
            $hp = new Hp(10);
            $this->send($connection, $hp);

            $mana = new Mana(10);
            $this->send($connection, $mana);

            $weather = new Weather();
            $this->send($connection, $weather);

            $connection->write(pack('c*', 0xc1, 0x05, 0x0b, 0x00, 0x03));
            $connection->write(pack('c*', 0xc1, 0x06, 0x03, 0x00, 0xb6));

            $result = new StatsInfo($account->getCharacters()->first());
            $this->send($connection, $result);

            $inventory = new InventoryListCount([]);
            $this->send($connection, $inventory);

            $connection->write(pack('c*', 0xc1, 0x06, 0x03, 0x00, 0xb6));

            //magic list
            $connection->write(pack('c*', 0xC1, 0x05, 0xF3, 0x11, 0x00));
            $connection->write(pack('c*', 0xC1, 0x04, 0x0f, 0x11));
            */

//            $connection->write(pack('c*', 0xc1, 0x2d, 0x0d, 0x01, 0x59, 0x6f, 0x75, 0x20, 0x61, 0x72, 0x65, 0x20, 0x63, 0x6f, 0x6e, 0x6e, 0x65, 0x63, 0x74, 0x69, 0x6e, 0x67, 0x20, 0x74, 0x6f, 0x20, 0x74, 0x68, 0x65, 0x20, 0x67, 0x61, 0x6d, 0x65, 0x20, 0x66, 0x6f, 0x72, 0x20, 0x46, 0x52, 0x45, 0x45, 0x2e, 0x00));
//            $connection->write(pack('c*', 0xc1, 0x21, 0x0d, 0x01, 0x50, 0x6f, 0x77, 0x65, 0x72, 0x65, 0x64, 0x20, 0x62, 0x79, 0x20, 0x77, 0x77, 0x77, 0x2e, 0x44, 0x61, 0x72, 0x6b, 0x73, 0x54, 0x65, 0x61, 0x6d, 0x2e, 0x4e, 0x45, 0x54, 0x00));
//            $connection->write(pack('c*', 0xc1, 0x07, 0x26, 0xfe, 0x00, 0x6e, 0x00));
//            $connection->write(pack('c*', 0xc1, 0x08, 0x27, 0xfe, 0x00, 0x14, 0x00, 0x19));
//            $connection->write(pack('c*', 0xc1, 0x05, 0x0b, 0x00, 0x03));
//            $connection->write(pack('c*', 0xc1, 0x06, 0x03, 0x00, 0xce, 0xc4));
//            $connection->write(pack('c*', 0xc3, 0x4f, 0x0e, 0x98, 0x06, 0x5e, 0x51, 0x72, 0xb3, 0x44, 0x4e, 0xc5, 0xf0, 0x1d, 0x22, 0x23, 0x00, 0xcc, 0xf1, 0x72, 0xfc, 0x1c, 0xa9, 0x9c, 0x1b, 0x19, 0x17, 0x37, 0xc6, 0x9c, 0xe7, 0x7d, 0x1a, 0xd6, 0xe3, 0x66, 0xaf, 0x2e, 0x94, 0xc4, 0x3e, 0xf5, 0x08, 0xac, 0xcd, 0xf8, 0xe1, 0xa9, 0x25, 0xc8, 0xd0, 0x6b, 0x43, 0x07, 0x71, 0xa5, 0x90, 0x44, 0x71, 0x3b, 0x6f, 0x48, 0x73, 0x23, 0x3f, 0x3d, 0xcc, 0xf9, 0xcb, 0x2a, 0x2e, 0xab, 0x5b, 0xdc, 0x02, 0xd4, 0x79, 0xb0, 0x8e));
//            $connection->write(pack('c*', 0xc4, 0x00, 0x19, 0xc8, 0x0c, 0x35, 0xee, 0x0e, 0x5e, 0xa6, 0xe9, 0x41, 0x1c, 0x29, 0xdc, 0xe1, 0x28, 0x06, 0xd0, 0x34, 0x42, 0x7a, 0x3e, 0x64, 0x58));
//            $connection->write(pack('c*', 0xc1, 0x05, 0xf3, 0x11, 0x00));
//            $connection->write(pack('c*', 0xc1, 0x13, 0xf3, 0x30, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x09, 0x00, 0x04, 0x08, 0xcf));
//            $connection->write(pack('c*', 0xc1, 0x21, 0x0d, 0x00, 0x50, 0x6f, 0x77, 0x65, 0x72, 0x65, 0x64, 0x20, 0x62, 0x79, 0x20, 0x77, 0x77, 0x77, 0x2e, 0x44, 0x61, 0x72, 0x6b, 0x73, 0x54, 0x65, 0x61, 0x6d, 0x2e, 0x4e, 0x45, 0x54, 0x00));

        } else if ($packet instanceof CreateCharacter) {
            /** @var Account $account */
            $account = $this->players->offsetGet($connection);

            try {
                $character = $this->characterRepository->createCharacter($account, $packet->getName(), 0);
                $result = new CharCreateResult(true, $character);
            } catch (\Exception $e) {
                echo "Failed to create character [{$connection->getRemoteAddress()}][{$account->getId()}]: " . get_class($e) . " {$e->getMessage()}\n";
                $result = new CharCreateResult(false, $packet->getName());
            }

            $this->send($connection, $result);
        } else if ($packet instanceof DeleteCharacter) {
            /** @var Account $account */
            $account = $this->players->offsetGet($connection);

            try {
                $character = $account->getCharacters()->filter(function (Character $character) use ($packet) {
                    return $character->getName() === $packet->getName();
                })->first();

                //verify passcode
                $this->accountRepository->authenticate($account->getLogin(), $packet->getPass());

                $this->characterRepository->deleteCharacter($character);

                $result = new CharDeleteResult(true);
            } catch (\Exception $e) {
                echo "Failed to delete character [{$connection->getRemoteAddress()}][{$account->getId()}]: " . get_class($e) . " {$e->getMessage()}\n";
                $result = new CharDeleteResult(false);
            }

            $this->send($connection, $result);

        } else if ($packet instanceof ClientClose) {
            echo "Disconnected [{$connection->getRemoteAddress()}]: client close flag '{$packet->getFlag()}'\n";
            $connection->close();
        }

//        $this->serviceLocator->get('orm_em')->clear();
    }

    public function createConnection($socket)
    {
        $connection = parent::createConnection($socket);

        $this->clients->attach($connection);

        $that = $this;

        $connection->on('close', function () use ($that, $connection) {
            $this->clients->detach($connection);
        });

        $connection->on('data', array($this, 'receive'));

        $this->send($connection, new JoinResult(true, '0.97.04'));
    }

    public function send(ConnectionInterface $connection, SCPacket $packet)
    {
        echo 'Sent ' . get_class($packet) . PHP_EOL;
        $connection->write($packet->buildPacket());
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}