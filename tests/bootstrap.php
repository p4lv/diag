<?php

require_once __DIR__.'/../vendor/autoload.php';
//$pdo = new PDO(getenv('dsn'));

putenv('dsn=sqlite::memory:');

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("
CREATE TABLE table_log
(
  id        INTEGER NOT NULL,
  message   TEXT,
  severity  INTEGER,
  eventType TEXT,
  createdAt
  projectId INT,
  version   INT
);
CREATE UNIQUE INDEX table_log_id_uindex
  ON table_log (id);
");


