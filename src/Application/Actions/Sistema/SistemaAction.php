<?php
declare(strict_types=1);

namespace App\Application\Actions\Sistema;

use App\Application\Actions\Action;
use App\Domain\Sistema\SistemaRepository;
use Odan\Session\SessionInterface;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ResponseInterface as Response;

abstract class SistemaAction extends Action
{
    /**
     * @var SistemaRepository
     */
    protected $sistemaRepository;

    /**
     * @param LoggerInterface $logger
     * @param SistemaRepository $sistemaRepository
     */
    public function __construct(LoggerInterface $logger,
            SistemaRepository $sistemaRepository,
            SessionInterface $session
    ) {

        parent::__construct($logger);
        $this->sistemaRepository = $sistemaRepository;
        $this->session = $session;
    }

}
