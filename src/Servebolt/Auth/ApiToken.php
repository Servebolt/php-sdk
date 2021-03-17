<?php

namespace Servebolt\Sdk\Auth;

class ApiToken implements ApiAuth
{

    private $apiToken;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function getAuthHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiToken,
        ];
    }
}
