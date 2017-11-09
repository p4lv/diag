<?php

namespace Diag;

/**
 * Class LogRecord
 * @package Diag
 * @deprecated I think this is not the best way of usage...
 */
class LogRecord
{
    protected $id;
    protected $message;
    protected $object;
    protected $type;

    protected $severity;
    protected $projectId;
    protected $version;

    public function __construct($message, $object, $type, $severity, $projectId, $version = 0)
    {
        $this->message = $message;
        $this->object = $object;
        $this->type = $type;

        $this->severity = $severity;
        $this->projectId = $projectId;
        $this->version = $version;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getSeverity()
    {
        return $this->severity;
    }

    public function setSeverity($severity)
    {
        $this->severity = $severity;
    }

    public function getProjectId()
    {
        return $this->projectId;
    }

    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }
}
