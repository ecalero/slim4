<?php
declare(strict_types=1);

namespace App\Application\Actions\Usuario;

use App\Application\Actions\Action;
use App\Domain\Usuario\UsuarioRepository;
use App\Domain\Login\LoginRepository;
use Psr\Log\LoggerInterface;

abstract class UsuarioAction extends Action
{
    /**
     * @var UsuarioRepository
     * @var LoginRepository
     */
    protected $usuarioRepository;
    protected $loginRepository;

    /**
     *
     *
     * @param LoggerInterface $logger
     * @param UsuarioRepository $usuarioRepository
     * @param LoginRepository $loginRepository
     */
    public function __construct(LoggerInterface $logger,
                                UsuarioRepository $usuarioRepository,
                                LoginRepository $loginRepository
    ) {

        parent::__construct($logger);
        $this->usuarioRepository = $usuarioRepository;
        $this->loginRepository = $loginRepository;
    }
}
