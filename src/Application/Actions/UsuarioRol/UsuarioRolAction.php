<?php
declare(strict_types=1);

namespace App\Application\Actions\UsuarioRol;

use App\Application\Actions\Action;
use App\Domain\UsuarioRol\UsuarioRolRepository;
use Odan\Session\SessionInterface;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ResponseInterface as Response;

abstract class UsuarioRolAction extends Action
{
    /**
     * @var UsuarioRolRepository
     */
    protected $usuariorolRepository;

    /**
     * @param LoggerInterface $logger
     * @param UsuarioRolRepository $usuariorolRepository
     */
    public function __construct(LoggerInterface $logger,
            UsuarioRolRepository $usuariorolRepository,
            SessionInterface $session
    ) {

        parent::__construct($logger);
        $this->usuariorolRepository = $usuariorolRepository;
        $this->session = $session;
    }

}
