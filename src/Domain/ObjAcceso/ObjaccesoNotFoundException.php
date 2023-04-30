<?php
declare(strict_types=1);

namespace App\Domain\Usuario;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UsuarioNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Usuario you requested does not exist.';
}
