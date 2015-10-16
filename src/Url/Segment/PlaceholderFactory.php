<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Database\Mapper\PlaceholderTypeMapper;
use Dbrouter\Exception\Url\PlaceholderException;
use Doctrine\DBAL\Connection;

/**
 * Path segment item factory
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class PlaceholderFactory
{
    /**
     * Initialize the placeholder object
     *
     * @param   PlaceholderTypeMapper       $placeholderTypeMapper
     * @return  Analyzer
     */
    public static function make($name, $type, Connection $db)
    {
        // Create mapper instance and check the given type

        $mapper = new PlaceholderTypeMapper($db);

        if ($mapper->getValue($type) === null) {
            throw PlaceholderException::make('Unsupported type "' . $type . '" given!');
        }

        // Return new placeholder instance

        return new Placeholder($name, $type, $mapper);
    }
}