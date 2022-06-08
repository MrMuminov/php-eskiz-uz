<?php

namespace mrmuminov\eskizuz\request\sms;

use mrmuminov\eskizuz\client\ClientInterface;
use mrmuminov\eskizuz\request\AbstractRequest;
use mrmuminov\eskizuz\response\sms\SmsGetUserMessagesByDispatchResponse;

/**
 * Class SmsGetUserMessagesByDispatchRequest
 *
 * @property SmsGetUserMessagesByDispatchResponse $response
 */
class SmsGetUserMessagesByDispatchRequest extends AbstractRequest
{
    public $client;
    public $type;
    public $headers;
    public $action = '/message/sms/get-user-messages-by-dispatch';
    public $responseClass = SmsGetUserMessagesByDispatchResponse::class;

    public $errorMessages = [
        'first_page' => "This is a first page",
        'last_page' => "This is a last page",
    ];

    public function __construct(ClientInterface $client, array $type, array $headers = [])
    {
        $this->client = $client;
        $this->type = $type;
        $this->headers = $headers;
        $request = $client->post($this->action, $this->type, $this->headers);
        $this->setResponse(new $this->responseClass($request));
    }

    public function fetchFirstPage()
    {
        $request = $this->client->post($this->action . '?page=1', $this->type, $this->headers);
        $this->setResponse(new $this->responseClass($request));
    }

    public function fetchLastPage()
    {
        $request = $this->client->post($this->action . '?page=' . $this->response->last_page, $this->type, $this->headers);
        $this->setResponse(new $this->responseClass($request));
    }

    public function fetchNextPage()
    {
        if ($this->response->current_page < $this->response->total) {
            $next = $this->response->current_page + 1;
            $request = $this->client->post($this->action . '?page=' . $next, $this->type, $this->headers);
            $this->setResponse(new $this->responseClass($request));
        } else {
            $response = $this->response;
            $response->clear($this->errorMessages['last_page']);
            $this->setResponse($response);
        }
    }

    public function fetchPrevPage()
    {
        if ($this->response->current_page > 1) {
            $prev = $this->response->current_page - 1;
            $request = $this->client->post($this->action . '?page=' . $prev, $this->type, $this->headers);
            $this->setResponse(new $this->responseClass($request));
        } else {
            $response = $this->response;
            $response->clear($this->errorMessages['first_page']);
            $this->setResponse($response);
        }
    }

}