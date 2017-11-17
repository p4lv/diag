<?php

namespace Diag\Controller;


use Symfony\Component\HttpFoundation\Response;

class Landing
{

    public function getIndex()
    {
        return new Response('New DiAg is hErE! ');
    }
}
