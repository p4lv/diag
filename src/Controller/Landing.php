<?php

namespace Diag\Controller;


use Symfony\Component\HttpFoundation\Response;

class Landing
{
    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getIndex()
    {
        return new Response('New DiAg is hErE! ');
    }
}
