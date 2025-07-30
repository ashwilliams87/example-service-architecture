<?php

namespace Symfony\Config\FosElastica;

require_once __DIR__.\DIRECTORY_SEPARATOR.'ClientConfig'.\DIRECTORY_SEPARATOR.'ConnectionsConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ClientConfig 
{
    private $connections;
    private $timeout;
    private $connectTimeout;
    private $headers;
    private $connectionStrategy;
    private $_usedProperties = [];

    public function connections(array $value = []): \Symfony\Config\FosElastica\ClientConfig\ConnectionsConfig
    {
        $this->_usedProperties['connections'] = true;

        return $this->connections[] = new \Symfony\Config\FosElastica\ClientConfig\ConnectionsConfig($value);
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function timeout($value): static
    {
        $this->_usedProperties['timeout'] = true;
        $this->timeout = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function connectTimeout($value): static
    {
        $this->_usedProperties['connectTimeout'] = true;
        $this->connectTimeout = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function headers($value): static
    {
        $this->_usedProperties['headers'] = true;
        $this->headers = $value;

        return $this;
    }

    /**
     * @default 'Simple'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function connectionStrategy($value): static
    {
        $this->_usedProperties['connectionStrategy'] = true;
        $this->connectionStrategy = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('connections', $value)) {
            $this->_usedProperties['connections'] = true;
            $this->connections = array_map(fn ($v) => new \Symfony\Config\FosElastica\ClientConfig\ConnectionsConfig($v), $value['connections']);
            unset($value['connections']);
        }

        if (array_key_exists('timeout', $value)) {
            $this->_usedProperties['timeout'] = true;
            $this->timeout = $value['timeout'];
            unset($value['timeout']);
        }

        if (array_key_exists('connectTimeout', $value)) {
            $this->_usedProperties['connectTimeout'] = true;
            $this->connectTimeout = $value['connectTimeout'];
            unset($value['connectTimeout']);
        }

        if (array_key_exists('headers', $value)) {
            $this->_usedProperties['headers'] = true;
            $this->headers = $value['headers'];
            unset($value['headers']);
        }

        if (array_key_exists('connectionStrategy', $value)) {
            $this->_usedProperties['connectionStrategy'] = true;
            $this->connectionStrategy = $value['connectionStrategy'];
            unset($value['connectionStrategy']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['connections'])) {
            $output['connections'] = array_map(fn ($v) => $v->toArray(), $this->connections);
        }
        if (isset($this->_usedProperties['timeout'])) {
            $output['timeout'] = $this->timeout;
        }
        if (isset($this->_usedProperties['connectTimeout'])) {
            $output['connectTimeout'] = $this->connectTimeout;
        }
        if (isset($this->_usedProperties['headers'])) {
            $output['headers'] = $this->headers;
        }
        if (isset($this->_usedProperties['connectionStrategy'])) {
            $output['connectionStrategy'] = $this->connectionStrategy;
        }

        return $output;
    }

}
