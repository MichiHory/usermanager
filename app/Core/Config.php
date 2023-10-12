<?php

namespace app\Core;

class Config
{
    private array $config;

    public function __construct(string $configFile) {
        $this->config = include $configFile;
    }

    public function get($key, $default = null) {
        return $this->config[$key] ?? $default;
    }
}