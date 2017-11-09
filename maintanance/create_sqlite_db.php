<?php

require_once __DIR__.'/../bootstrap.php';

shell_exec("touch ./../db.sqlite");
//$pdo = new \PDO('sqlite:./../db.sqlite');
$pdo->exec("
CREATE TABLE table_log
(
  id        INTEGER NOT NULL,
  message   TEXT,
  severity  INTEGER,
  eventType TEXT,
  projectId INT,
  version   INT
);
CREATE UNIQUE INDEX table_log_id_uindex
  ON table_log (id);
");