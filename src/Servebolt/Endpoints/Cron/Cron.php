<?php

namespace Servebolt\Sdk\Endpoints\Cron;

use Servebolt\Sdk\Endpoints\Endpoint;
use Servebolt\Sdk\Traits\ApiEndpoint;
use Servebolt\Sdk\Models\CronJob;
use Servebolt\Sdk\Helpers\Response;

/**
 * Class Cron
 * @package Servebolt\SDK\Endpoints
 */
class Cron extends Endpoint
{

    private string $modelBinding = CronJob::class;

    use ApiEndpoint;

    /**
     * @return Response
     */
    public function list() : Response
    {
        $httpResponse = $this->httpClient->get('/cronjobs');
        return new Response($httpResponse->getData(), $this->modelBinding);
    }

    public function create($cronJob)
    {
        $cronJob = CronJob::factory($cronJob);
        $httpResponse = $this->httpClient->post('/cronjobs', $cronJob->toSnakeCase());
        return new Response($httpResponse->getData(), $this->modelBinding);
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
