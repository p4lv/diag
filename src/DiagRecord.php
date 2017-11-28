<?php

namespace Diag;


interface DiagRecord
{

    public function getId();

    public function getCreatedAt(): DateTime;

    public function getMessage(): string;

    public function toArray(): array;

}