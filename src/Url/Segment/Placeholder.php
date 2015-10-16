<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Database\Mapper\PlaceholderTypeMapper;
use Dbrouter\Exception\Url\PlaceholderException;

/**
 * Placeholder class
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class Placeholder implements PlaceholderTypeInterface
{
    /**
     * Placeholder identifier
     *
     * @var PlaceholderIdentifier
     */
    protected $id           = null;

    /**
     * Placeholder type mapper instance
     *
     * @var integer
     */
    protected $type_id      = null;

    /**
     * Current type
     *
     * @var string
     */
    protected $type         = '';

    /**
     * Regular expression to filter the value of the placeholder
     *
     * @var string
     */
    protected $regex        = '';

    /**
     * Placeholder type mapper instance
     *
     * @var string
     */
    protected $name         = '';

    /**
     * Type mapper
     *
     * @var PlaceholderTypeMapper
     */
    protected $mapper       = null;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $regex
     */
    public function __construct($name, $type, PlaceholderTypeMapper $typemapper)
    {
        if ( ! is_string($name)) {
            throw PlaceholderException::make('Unexpected name given!');
        }

        // Set values

        $this->name     = $name;
        $this->mapper   = $typemapper;

        $this->setType($name);
    }

    /**
     * Sets the placeholder identifier object
     *
     * @param   PlaceholderIdentifier $id
     * @return  Placeholder
     */
    public function setId(PlaceholderIdentifier $id)
    {
        $this->id = $id;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the placeholder identifier object
     *
     * @return PlaceholderIdentifier
     */
    public function getId()
    {
        return $id;
    }

    /**
     * Checks if the given placeholder type is already defined
     *
     * @param   string $type
     * @return  boolean
     */
    public function typeExists($type)
    {
        return ($this->mapper->getValue($type) === null) ? false : true;
    }

    /**
     * Sets the corresponding placeholder type
     *
     * @param   string $type
     * @return  PlaceholderType
     */
    public function setType($type)
    {
        if ( ! is_string($type)) {
            throw PlaceholderException::make('Unexpected type given!');
        }

        if ( ! $this->typeExists($type)) {
            throw PlaceholderException::make('Given type isn\'t defined!');
        }

        $this->type = $type;
        $this->setRegex($this->mapper->getRegex($type));

        return $this;
    }

    /**
     * Returns the current type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the corresponding type ID
     *
     * @return interger
     */
    public function getTypeId()
    {
        return $this->mapper->getValue($this->type);
    }

    /**
     *
     * @param string $regex
     * @return \Dbrouter\Url\Segment\Placeholder
     * @throws type
     */
    public function setRegex($regex)
    {
        if ( ! is_string($regex)) {
            throw PlaceholderException::make('Unexpected regex given!');
        }

        $this->regex = $regex;

        return $this;
    }

    /**
     * Returns a defined regex for the current type
     *
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

}