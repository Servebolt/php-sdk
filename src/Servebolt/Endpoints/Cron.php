<?php

namespace Servebolt\Sdk\Endpoints;

use Servebolt\Sdk\Traits\ApiEndpoint;
use Servebolt\Sdk\Models\CronJob;
use Servebolt\Sdk\Response;

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
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function list() : Response
    {
        $httpResponse = $this->httpClient->get('/cronjobs');
        return new Response($httpResponse->getDecodedBody(), $this->modelBinding);
    }

    /**
     * @param $cronJob
     * @return Response
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function create($cronJob)
    {
        $cronJob = CronJob::factory($cronJob);
        $httpResponse = $this->httpClient->post('/cronjobs', $cronJob->toSnakeCase());
        return new Response($httpResponse->getDecodedBody(), $this->modelBinding);
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
