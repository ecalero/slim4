<?php
declare(strict_types=1);

namespace App\Domain\UsuarioRol;

interface UsuarioRolRepository
{


    /**
     * @return UsuarioRol[]
     */
    public function findAll(): array;

    /**
     * @param int $IDUSUARIO
     * @return UsuarioRol
     * @throws UsuarioRolNotFoundException
     */
    public function findUserOfId(int $id): UsuarioRol;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioRolNotFoundException
     */
    public function getUserById(int $IDUSUARIO): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioRolNotFoundException
     */
    public function VerificarLogin(array $USUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioRolNotFoundException
     */
    public function crearToken(array $USUARIO): array;


        /**
     * @return array
     * @throws UsuarioRolNotFoundException
     */
    public function getColumnasUsuarioRol(): array;


     /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioRolNotFoundException
     */
     public function getFilasUsuarioRol(int $IDUSUARIO): array;

     /**
      * @return UsuarioRol
      * @throws UsuarioRolNotFoundException
      */
      public function nuevoUsuarioRol(UsuarioRol $usuariorol): UsuarioRol;

      /**
     * @param UsuarioRol $usuariorol
     * @return array
     * @throws UsuarioRolNotFoundException
     */
     public function eliminaUsuarioRolbyIdRol(UsuarioRol $usuariorol): array;


}
