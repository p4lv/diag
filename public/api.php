<?php

namespace Diag;



$record = new Record($_GET['record']);

$storage = new DataMapper(new Storage\Sqlite());

$storage->store($record);