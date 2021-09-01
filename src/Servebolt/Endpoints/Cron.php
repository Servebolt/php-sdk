<?php

namespace Servebolt\Sdk\Endpoints;

use Servebolt\Sdk\Traits\ApiEndpoint;

/**
 * Class Cron
 * @package Servebolt\SDK\Endpoints
 */
class Cron extends AbstractEndpoint
{

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
    /*
    public function create($cronJob)
    {
        $cronJob = CronJob::factory($cronJob);
        $httpResponse = $this->httpClient->postJson('/cronjobs', $cronJob->toArray());
        return $this->response($httpResponse);
    }
    */

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
