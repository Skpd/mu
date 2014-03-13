<?php

namespace MuServer\Game;

use Doctrine\ORM\EntityNotFoundException;
use MuServer\Entity\Account;
use MuServer\Protocol\Season097\ClientServer\CharListRequest;
use MuServer\Protocol\Season097\ClientServer\Factory;
use MuServer\Protocol\Season097\ClientServer\LoginRequest;
use MuServer\Protocol\Season097\ClientServer\Ping;
use MuServer\Protocol\Season097\ServerClient\AbstractPacket as SCPacket;
use MuServer\Protocol\Season097\ClientServer\AbstractPacket as CSPacket;
use MuServer\Protocol\Season097\ServerClient\CharList;
use MuServer\Protocol\Season097\ServerClient\CharListCount;
use MuServer\Protocol\Season097\ServerClient\CharListResult;
use MuServer\Protocol\Season097\ServerClient\JoinResult;
use MuServer\Protocol\Season097\ServerClient\LoginResult;
use MuServer\Repository\Account as AccountRepository;
use MuServer\Repository\InvalidPasswordException;
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

    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);

        $this->clients = new \SplObjectStorage();
        $this->players = new \SplObjectStorage();
    }

    public function init()
    {
        $this->accountRepository = $this->serviceLocator->get('orm_em')->getRepository('MuServer\Entity\Account');
    }

    public function receive($data, ConnectionInterface $connection)
    {
        try {
            $packet = Factory::buildPacket($data);
        } catch (\RuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }

        if ($packet instanceof Ping) {
            // count ticks
        } elseif ($packet instanceof LoginRequest) {
            $result = new LoginResult(LoginResult::SUCCESS);

            try {
                $account = $this->accountRepository->authenticate($packet->getLogin(), $packet->getPassword());

                $this->players->attach($connection, $account);
            } catch (EntityNotFoundException $e) {
                $result->setResult(LoginResult::INVALID_ACCOUNT);
            } catch (InvalidPasswordException $e) {
                $result->setResult(LoginResult::BAD_PASSWORD);
            }

            $this->send($connection, $result);
        } elseif ($packet instanceof CharListRequest) {
            /** @var Account $account */
            $account = $this->players->offsetGet($connection);

            $result = new CharListResult($this->clients->getHash($connection), $account->getId());
            $this->send($connection, $result);

            $chars = [];

            foreach ($account->getCharacters() as $character) {
                $char = new CharList();
                $char->setIndex($character->getIndex());
                $char->setCharClass($character->getClass());
                $char->setControlCode($character->getCode());
                $char->setLevel($character->getLevel());
                $char->setName($character->getName());
                $char->setSet(
                      chr(0xFF)   // right arm (weapon)
                    . chr(0xFF)   // left arm (weapon / shield)

                    . chr(0xFF)   // helm and armor type
                    . chr(0xFF)   // gloves and pants type
                    . chr(0xFF)   // boots and wings type

                    . chr(0x00)   // boots and gloves level
                    . chr(0x00)   // pants armor helm gloves level
                    . chr(0x00)   // helm level

                    . chr(0xF8)   // 2nd wings ?

                    . chr(0x00)   // is exc ? 1 << 1 | 1 << 2 ... 1 << 7
                );

                $chars[] = $char;
            }


            $result = new CharListCount($chars);
            $this->send($connection, $result);
        }
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

        $this->send($connection, new JoinResult());
    }

    public function send(ConnectionInterface $connection, SCPacket $packet)
    {
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