<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Database\Mapper\PlaceholderTypeMapper;
use Doctrine\DBAL\Connection;

/**
 * Path segment item factory
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class PlaceholderFactory
{
    /**
     * Initialize the analyzer object
     *
     * @param   PlaceholderTypeMapper       $placeholderTypeMapper
     * @return  Analyzer
     */
    public static function make(Connection $db)
    {
        return new Placeholder();
    }
}