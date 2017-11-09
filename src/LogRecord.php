<?php

namespace Diag;


class LogRecord
{
    protected $id;
    protected $message;
    protected $object;
    protected $type;
    protected $severity;
    protected $projectId;
    protected $subProjectId;

    public function __construct($message, $object, $type, $severity, $projectId, $subProjectId = null)
    {
        $this->message = $message;
        $this->object = $object;
        $this->type = $type;

        $this->severity = $severity;
        $this->projectId = $projectId;
        $this->subProjectId = $subProjectId;
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

    public function getSubProjectId()
    {
        return $this->subProjectId;
    }

    public function setSubProjectId($subProjectId)
    {
        $this->subProjectId = $subProjectId;
    }
}