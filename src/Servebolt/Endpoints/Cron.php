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
     * @param array $data
     * @return Response|object
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function create($data)
    {
        $httpResponse = $this->httpClient->postJson('/cronjobs', compact('data'));
        return $this->response($httpResponse);
    }

    public function get($id)
    {
        $httpResponse = $this->httpClient->get('/cronjobs/' . $id);
        return $this->response($httpResponse);
    }

    public function delete($id)
    {
        $httpResponse = $this->httpClient->delete('/cronjobs/' . $id);
        return $this->response($httpResponse);
    }

    public function update($id, $data)
    {
        $httpResponse = $this->httpClient->patchJson('/cronjobs/' . $id, compact('data'));
        return $this->response($httpResponse);
    }
}
