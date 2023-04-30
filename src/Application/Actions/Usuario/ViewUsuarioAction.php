<?php
declare(strict_types=1);

namespace App\Application\Actions\Usuario;

use Psr\Http\Message\ResponseInterface as Response;

class ViewUsuarioAction extends UsuarioAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('IDUSUARIO');
        //$user = $this->usuarioRepository->findUserOfId($userId);
        $user = $this->usuarioRepository->getUserById($userId);
        $this->logger->info("Usuario of id `${userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
