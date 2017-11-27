<?php

namespace Diag;


interface DiagRecord
{

    public function getId(): ?int;

    public function getMessage(): string;

    public function toArray(): array;

}
