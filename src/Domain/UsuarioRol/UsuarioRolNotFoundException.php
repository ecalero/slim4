<?php
declare(strict_types=1);

namespace App\Domain\UsuarioRol;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UsuarioRolNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Usuario Rol you requested does not exist.';
}
