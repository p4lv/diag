<?php

namespace Diag\Storage;


use DateTimeImmutable;

interface CanCleanUp
{
    public function cleanup(DateTimeImmutable $now = null) : bool;
}
