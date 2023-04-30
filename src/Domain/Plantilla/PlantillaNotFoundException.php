<?php
declare(strict_types=1);

namespace App\Domain\Plantilla;

use App\Domain\DomainException\DomainRecordNotFoundException;

class PlantillaNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'El Plantilla requerido no existe.';
}
