<?php

// Source from https://github.com/scoutapp/core-agent-api/blob/master/examples/php/HelloWorld.php

namespace Scout;

class Request
{
    private $_connection = NULL;
    private $_request_id = NULL;
    private $_span_id = NULL;

    public function __construct()
    {
        $this->_connection = new Connection();
    }

    public function startRequest() {
        if ( $this->_request_id != NULL ) {
            throw new \Exception('Previous request not complete, request_id: ' . $this->_request_id);
        }

        $this->_request_id = $this->generateRequestId();
        $this->_connection->send(['StartRequest' => ['request_id' => $this->_request_id]]);
    }

    public function startSpan($operation) {
        if ( $this->_request_id == NULL ) {
            throw new \Exception('No request_id found, did you start a request?');
        }

        if ( $this->_span_id != NULL ) {
            throw new \Exception('Previous span not complete, span_id: ' . $this->_span_id);
        }

        $this->_span_id = 'span-' . $this->uuid4();
        $this->_connection->send(['StartSpan' => ['request_id' => $this->_request_id, 'span_id' => $this->_span_id, 'operation' => $operation]]);
    }

    public function stopSpan() {
        if ( $this->_request_id == NULL ) {
            throw new \Exception('No request_id found, did you start a request?');
        }

        if ( $this->_span_id == NULL ) {
            throw new \Exception('No span_id found, did you start a span?');
        }

        $this->_connection->send(['StopSpan' => ['request_id' => $this->_request_id, 'span_id' => $this->_span_id]]);

        $this->_span_id = NULL;
    }

    public function finishRequest() {
        if ( $this->_request_id == NULL ) {
            throw new \Exception('No request_id found, did you start a request?');
        }

        $this->_connection->send(['FinishRequest' => ['request_id' => $this->_request_id]]);

        $this->_request_id = NULL;
    }

    # Original uuid4 source: http://www.php.net/manual/en/function.uniqid.php#94959
    function uuid4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    private function generateRequestId() {
        return 'req-' . $this->uuid4();
    }
}
