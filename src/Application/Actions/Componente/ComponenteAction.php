<?php
declare(strict_types=1);

namespace App\Application\Actions\Componente;

use App\Application\Actions\Action;
use App\Domain\Componente\ComponenteRepository;
use Odan\Session\SessionInterface;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ResponseInterface as Response;

abstract class ComponenteAction extends Action
{
    /**
     * @var ComponenteRepository
     */
    protected $componenteRepository;

    /**
     * @param LoggerInterface $logger
     * @param ComponenteRepository $componenteRepository
     */
    public function __construct(LoggerInterface $logger,
            ComponenteRepository $componenteRepository,
            SessionInterface $session
    ) {

        parent::__construct($logger);
        $this->componenteRepository = $componenteRepository;
        $this->session = $session;
    }

}
