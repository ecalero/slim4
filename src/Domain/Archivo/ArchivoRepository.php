<?php
declare(strict_types=1);

namespace App\Domain\Archivo;

interface ArchivoRepository
{


    /**
     * @return Archivo[]
     */
    public function findAll(): array;

    /**
     * @param int $IDUSUARIO
     * @return Archivo
     * @throws ArchivoNotFoundException
     */
    public function findUserOfId(int $id): Archivo;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws ArchivoNotFoundException
     */
    public function getUserById(int $IDUSUARIO): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws ArchivoNotFoundException
     */
    public function VerificarLogin(array $USUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws ArchivoNotFoundException
     */
    public function crearToken(array $USUARIO): array;

    /**
     * @return array
     * @throws ArchivoNotFoundException
     */
     public function nuevoArchivo(Archivo $archivo): array;

}
