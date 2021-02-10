<?php

namespace Servebolt\SDK\Models;

class CronJob extends Model
{

    protected $properties = ['id', 'environmentId', 'schedule', 'command', 'comment', 'enabled', 'notifications'];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
