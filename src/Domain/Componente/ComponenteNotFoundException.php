<?php
declare(strict_types=1);

namespace App\Domain\Componente;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ComponenteNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'El Componente requerido no existe.';
}
