<?php
declare(strict_types=1);

namespace App\Domain\Sistema;

interface SistemaRepository
{
    

    /**
     * @return Sistema[]
     */
    public function findAll(): array;

    /**
     * @param int $IDSISTEMA
     * @return Sistema
     * @throws SistemaNotFoundException
     */
    public function findUserOfId(int $id): Sistema;

    /**
     * @param int $IDSISTEMA
     * @return array
     * @throws SistemaNotFoundException
     */
    public function getUserById(int $IDSISTEMA): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDSISTEMA
     * @return array
     * @throws SistemaNotFoundException
     */
    public function VerificarLogin(array $SISTEMA): array;

    /**
     * @param int $IDSISTEMA
     * @return array
     * @throws SistemaNotFoundException
     */
    public function crearToken(array $SISTEMA): array;

    /**
     * @return array
     * @throws SistemaNotFoundException
     */
    public function getColumnasSistema(): array;

    /**
     * @return array
     * @throws SistemaNotFoundException
     */
     public function getFilasSistema(): array; 

}
