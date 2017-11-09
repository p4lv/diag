<?php

namespace Diag;


class DetailedLogRecord
{
    protected $projectId;
    protected $subProjectId;
    protected $type;
    protected $severity;
    protected $host;
    protected $file;
    protected $method;
    protected $lDateFrom;
    protected $lDateTo;
    protected $last;
    protected $reference;

    public function __construct($data)
    {
        foreach ($data as $k=>$v){
            $this->{$k} = $v;
        }

    }
}
