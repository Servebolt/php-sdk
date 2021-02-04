<?php

namespace Servebolt\SDK\Models;

use Servebolt\SDK\Traits\Model;

class CronJob
{

    use Model;

    private $properties = ['id', 'environmentId', 'schedule', 'command', 'comment', 'enabled', 'notifications'];

    private $casts = [
        'enabled' => 'boolean',
    ];
}
