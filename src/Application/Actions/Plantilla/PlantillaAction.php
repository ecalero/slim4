<?php
declare(strict_types=1);

namespace App\Application\Actions\Plantilla;

use App\Application\Actions\Action;
use App\Domain\Plantilla\PlantillaRepository;
use Odan\Session\SessionInterface;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ResponseInterface as Response;

abstract class PlantillaAction extends Action
{
    /**
     * @var PlantillaRepository
     */
    protected $plantillaRepository;

    /**
     * @param LoggerInterface $logger
     * @param PlantillaRepository $plantillaRepository
     */
    public function __construct(LoggerInterface $logger,
            PlantillaRepository $plantillaRepository,
            SessionInterface $session
    ) {

        parent::__construct($logger);
        $this->plantillaRepository = $plantillaRepository;
        $this->session = $session;
    }

}
