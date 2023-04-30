<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;


use Illuminate\Database\Connection;


class InMemoryUserRepository implements UserRepository
{

        /**
     * @var Connection
     */
    private $connection;

    /**
     * The constructor.
     *
     * @param Connection $connection The database connection
     */

    /**
     * @var User[]
     */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct(array $users = null, Connection $connection)
    {
        $this->connection = $connection;
        /* $this->users = $users ?? [
            1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
        ]; */
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * {@inheritdoc}
     */

    public function getUserById(int $userId): array
    {
        $row = $this->connection->table('USUARIO')->find($userId);

        if(!$row) {
            throw new \DomainException(sprintf('User not found: %s', $userId));
        }       

        return $row;
    }



}
