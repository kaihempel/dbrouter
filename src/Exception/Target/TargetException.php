<?php namespace Dbrouter\Exception\Target;

use Dbrouter\Exception\DbrouterException;
use Dbrouter\Target\TargetTypeInterface;

/**
 * Target exception
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class TargetException extends DbrouterException implements TargetTypeInterface {

    /**
     * Current target type
     *
     * @var string
     */
    protected $type     = 'unknown';

    /**
     * Target error data
     *
     * @var array
     */
    protected $target   = array();

    /**
     * Last error data
     *
     * @var array
     */
    protected $error    = array();

    /**
     * Returns the exception object.
     *
     * @param   string $message
     * @return  TargetException
     */
    public static function make($message = NULL, $target = NULL)
    {
        $e = parent::make($message);

        $e->setTarget($target);
        $e->setError();

        return $e;
    }

    /**
     * Sets the debug backtrace and adds the target string
     *
     * @param   string $target
     * @return  void
     */
    public function setTarget($target)
    {
        $backtrace = debug_backtrace();

        if (isset($backtrace[0]['class'])) {
            $this->analyseType($backtrace[0]['class']);
        }

        if ( ! empty($target)) {
            $backtrace['target'] = $target;
        }

        $this->target = $backtrace;
    }

    /**
     * Sets the type depending on the throwing class
     *
     * @param string $classname
     */
    private function analyseType($classname)
    {
        if (preg_match('/closure/i', $classname)) {
            $this->setType(self::TARGETTYPE_CLOSURE);

        } else if (preg_match('/method/i', $classname)) {
            $this->setType(self::TARGETTYPE_METHOD);

        } else if (preg_match('/class/i', $classname)) {
            $this->setType(self::TARGETTYPE_CLASS);
        }
    }

    /**
     * Returns the current type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the target type
     *
     * @param   string $type
     * @return  void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the target error informations
     *
     * @return array
     */
    public function getTargetInformations()
    {
        return $this->target;
    }

    /**
     * Sets error informations
     *
     * @return void
     */
    public function setError()
    {
        $this->error = error_get_last();
    }

    /**
     * Returns the last error informations
     *
     * @return array
     */
    public function getErrorInformations()
    {
        return $this->error;
    }

}