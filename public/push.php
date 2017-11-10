<?php

require_once __DIR__.'/../bootstrap.php';

$sqlite = new \Diag\Storage\Sqlite($pdo);
$dataMapper = new \Diag\DataMapper($sqlite);

$record = new \Diag\Record($_POST);
$result = $dataMapper->store($record);

if($result) {
    $response = new \Diag\DiagResponse('ok', 200);
} else {
    $response = new \Diag\DiagResponse('error', 505);
}

echo $response;
