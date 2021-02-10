<?php

namespace Servebolt\Sdk\Endpoints\Cron;

use Servebolt\Sdk\Traits\ApiEndpoint;
use Servebolt\Sdk\Models\CronJob;
use Servebolt\Sdk\Helpers\Response;

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
