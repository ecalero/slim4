<?php
declare(strict_types=1);

namespace App\Application\Actions\Archivo;

use App\Application\Actions\Action;
use App\Domain\Archivo\ArchivoRepository;
use Odan\Session\SessionInterface;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ResponseInterface as Response;

abstract class ArchivoAction extends Action
{
    /**
     * @var ArchivoRepository
     */
    protected $archivoRepository;

    /**
     * @param LoggerInterface $logger
     * @param ArchivoRepository $archivoRepository
     * @param SessionInterface $session
     */

    public function __construct(LoggerInterface $logger,
            ArchivoRepository $archivoRepository,
            SessionInterface $session,
    ) {

        parent::__construct($logger);
        $this->archivoRepository = $archivoRepository;
        $this->session = $session;
    }

}
