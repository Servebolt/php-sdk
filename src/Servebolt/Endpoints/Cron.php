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
     * @var string The endpoint URI name.
     */
    protected $endpoint = 'cronjobs';

    /**
     * List all cron jobs.
     *
     * @return Response|object
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function list()
    {
        $httpResponse = $this->httpClient->get('/' . $this->endpoint);
        return $this->response($httpResponse);
    }

    /**
     * Create cron job.
     *
     * @param array $data
     * @param int|null $environmentId
     * @return Response|object
     * @throws \Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function create($data, $environmentId = null)
    {
        $data = $this->appendCommonRequestData($data);
        $data = $this->appendEnvironmentRelation($data, $environmentId);
        $httpResponse = $this->httpClient->postJson('/' . $this->endpoint, compact('data'));
        return $this->response($httpResponse);
    }

    /**
     * Get cron job by ID.
     *
     * @param int $id
     * @return \GuzzleHttp\Psr7\Response|object|\Servebolt\Sdk\Response
     */
    public function get($id)
    {
        $httpResponse = $this->httpClient->get('/' . $this->endpoint . '/' . $id);
        return $this->response($httpResponse);
    }

    /**
     * Delete cron job by ID.
     *
     * @param int $id
     * @return \GuzzleHttp\Psr7\Response|object|\Servebolt\Sdk\Response
     */
    public function delete($id)
    {
        $httpResponse = $this->httpClient->delete('/' . $this->endpoint . '/' . $id);
        return $this->response($httpResponse);
    }

    /**
     * Update the cron job by ID.
     *
     * @param int $id
     * @param array $data
     * @return \GuzzleHttp\Psr7\Response|object|\Servebolt\Sdk\Response
     */
    public function update($id, $data)
    {
        $data = $this->appendCommonRequestData($data);
        $httpResponse = $this->httpClient->patchJson('/' . $this->endpoint . '/' . $id, compact('data'));
        return $this->response($httpResponse);
    }

    /**
     * Add environment relation to request data.
     *
     * @param array $data
     * @param int|null $environmentId
     * @return array
     */
    private function appendEnvironmentRelation($data, $environmentId)
    {
        if ($environmentId) {
            if (!array_key_exists('relationships', $data)) {
                $data['relationships'] = [
                    'environment' => [
                        'data' => [
                            'type' => 'environments',
                            'id' => $environmentId,
                        ]
                    ]
                ];
            }
            if (!array_key_exists('links', $data)) {
                $data['links'] = [
                    'related' => $this->config->get('baseUri') . 'environments/' . $environmentId,
                    'data' => [
                        'type' => 'environments',
                        'id' => $environmentId,
                    ]
                ];
            }
        }
        return $data;
    }
}
