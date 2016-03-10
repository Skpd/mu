<?php

namespace MuServer\Game;

use Doctrine\ORM\EntityNotFoundException;
use MuServer\Entity\Account;
use MuServer\Protocol\Debug;
use MuServer\Protocol\Season097\ClientServer\CharListRequest;
use MuServer\Protocol\Season097\ClientServer\ClientClose;
use MuServer\Protocol\Season097\ClientServer\CreateCharacter;
use MuServer\Protocol\Season097\ClientServer\Factory;
use MuServer\Protocol\Season097\ClientServer\LoginRequest;
use MuServer\Protocol\Season097\ClientServer\MapJoinRequest;
use MuServer\Protocol\Season097\ClientServer\Ping;
use MuServer\Protocol\Season097\ServerClient\AbstractPacket as SCPacket;
use MuServer\Protocol\Season097\ClientServer\AbstractPacket as CSPacket;
use MuServer\Protocol\Season097\ServerClient\CharInfoResult;
use MuServer\Protocol\Season097\ServerClient\CharList;
use MuServer\Protocol\Season097\ServerClient\CharListCount;
use MuServer\Protocol\Season097\ServerClient\CharListResult;
use MuServer\Protocol\Season097\ServerClient\JoinPosition;
use MuServer\Protocol\Season097\ServerClient\JoinResult;
use MuServer\Protocol\Season097\ServerClient\LoginResult;
use MuServer\Repository\Account as AccountRepository;
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
        } elseif ($packet instanceof LoginRequest) {
            $result = new LoginResult(LoginResult::SUCCESS);

            try {
                $account = $this->accountRepository->authenticate($packet->getLogin(), $packet->getPassword());

                $this->players->attach($connection, $account);
                $this->send($connection, $result);
            } catch (EntityNotFoundException $e) {
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
            //TODO: unpack name and select right character
            /** @var Account $account */
            $account = $this->players->offsetGet($connection);

            var_dump(Security::decodeName($packet->getName()));

            $result = new CharInfoResult(1, $packet->getName(), 1);
            $this->send($connection, $result);

            $result = new JoinPosition($account->getCharacters()->first(), $this->clients->getHash($connection));
            $this->send($connection, $result);
        } else if ($packet instanceof CreateCharacter) {
            /** @var Account $account */
            $account = $this->players->offsetGet($connection);

            $character = $this->characterRepository->createCharacter($account, $packet->getName(), 0);

            $result = new CharInfoResult(1, $character->getName(), $character->getIndex());
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

        $this->send($connection, new JoinResult());
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