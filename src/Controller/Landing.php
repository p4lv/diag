<?php

namespace Diag\Controller;


use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Landing
{

    public function getIndex()
    {
        return new ResponseHeaderBag('New DiAg is hErE! ');
    }
}
