<?php

namespace Servebolt\Sdk\Models;

use Servebolt\Sdk\Endpoints\Cron;
use Servebolt\Sdk\Traits\ModelFactoryTrait;

class CronJob extends Model
{

    use ModelFactoryTrait;

    protected static $endpointBinding = Cron::class;

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
