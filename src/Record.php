<?php

namespace Diag;


class Record implements DiagRecord
{
    protected $id;
    protected $message;
    protected $object;
    protected $createdAt;
    protected $eventType;
    protected $severity;
    protected $projectId;
    protected $version;

    public function __construct(array $data = [])
    {
        $this->version = $data['version'] ?? 0;

        $this->createdAt = $data['createdAt'] ?? $data['created_at'] ?? date('Y-m-d H:i:s');

        $this->id = $data['id'] ?? null;
        $this->message = $data['message'] ?? '';
        $this->object = $data['object'] ?? null;
        $this->eventType = $data['event'] ?? $data['eventType'] ?? 'general';
        $this->severity = $data['severity'] ?? Severity::LOG;
        $this->projectId = $data['projectId'] ?? $data['project_id'] ?? 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMessage(): string
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
    public function getEventType(): string
    {
        return $this->getType();
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
            'createdAt' => $this->getCreatedAt(),
            'projectId' => $this->getProjectId(),
            'version' => $this->getVersion(),
        ];
    }

    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return false|mixed|string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}