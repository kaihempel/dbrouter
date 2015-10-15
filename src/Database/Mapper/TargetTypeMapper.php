<?php namespace Dbrouter\Database\Mapper;

use Dbrouter\Database\Mapper\BaseMapper;
use Doctrine\DBAL\Connection;

/**
 * Extentsion mapper class.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class TargetTypeMapper extends BaseMapper
{
    /**
     * Load the type mapping
     *
     * @param Connection $db
     */
    protected function load(Connection $db)
    {
        // Check if data already loaded

        if ( ! $this->isEmpty()) {
            return;
        }

        // Load data

        $data = $this->executeQuery($db, 'SELECT id, name FROM dbr_targettype');

        // Store data

        foreach ($data as $row) {
            $this->setValue($row->name, $row->id);
        }
    }

    /**
     * Return the ID of the item type
     *
     * @param   UrlSegmentItem $item
     * @return  interger|null
     */
    public function getTargetId($target)
    {
        return $this->getValue($target->getType());
    }
}