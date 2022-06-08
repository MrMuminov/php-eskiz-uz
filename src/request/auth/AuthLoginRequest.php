<?php

namespace mrmuminov\eskizuz\request\auth;

use mrmuminov\eskizuz\client\ClientInterface;
use mrmuminov\eskizuz\request\AbstractRequest;
use mrmuminov\eskizuz\response\auth\AuthLoginResponse;

/**
 * Class AuthLoginClient
 *
 * @property AuthLoginResponse $response
 */
class AuthLoginRequest extends AbstractRequest
{
    public $action = '/auth/login';
    public $responseClass = AuthLoginResponse::class;

    public function __construct(ClientInterface $client, array $type, array $headers = [])
    {
        $request = $client->post($this->action, $type, $headers);
        $this->setResponse(new $this->responseClass($request));
    }

}
