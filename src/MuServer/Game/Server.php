<?php

namespace MuServer\Game;

use MuServer\Entity\Account;
use MuServer\Entity\Character;
use MuServer\Protocol\Debug;
use MuServer\Protocol\Season097\ClientServer\AddPoint;
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
use MuServer\Protocol\Season097\ServerClient\AddPointResult;
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
    private $accounts;
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
        $this->accounts = new \SplObjectStorage();
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
        } elseif ($packet instanceof CheckSum) {
            //check smth?
        } elseif ($packet instanceof LoginRequest) {
            $result = new LoginResult(LoginResult::SUCCESS);

            try {
                $account = $this->accountRepository->authenticate($packet->getLogin(), $packet->getPassword());

                $this->accounts->attach($connection, $account);
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
            $account = $this->accounts->offsetGet($connection);

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

            $account = $this->accounts->offsetGet($connection);

            $character = $account->getCharacters()->first();

            $result = new StatsInfo($character);
            $this->send($connection, $result);

            $this->players->attach($connection, $character);
            /*
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
        } else if ($packet instanceof CreateCharacter) {
            /** @var Account $account */
            $account = $this->accounts->offsetGet($connection);

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
            $account = $this->accounts->offsetGet($connection);

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
        } else if ($packet instanceof AddPoint) {
            /** @var Character $character */
            $character = $this->players->offsetGet($connection);

            $success = true;
            $fillUpdate = 0;

            switch ($packet->getWhich()) {
                case AddPoint::STRENGTH:
                    $character->setStrength($character->getStrength() + 1);
                    break;
                case AddPoint::AGILITY:
                    $character->setAgility($character->getAgility() + 1);
                    break;
                case AddPoint::VITALITY:
                    $character->setVitality($character->getVitality() + 1);
                    $character->setMaxLife($character->getMaxLife() + 10);
                    $fillUpdate = $character->getMaxLife();
                    break;
                case AddPoint::ENERGY:
                    $character->setEnergy($character->getEnergy() + 1);
                    $character->setMaxMana($character->getMaxMana() + 10);
                    $fillUpdate = $character->getMaxMana();
                    break;
                default:
                    $success = false;
                    echo "Unexpected value [{$connection->getRemoteAddress()}]: invalid stat '{$packet->getWhich()}'\n";
                    break;
            }

            $result = new AddPointResult($success, $packet->getWhich(), $fillUpdate);
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