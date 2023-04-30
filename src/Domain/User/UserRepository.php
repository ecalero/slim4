<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    

    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $IDUSUARIO
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UserNotFoundException
     */
    public function getUserById(int $IDUSUARIO): array;

}
