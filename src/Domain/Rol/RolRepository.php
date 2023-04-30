<?php
declare(strict_types=1);

namespace App\Domain\Rol;

interface RolRepository
{


    /**
     * @return Rol[]
     */
    public function findAll(): array;

    /**
     * @param int $IDUSUARIO
     * @return Rol
     * @throws RolNotFoundException
     */
    public function findUserOfId(int $id): Rol;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws RolNotFoundException
     */
    public function getUserById(int $IDUSUARIO): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws RolNotFoundException
     */
    public function VerificarLogin(array $USUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws RolNotFoundException
     */
    public function crearToken(array $USUARIO): array;

}
