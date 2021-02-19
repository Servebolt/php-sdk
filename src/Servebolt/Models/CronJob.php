<?php

namespace Servebolt\Sdk\Models;

use Servebolt\Sdk\Traits\ModelFactoryTrait;

class CronJob extends Model
{

    use ModelFactoryTrait;

    protected array $properties = [
        'id',
        'environmentId',
        'schedule',
        'command',
        'comment',
        'enabled',
        'notifications',
    ];

    protected array $requiredPropertiesOnCreation = [
        'environmentId',
        'schedule',
        'command',
    ];

    protected array $casts = [
        'enabled' => 'boolean',
    ];
}
