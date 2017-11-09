<?php
/**
 * Created by PhpStorm.
 * User: r.shvets
 * Date: 06/11/2017
 * Time: 08:32
 */


require_once __DIR__.'/vendor/autoload.php';

$pdo = new \PDO('sqlite:db.sqlite');

$sqlite = new \Diag\Storage\Sqlite($pdo);
$dataMapepr = new \Diag\DataMapper($sqlite);

echo "INIT\n";

$data = [
    'message' => 'test'
];
$testRecord = new \Diag\Record($data);
echo "Testing single record ";
$dataMapepr->store($testRecord);

echo " SUCCESS \n\n";

$dataFetcher = new \Diag\LogReader($sqlite);

$d = $dataFetcher->get(1);

var_dump($d);

