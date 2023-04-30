<?php
declare(strict_types=1);

namespace App\Domain\Usuario;

interface UsuarioRepository
{


    /**
     * @return Usuario[]
     */
    public function findAll(): array;

    /**
     * @param int $IDUSUARIO
     * @return Usuario
     * @throws UsuarioNotFoundException
     */
    public function findUserOfId(int $id): Usuario;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function getUserById(int $IDUSUARIO): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function VerificarLogin(array $USUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function crearToken(array $USUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function getMenuAccesos(String $token): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function getUsuarioById(int $IDUSUARIO): array;

    /**
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function getColumnasUsuario(): array;

    /**
     * @return array
     * @throws UsuarioNotFoundException
     */
     public function getFilasUsuario(): array;

         /**
     * @param array $datos
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function actualizarUsuario(array $datos): array;

    /**
     * @param array $datos
     * @return array
     * @throws UsuarioNotFoundException
     */
    public function resetearClave(array $datos): array;



}
