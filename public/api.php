<?php

namespace Diag;



$record = new Record(...);

$storage = new DataMapper(new Storage\Sqlite());

$storage->store($record);