<?php

namespace Servebolt\Sdk\Models;

use Servebolt\Sdk\Traits\ModelFactoryTrait;

class CronJob extends Model
{

    use ModelFactoryTrait;

    protected $properties = [
        'id',
        'environmentId',
        'schedule',
        'command',
        'comment',
        'enabled',
        'notifications',
    ];

    protected $requiredPropertiesOnCreation = [
        'environmentId',
        'schedule',
        'command',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
