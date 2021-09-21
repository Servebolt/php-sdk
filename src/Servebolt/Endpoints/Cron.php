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
     * @var string
     */
    protected $endpoint = 'cronjobs';

    /**
     * @return Response|object
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function list()
    {
        $httpResponse = $this->httpClient->get('/' . $this->endpoint);
        return $this->response($httpResponse);
    }

    /**
     * @param array $data
     * @return Response|object
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function create($data)
    {
        $data = $this->appendCommonRequestData($data);
        $httpResponse = $this->httpClient->postJson('/' . $this->endpoint, compact('data'));
        return $this->response($httpResponse);
    }

    public function get($id)
    {
        $httpResponse = $this->httpClient->get('/' . $this->endpoint . '/' . $id);
        return $this->response($httpResponse);
    }

    public function delete($id)
    {
        $httpResponse = $this->httpClient->delete('/' . $this->endpoint . '/' . $id);
        return $this->response($httpResponse);
    }

    public function update($id, $data)
    {
        $data = $this->appendCommonRequestData($data);
        $httpResponse = $this->httpClient->patchJson('/' . $this->endpoint . '/' . $id, compact('data'));
        return $this->response($httpResponse);
    }
}
