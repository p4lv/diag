<?php

namespace Diag\Storage;


interface CanCleanUp
{
    public function cleanup(\DateTime $now = null) : bool;
}