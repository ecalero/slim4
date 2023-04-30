<?php
declare(strict_types=1);

namespace App\Domain\Componente;

interface ComponenteRepository
{


    /**
     * @return Componente[]
     */
    public function findAll(): array;

    /**
     * @param int $IDCOMPONENTE
     * @return Componente
     * @throws ComponenteNotFoundException
     */
    public function findUserOfId(int $id): Componente;

    /**
     * @param int $IDCOMPONENTE
     * @return array
     * @throws ComponenteNotFoundException
     */
    public function getUserById(int $IDCOMPONENTE): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDCOMPONENTE
     * @return array
     * @throws ComponenteNotFoundException
     */
    public function VerificarLogin(array $COMPONENTE): array;

    /**
     * @param int $IDCOMPONENTE
     * @return array
     * @throws ComponenteNotFoundException
     */
    public function crearToken(array $COMPONENTE): array;

    /**
     * @return array
     * @throws ComponenteNotFoundException
     */
    public function getColumnasComponente(): array;

    /**
     * @return array
     * @throws ComponenteNotFoundException
     */
     public function getFilasComponente(): array;

     /**
      * @return Componente
      * @throws ComponenteNotFoundException
      */
      public function nuevoComponente(Componente $componente): Componente;

}
