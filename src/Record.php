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
        if(!$this->version)
        $this->version = $data['version'] ?? 0;

        if(!$this->createdAt)
        $this->createdAt = $data['createdAt'] ?? $data['created_at'] ?? date('Y-m-d H:i:s');

        if(!$this->id)
        $this->id = $data['id'] ?? null;
        if(!$this->message)
        $this->message = $data['message'] ?? '';
        if(!$this->object)
        $this->object = $data['object'] ?? null;
        if(!$this->eventType)
        $this->eventType = $data['event'] ?? $data['eventType'] ?? 'general';
        if(!$this->severity)
        $this->severity = $data['severity'] ?? Severity::LOG;
        if(!$this->projectId)
        $this->projectId = $data['projectId'] ?? $data['project_id'] ?? 0;
    }

    public function getId()
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

    public function toArray(): array
    {
        $record = [
            'message' => $this->getMessage(),
            'severity' => $this->getSeverity(),
            'eventType' => $this->getType(),
            'createdAt' => (string)$this->getCreatedAt(),
            'projectId' => $this->getProjectId(),
            'version' => $this->getVersion(),
        ];
        if ($this->getId()) {
            $record['id'] = $this->getId();
        }
        return $record;
    }

    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return false|mixed|string
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->createdAt);
    }

    /**
     * @param mixed|null $id
     * @return Record
     */
    public function setId(?mixed $id): Record
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed|string $message
     * @return Record
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param mixed|null $object
     * @return Record
     */
    public function setObject(?mixed $object): Record
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @param false|mixed|string $createdAt
     * @return Record
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param mixed|string $eventType
     * @return Record
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
        return $this;
    }

    /**
     * @param int|mixed $severity
     * @return Record
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
        return $this;
    }

    /**
     * @param int|mixed $projectId
     * @return Record
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
        return $this;
    }

    /**
     * @param int|mixed $version
     * @return Record
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }
}
