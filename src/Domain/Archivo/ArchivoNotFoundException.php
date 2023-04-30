<?php
declare(strict_types=1);

namespace App\Domain\Archivo;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ArchivoNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Archivo you requested does not exist.';
}
