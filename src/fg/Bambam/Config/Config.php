<?php namespace Bambam\Config;

use Symfony\Component\Yaml\Yaml;
use PetrGrishin\ArrayAccess\ArrayAccess;

class Config implements ConfigInterface
{
    /**
     * @var \PetrGrishin\ArrayAccess\ArrayAccess $values
     */
    protected $values;

    protected $configPath;

    public function __construct(array $values, $configPath = null)
    {
        $this->values = ArrayAccess::create($values);
        $this->configPath = $configPath;
    }

    public static function fromYaml($configPath) {
        if (!empty($configPath) && file_exists($configPath)) {
            $file = file_get_contents($configPath);
            $fileParams = Yaml::parse($file);
            if (is_array($fileParams)) {
                return new static($fileParams, $configPath);
            }else throw new \RuntimeException(sprintf('File \'%s\' must be valid YML!', $configPath));
        }else throw new \InvalidArgumentException(sprintf('Config path \'%s\' must be a file!', $configPath));
    }

    public static function fromPhp($configPath) {
        if (!empty($configPath) && file_exists($configPath)) {
            $fileParams = require($configPath);
            if (is_array($fileParams)) {
                return new static($fileParams, $configPath);
            }else throw new \RuntimeException(sprintf('File \'%s\' must be valid PhpArray Format!'), $configPath);
        }else throw new \InvalidArgumentException(sprintf('Config path \'%s\' must be a file!', $configPath));
    }

    public function getConfigPath() {
        return $this->configPath;
    }

    public function getValues() {
        return $this->values;
    }
}