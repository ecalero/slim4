<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

use App\Application\Actions\ActionPayload;
use App\Domain\Usuario\UsuarioRepository;
use App\Domain\Usuario\Usuario;
use DI\Container;
class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
