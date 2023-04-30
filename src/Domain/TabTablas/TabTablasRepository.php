<?php
declare(strict_types=1);

namespace App\Domain\TabTablas;

interface TabTablasRepository
{


    /**
     * @return TabTablas[]
     */
    public function findAll(): array;

    /**
     * @param int $IDUSUARIO
     * @return TabTablas
     * @throws TabTablasNotFoundException
     */
    public function findUserOfId(int $id): TabTablas;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws TabTablasNotFoundException
     */
    public function getUserById(int $IDUSUARIO): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws TabTablasNotFoundException
     */
    public function VerificarLogin(array $USUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws TabTablasNotFoundException
     */
    public function crearToken(array $USUARIO): array;

}
