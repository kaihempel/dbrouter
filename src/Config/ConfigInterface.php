<?php namespace Dbrouter\Config;

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
interface ConfigInterface
{
    /**
     * Returns one config value
     *
     * @param   string      $key            Key of the config value.
     * @return  mixed
     */
    public function getValue($key);

    /**
     * Sets one value
     *
     * @param   string      $key            Key of the config value.
     * @param   mixed       $value          Config value
     * @return  ConfigInterface
     */
    public function setValue($key, $value);

    /**
     * Returns the config file path
     *
     * @return  string
     */
    public function getFilePath();

    /**
     * Set the config file path
     *
     * @param   string      $filepath       Path to the config file.
     * @return  ConfigInterface
     */
    public function setFilePath($filepath);

    /**
     * Loads the confg file
     *
     * @return  void
     */
    public function load();

    /**
     * Saves the config into given filepath
     *
     * @return  void
     */
    public function save();
}