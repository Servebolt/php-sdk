<?php

namespace Servebolt\Sdk\Endpoints;

use Servebolt\Sdk\Traits\ApiEndpoint;
use Servebolt\Sdk\Models\CronJob;

/**
 * Class Cron
 * @package Servebolt\SDK\Endpoints
 */
class Cron extends Endpoint
{

    protected string $modelBinding = CronJob::class;

    use ApiEndpoint;

    /**
     * @return Response|object
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function list()
    {
        $httpResponse = $this->httpClient->get('/cronjobs');
        return $this->response($httpResponse);
    }

    /**
     * @param $cronJob
     * @return Response|object
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function create($cronJob)
    {
        $cronJob = CronJob::factory($cronJob);
        $httpResponse = $this->httpClient->post('/cronjobs', $cronJob->toSnakeCase());
        return $this->response($httpResponse);
    }

    /*
    public function get($id)
    {
    }

    public function delete($id)
    {
    }

    public function update($cronJob)
    {
        $cronJob = CronJob::factory($cronJob);
    }

    public function replace(CronJob $cronJob)
    {
        $cronJob = CronJob::factory($cronJob);
    }
    */
}
