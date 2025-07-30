<?php

namespace Symfony\Config\FosElastica\ClientConfig;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ConnectionsConfig 
{
    private $url;
    private $username;
    private $password;
    private $host;
    private $port;
    private $proxy;
    private $authType;
    private $httpErrorCodes;
    private $awsAccessKeyId;
    private $awsSecretAccessKey;
    private $awsRegion;
    private $awsSessionToken;
    private $awsCredentialProvider;
    private $ssl;
    private $logger;
    private $compression;
    private $headers;
    private $curl;
    private $transport;
    private $timeout;
    private $connectTimeout;
    private $retryOnConflict;
    private $persistent;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function url($value): static
    {
        $this->_usedProperties['url'] = true;
        $this->url = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function username($value): static
    {
        $this->_usedProperties['username'] = true;
        $this->username = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function password($value): static
    {
        $this->_usedProperties['password'] = true;
        $this->password = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function host($value): static
    {
        $this->_usedProperties['host'] = true;
        $this->host = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function port($value): static
    {
        $this->_usedProperties['port'] = true;
        $this->port = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function proxy($value): static
    {
        $this->_usedProperties['proxy'] = true;
        $this->proxy = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function authType($value): static
    {
        $this->_usedProperties['authType'] = true;
        $this->authType = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed>|mixed $value
     *
     * @return $this
     */
    public function httpErrorCodes(mixed $value): static
    {
        $this->_usedProperties['httpErrorCodes'] = true;
        $this->httpErrorCodes = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function awsAccessKeyId($value): static
    {
        $this->_usedProperties['awsAccessKeyId'] = true;
        $this->awsAccessKeyId = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function awsSecretAccessKey($value): static
    {
        $this->_usedProperties['awsSecretAccessKey'] = true;
        $this->awsSecretAccessKey = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function awsRegion($value): static
    {
        $this->_usedProperties['awsRegion'] = true;
        $this->awsRegion = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function awsSessionToken($value): static
    {
        $this->_usedProperties['awsSessionToken'] = true;
        $this->awsSessionToken = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function awsCredentialProvider($value): static
    {
        $this->_usedProperties['awsCredentialProvider'] = true;
        $this->awsCredentialProvider = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function ssl($value): static
    {
        $this->_usedProperties['ssl'] = true;
        $this->ssl = $value;

        return $this;
    }

    /**
     * @default 'fos_elastica.logger'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function logger($value): static
    {
        $this->_usedProperties['logger'] = true;
        $this->logger = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function compression($value): static
    {
        $this->_usedProperties['compression'] = true;
        $this->compression = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function header(string $name, mixed $value): static
    {
        $this->_usedProperties['headers'] = true;
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function curl(string $name, mixed $value): static
    {
        $this->_usedProperties['curl'] = true;
        $this->curl[$name] = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function transport($value): static
    {
        $this->_usedProperties['transport'] = true;
        $this->transport = $value;

        return $this;
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
     * @default 0
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function retryOnConflict($value): static
    {
        $this->_usedProperties['retryOnConflict'] = true;
        $this->retryOnConflict = $value;

        return $this;
    }

    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function persistent($value): static
    {
        $this->_usedProperties['persistent'] = true;
        $this->persistent = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('url', $value)) {
            $this->_usedProperties['url'] = true;
            $this->url = $value['url'];
            unset($value['url']);
        }

        if (array_key_exists('username', $value)) {
            $this->_usedProperties['username'] = true;
            $this->username = $value['username'];
            unset($value['username']);
        }

        if (array_key_exists('password', $value)) {
            $this->_usedProperties['password'] = true;
            $this->password = $value['password'];
            unset($value['password']);
        }

        if (array_key_exists('host', $value)) {
            $this->_usedProperties['host'] = true;
            $this->host = $value['host'];
            unset($value['host']);
        }

        if (array_key_exists('port', $value)) {
            $this->_usedProperties['port'] = true;
            $this->port = $value['port'];
            unset($value['port']);
        }

        if (array_key_exists('proxy', $value)) {
            $this->_usedProperties['proxy'] = true;
            $this->proxy = $value['proxy'];
            unset($value['proxy']);
        }

        if (array_key_exists('auth_type', $value)) {
            $this->_usedProperties['authType'] = true;
            $this->authType = $value['auth_type'];
            unset($value['auth_type']);
        }

        if (array_key_exists('http_error_codes', $value)) {
            $this->_usedProperties['httpErrorCodes'] = true;
            $this->httpErrorCodes = $value['http_error_codes'];
            unset($value['http_error_codes']);
        }

        if (array_key_exists('aws_access_key_id', $value)) {
            $this->_usedProperties['awsAccessKeyId'] = true;
            $this->awsAccessKeyId = $value['aws_access_key_id'];
            unset($value['aws_access_key_id']);
        }

        if (array_key_exists('aws_secret_access_key', $value)) {
            $this->_usedProperties['awsSecretAccessKey'] = true;
            $this->awsSecretAccessKey = $value['aws_secret_access_key'];
            unset($value['aws_secret_access_key']);
        }

        if (array_key_exists('aws_region', $value)) {
            $this->_usedProperties['awsRegion'] = true;
            $this->awsRegion = $value['aws_region'];
            unset($value['aws_region']);
        }

        if (array_key_exists('aws_session_token', $value)) {
            $this->_usedProperties['awsSessionToken'] = true;
            $this->awsSessionToken = $value['aws_session_token'];
            unset($value['aws_session_token']);
        }

        if (array_key_exists('aws_credential_provider', $value)) {
            $this->_usedProperties['awsCredentialProvider'] = true;
            $this->awsCredentialProvider = $value['aws_credential_provider'];
            unset($value['aws_credential_provider']);
        }

        if (array_key_exists('ssl', $value)) {
            $this->_usedProperties['ssl'] = true;
            $this->ssl = $value['ssl'];
            unset($value['ssl']);
        }

        if (array_key_exists('logger', $value)) {
            $this->_usedProperties['logger'] = true;
            $this->logger = $value['logger'];
            unset($value['logger']);
        }

        if (array_key_exists('compression', $value)) {
            $this->_usedProperties['compression'] = true;
            $this->compression = $value['compression'];
            unset($value['compression']);
        }

        if (array_key_exists('headers', $value)) {
            $this->_usedProperties['headers'] = true;
            $this->headers = $value['headers'];
            unset($value['headers']);
        }

        if (array_key_exists('curl', $value)) {
            $this->_usedProperties['curl'] = true;
            $this->curl = $value['curl'];
            unset($value['curl']);
        }

        if (array_key_exists('transport', $value)) {
            $this->_usedProperties['transport'] = true;
            $this->transport = $value['transport'];
            unset($value['transport']);
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

        if (array_key_exists('retryOnConflict', $value)) {
            $this->_usedProperties['retryOnConflict'] = true;
            $this->retryOnConflict = $value['retryOnConflict'];
            unset($value['retryOnConflict']);
        }

        if (array_key_exists('persistent', $value)) {
            $this->_usedProperties['persistent'] = true;
            $this->persistent = $value['persistent'];
            unset($value['persistent']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['url'])) {
            $output['url'] = $this->url;
        }
        if (isset($this->_usedProperties['username'])) {
            $output['username'] = $this->username;
        }
        if (isset($this->_usedProperties['password'])) {
            $output['password'] = $this->password;
        }
        if (isset($this->_usedProperties['host'])) {
            $output['host'] = $this->host;
        }
        if (isset($this->_usedProperties['port'])) {
            $output['port'] = $this->port;
        }
        if (isset($this->_usedProperties['proxy'])) {
            $output['proxy'] = $this->proxy;
        }
        if (isset($this->_usedProperties['authType'])) {
            $output['auth_type'] = $this->authType;
        }
        if (isset($this->_usedProperties['httpErrorCodes'])) {
            $output['http_error_codes'] = $this->httpErrorCodes;
        }
        if (isset($this->_usedProperties['awsAccessKeyId'])) {
            $output['aws_access_key_id'] = $this->awsAccessKeyId;
        }
        if (isset($this->_usedProperties['awsSecretAccessKey'])) {
            $output['aws_secret_access_key'] = $this->awsSecretAccessKey;
        }
        if (isset($this->_usedProperties['awsRegion'])) {
            $output['aws_region'] = $this->awsRegion;
        }
        if (isset($this->_usedProperties['awsSessionToken'])) {
            $output['aws_session_token'] = $this->awsSessionToken;
        }
        if (isset($this->_usedProperties['awsCredentialProvider'])) {
            $output['aws_credential_provider'] = $this->awsCredentialProvider;
        }
        if (isset($this->_usedProperties['ssl'])) {
            $output['ssl'] = $this->ssl;
        }
        if (isset($this->_usedProperties['logger'])) {
            $output['logger'] = $this->logger;
        }
        if (isset($this->_usedProperties['compression'])) {
            $output['compression'] = $this->compression;
        }
        if (isset($this->_usedProperties['headers'])) {
            $output['headers'] = $this->headers;
        }
        if (isset($this->_usedProperties['curl'])) {
            $output['curl'] = $this->curl;
        }
        if (isset($this->_usedProperties['transport'])) {
            $output['transport'] = $this->transport;
        }
        if (isset($this->_usedProperties['timeout'])) {
            $output['timeout'] = $this->timeout;
        }
        if (isset($this->_usedProperties['connectTimeout'])) {
            $output['connectTimeout'] = $this->connectTimeout;
        }
        if (isset($this->_usedProperties['retryOnConflict'])) {
            $output['retryOnConflict'] = $this->retryOnConflict;
        }
        if (isset($this->_usedProperties['persistent'])) {
            $output['persistent'] = $this->persistent;
        }

        return $output;
    }

}
