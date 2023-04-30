<?php
declare(strict_types=1);

namespace App\Domain\Modulos;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ModulosNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Usuario you requested does not exist.';
}
