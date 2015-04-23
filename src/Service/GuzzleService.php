<?php

namespace Mdb\PayPal\Ipn\Service;

use GuzzleHttp\ClientInterface;
use Mdb\PayPal\Ipn\Exception\ServiceException;
use Mdb\PayPal\Ipn\Message;
use Mdb\PayPal\Ipn\Service;
use Mdb\PayPal\Ipn\ServiceResponse;

class GuzzleService implements Service
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $serviceEndpoint;

    /**
     * @param ClientInterface $httpClient
     * @param string          $serviceEndpoint
     */
    public function __construct(ClientInterface $httpClient, $serviceEndpoint)
    {
        $this->httpClient = $httpClient;
        $this->serviceEndpoint = $serviceEndpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function verifyIpnMessage(Message $message)
    {
        $requestBody = 'cmd=_notify-validate&'.(string) $message;

        try {
            $response = $this->httpClient->post(
                $this->serviceEndpoint,
                array('body' => $requestBody)
            );
        } catch (\Exception $e) {
            throw new ServiceException($e->getMessage());
        }

        return new ServiceResponse(
            (string) $response->getBody()
        );
    }
}
