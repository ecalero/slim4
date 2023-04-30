<?php
declare(strict_types=1);

namespace App\Domain\TabTablas;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TabTablasNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The TabTablas you requested does not exist.';
}
