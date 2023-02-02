<?php

namespace App\Services\Api;

class ApiService
{
    /**
     * @var string
     */
    private $apiBaseUrl;

    /**
     * @var string
     */
    private $token;

    /**
     * Return Rest API Base URL (e.g. https://www.somemagentourl.com/rest/V1 )
     * @return mixed|string
     */
    protected function getApiBaseUrl()
    {
        if (!$this->apiBaseUrl) {
            $this->apiBaseUrl = env('MAGENTO_API_BASE_URL');

        }
        return $this->apiBaseUrl;
    }

    /**
     * Return token used
     * @return mixed|string
     */
    protected function getToken()
    {
        if (!$this->token) {
            $this->token = env('MAGENTO_API_TOKEN');
        }
        return $this->token;
    }

    /**
     * @throws \Exception
     */
    protected function throwExceptionIfUrlOrTokenEmpty()
    {
        if (empty($this->getApiBaseUrl()) || empty($this->getToken())) {
            throw new \Exception('ERROR : API URL / token is empty.');
        }
    }
}