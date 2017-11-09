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
        $this->version = $data['version'] ?? 0;

        $this->id = $data['id'] ?? null;
        $this->message = $data['message'] ?? '';
        $this->object = $data['object'] ?? null;
        $this->eventType = $data['event'] ?? $data['eventType'] ?? 'general';
        $this->severity = $data['severity'] ?? Severity::LOG;
        $this->projectId = $data['projectId'] ?? $data['project_id'] ?? 0;
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

    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return (string)$this->eventType;
    }

    public function getSeverity()
    {
        return (int)$this->severity;
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

    private function getVersion()
    {
        return $this->version;
    }
}