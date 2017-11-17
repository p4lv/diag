<?php

namespace Diag\Command;


use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

abstract class ContainerAwareCommand extends Command
{

    protected $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    protected function getContainer()
    {
        return $this->container;
    }
}