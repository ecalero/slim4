<?php
declare(strict_types=1);

namespace App\Domain\Sistema;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SistemaNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'El Sistema requerido no existe.';
}
