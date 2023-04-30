<?php
declare(strict_types=1);

namespace App\Domain\Login;

interface LoginRepository
{


    /**
     * @return Login[]
     */
    public function findAll(): array;

    /**
     * @param int $IDLogin
     * @return Login
     * @throws LoginNotFoundException
     */
    public function findUserOfId(int $id): Login;

    /**
     * @param int $IDLogin
     * @return array
     * @throws LoginNotFoundException
     */
    public function getUserById(int $IDLogin): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDLogin
     * @return array
     * @throws LoginNotFoundException
     */
    public function VerificarLogin(array $Login): array;

    /**
     * @param int $IDLogin
     * @return array
     * @throws LoginNotFoundException
     */
    public function crearToken(array $Login): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws LoginNotFoundException
     */
    public function verificaSesionAbierta(int $IDUSUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws LoginNotFoundException
     */
    public function loginSesion(int $IDUSUARIO): array;

    /**
     * @param int $IDUSUARIO
     * @return array
     * @throws LoginNotFoundException
     */
    public function loginDatosApp(int $IDUSUARIO): array;

    /**
     * @return array
     * @throws LoginNotFoundException
     */
    public function loginParametrosSesion(): array;

}
