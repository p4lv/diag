<?php

namespace Diag;


use Symfony\Component\Yaml\Yaml;

class Config
{
    private $configPath;
    private $config;

    public function __construct(string $configPath = null)
    {
        $configPath = $configPath ?: getenv('DIAG_CONFIG_PATH');
        if (!$configPath || !is_file($configPath)) {
            throw new \RuntimeException('config path not defined');
        }
        $this->configPath = $configPath;
    }

    private function all()
    {
        if ($this->config === null) {
            $this->config = Yaml::parse(
                file_get_contents($this->configPath)
            );
        }
        return $this->config;
    }

    public function get(string $key)
    {
        return $this->all()[$key] ?? null;
    }

    public function hasStorage(string $storage) : bool
    {
        return (bool) $this->all()['storage'][$storage] ?? false;
    }

    public function getStorage(string $storage) : array
    {
        if (!$this->hasStorage($storage)) {
            throw new \LogicException('storage was not defined');
        }
        return $this->all()['storage'][$storage] ?? null;
    }
}