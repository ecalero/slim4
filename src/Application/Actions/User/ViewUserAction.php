<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('IDUSUARIO');
        //$user = $this->userRepository->findUserOfId($userId);
        $user = $this->userRepository->getUserById($userId);
        $this->logger->info("User of id `${userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
