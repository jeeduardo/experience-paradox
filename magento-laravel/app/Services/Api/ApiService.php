<?php

namespace App\Services\Api;

class ApiService
{
    private $apiBaseUrl;

    private $token;

    protected function getApiBaseUrl()
    {
        if (!$this->apiBaseUrl) {
            $this->apiBaseUrl = env('MAGENTO_API_BASE_URL');

        }
        return $this->apiBaseUrl;
    }

    protected function getToken()
    {
        if (!$this->token) {
            $this->token = env('MAGENTO_API_TOKEN');
        }
        return $this->token;
    }

    protected function throwExceptionIfUrlOrTokenEmpty()
    {
        if (empty($this->getApiBaseUrl()) || empty($this->getToken())) {
            throw new \Exception('ERROR : API URL / token is empty.');
        }
    }
}