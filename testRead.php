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

$dataFetcher = new \Diag\LogReader($sqlite);

$d = $dataFetcher->get(10);

var_dump($d);


echo " SUCCESS \n\n";
