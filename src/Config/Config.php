<?php namespace Dbrouter\Config;

use Dbrouter\Exception\Config\ConfigException;
use Dbrouter\Utils\Collection;
use SplFileObject;

/**
 * Router config interface.
 * Defines the methods for config interaction.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class Config extends Collection implements ConfigInterface
{
    protected $filePath = '';

    /**
     *
     * @param type $filePath
     * @throws type
     */
    public function __construct($filepath)
    {
        // Set config file path and try to load config

        $this->setFilePath($filepath);

        if (file_exists($this->filePath)) {
            $this->load($this->filePath);
        }

    }

    /**
     * Returns one config value
     *
     * @param   string      $key
     * @return  mixed
     */
    public function getValue($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * Sets one value
     *
     * @param   string      $key
     * @param   mixed       $value
     * @return  ConfigInterface
     */
    public function setValue($key, $value)
    {
        $this->offsetSet($key, $value);

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the config file path
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set the config file path
     *
     * @param   string $filepath
     * @return  ConfigInterface
     */
    public function setFilePath($filepath)
    {
        if ( ! is_dir(dirname($filepath))) {
            throw ConfigException::make('Given path dosn\'t exists!');
        }

        if (file_exists($filepath) && ! is_readable($filepath)) {
            throw ConfigException::make('Given config file isn\'t readable!');
        }

        $this->filePath = $filepath;

        // Return self for chaining

        return $this;
    }

    /**
     * Loads the confg file
     *
     * @param   string      $filepath
     * @return  void
     */
    public function load()
    {
        $this->readFromPHPFile($this->filePath);
    }

    /**
     * Saves the config into given filepath
     *
     * @param   string      $filepath
     * @return  void
     */
    public function save()
    {
        $this->saveAsPHPFile($this->filePath);
    }

    /**
     * Creates a human readable PHP file content
     *
     * @return  string
     */
    protected function createCollectionFileContent()
    {
        $content  = '<?php' . PHP_EOL;
        $content .= 'return ' . var_export($this->getArrayCopy(), true) . ';' . PHP_EOL;
        $content .= '?>';

        return $content;
    }

    /**
     * Saves the current data as PHP file
     *
     * @param   string  $filepath
     * @param   boolean $overwrite
     * @return  void
     * @throws  CollectionException
     */
    protected function saveAsPHPFile($filepath)
    {
        if (file_exists($filepath) && ! is_writeable($filepath)) {
            throw ConfigException::make('Current file path "' . $filepath . '" is not writeable!');
        }

        $file = new SplFileObject($filepath, 'w');
        $file->fwrite($this->createCollectionFileContent());
    }

    /**
     * Imports a php array from file
     *
     * @param   string $filepath
     * @return  void
     * @throws  ConfigException
     */
    protected function readFromPHPFile($filepath)
    {
        if ( ! preg_match('/\.php$/', $filepath)) {
            throw ConfigException::make('Only PHP files are supported!');
        }

        $data = require $filepath;

        if ( ! is_array($data)) {
            throw ConfigException::make('Unexpected file content!');
        }

        $this->exchangeArray($data);
    }

}