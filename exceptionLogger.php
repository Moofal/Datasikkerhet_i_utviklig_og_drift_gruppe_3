<?php
require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Monolog\Handler\GelfHandler;
use Gelf\Message;
use Monolog\Formatter\GelfMessageFormatter;

$logger = new Logger('exceptions');

/* Graylog / gelf */
$transport = new Gelf\Transport\UdpTransport("127.0.0.1", 12201 /*,
Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN*/);
$publisher = new Gelf\Publisher($transport);
$handler = new GelfHandler($publisher,Logger::DEBUG);
$logger->pushHandler($handler);

$logger->notice($e);

?>
