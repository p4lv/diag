<?php

namespace Diag;


class Record
{
    protected $id;
    protected $message;
    protected $object;
    protected $eventType;
    protected $severity;
    protected $projectId;
    protected $version;

    public function __construct(array $data = [])
    {
        $this->version = 0;
        foreach ($data as $k => $v) {
            if(property_exists($this, $k)) {
                // use setters or direct inserting with post validation ?
                $this->{$k} = $v;
            }
        }

        //todo: add rules for validations... ??
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
        return (string)$this->eventType;
    }

    public function setType(string $eventType)
    {
        $this->eventType = $eventType;
    }

    public function getSeverity()
    {
        return (int)$this->severity;
    }

    public function setSeverity(int $severity)
    {
        $this->severity = $severity;
    }

    public function getProjectId()
    {
        return (int)$this->projectId;
    }

    public function toArray()
    {
        return [
            'message' => $this->getMessage(),
            'severity' => $this->getSeverity(),
            'eventType' => $this->getType(),
            'projectId' => $this->getProjectId(),
            'version' => $this->getVersion(),
        ];
    }

    /**
     * @param mixed $version
     * @return Record
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
        return $this;
    }

    private function getVersion()
    {
        return $this->version;
    }
}