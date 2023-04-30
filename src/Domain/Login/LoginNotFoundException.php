<?php
declare(strict_types=1);

namespace App\Domain\Login;

use App\Domain\DomainException\DomainRecordNotFoundException;

class LoginNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Usuario you requested does not exist.';
}
