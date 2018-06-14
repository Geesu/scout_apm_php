<?php

// Source from https://github.com/scoutapp/core-agent-api/blob/master/examples/php/HelloWorld.php

namespace Scout;

class Connection
{
    private $_sock = NULL;

    public function __construct()
    {
        $this->connect();
        $this->register();
    }

    private function disconnect() {
        if ( $this->sock != NULL ) {
            socket_close($this->sock);
            $this->sock = NULL;
        }
    }

    private function connect() {
        if ( $this->_sock == NULL ) {
            $this->_sock = socket_create(AF_UNIX, SOCK_STREAM, 0);
            socket_connect($this->_sock, '/tmp/core-agent.sock');
            socket_set_nonblock($this->_sock);
        }
    }

    private function register() {
        $this->send(['Register' => ['app' => getenv('SCOUT_NAME'), 'key' => getenv('SCOUT_KEY'), 'api_version' => '1.0']]);
    }

    function send($arr, $retry = false) {
        try {
            $message = json_encode($arr);
            $size = strlen($message);
            socket_send($this->_sock, pack('N', $size), 4, 0);
            socket_send($this->_sock, $message, $size, 0);
        }
        catch (Exception $e) {
            error_log("Failure when sending data: $e->getMessage()");
            error_log(print_r($arr, true));

            if ( $retry ) {
                error_log("Failed after 1 retry, not sending again");
                return;
            }

            $this->disconnect();
            $this->connect();
            $this->register();
            $this->send($arr, true);
        }
    }
}
