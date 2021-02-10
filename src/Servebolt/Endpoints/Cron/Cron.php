<?php

namespace Servebolt\SDK\Endpoints\Cron;

use Servebolt\SDK\Traits\ApiEndpoint;
use Servebolt\SDK\Models\CronJob;
use Servebolt\SDK\Helpers\Response;

/**
 * Class Cron
 * @package Servebolt\SDK\Endpoints
 */
class Cron
{

    use ApiEndpoint;

    public function list()
    {
        $httpResponse = $this->httpClient->get('/cronjobs');
        return new Response($httpResponse, CronJob::class);
    }

    public function create(CronJob $cronJob)
    {
    }

    public function get($id)
    {
    }

    public function delete($id)
    {
    }

    public function update(CronJob $cronJob)
    {
    }

    public function replace(CronJob $cronJob)
    {
    }
}
