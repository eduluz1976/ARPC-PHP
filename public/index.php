<?php

include_once('../vendor/autoload.php');

$s = new \eduluz1976\ARPC\Server();

try {
    
    $s->init();
    $s->run();
} catch (\Exception $ex) {
    http_response_code(400);
    \eduluz1976\ARPC\Server::sendJSON([
        'code' => $ex->getCode(),
        'msg' => $ex->getMessage()
    ]);
}
