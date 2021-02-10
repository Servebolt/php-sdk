<?php

namespace Servebolt\Sdk\Endpoints\Cron;

use Servebolt\Sdk\Traits\ApiEndpoint;
use Servebolt\Sdk\Models\CronJob;
use Servebolt\Sdk\Helpers\Response;
use Servebolt\Sdk\Exceptions\ServeboltHttpClientException;

/**
 * Class Cron
 * @package Servebolt\SDK\Endpoints
 */
class Cron
{

    private string $modelBinding = CronJob::class;

    use ApiEndpoint;

    /**
     * @return Response
     * @throws ServeboltHttpClientException
     */
    public function list() : Response
    {
        try {
            $httpResponse = $this->httpClient->get('/cronjobs');
            return new Response($httpResponse->getData(), $this->modelBinding);
        } catch (\Exception $exception) {
            throw new ServeboltHttpClientException($exception->getMessage(), $exception->getCode());
        }
    }

    /*
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
    */
}
