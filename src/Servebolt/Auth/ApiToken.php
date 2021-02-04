<?php

namespace Servebolt\SDK\Auth;

class ApiToken implements ApiAuth
{

    private string $apiToken;

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
