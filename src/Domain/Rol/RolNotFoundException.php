<?php
declare(strict_types=1);

namespace App\Domain\Rol;

use App\Domain\DomainException\DomainRecordNotFoundException;

class RolNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Rol you requested does not exist.';
}
