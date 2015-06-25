<?php namespace Dbrouter\Database;

use Dbrouter\Url\Url;
use Dbrouter\Url\UrlIdentifier;
use Doctrine\DBAL\Connection;
use PDO;

/**
 * Base dataprovider class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
abstract class DataProvider
{
    /**
     * Current database instance
     *
     * @var Connection
     */
    protected $db = NULL;

    /**
     * Current Url instance
     *
     * @var Url
     */
    protected $url = NULL;

    /**
     * Constructor
     *
     * @param   Connection          $db                 Database connection
     * @param   Url                 $url                Optional: Url instance
     * @return  void
     */
    public function __construct(Connection $db, $url = NULL)
    {
        // Set the PDO fetch mode and transaction handling

        $db->setFetchMode(PDO::FETCH_OBJ);
        $db->setNestTransactionsWithSavepoints(true);

        // Store database connection

        $this->db = $db;

        // Check if a Url instance is given

        if ( ! empty($url) && $url instanceof Url) {
            $this->url = $url;
        }
    }

    /**
     * Returns the url identifier object.
     *
     * @return  UrlIdentifier|null
     */
    public function getUrlId()
    {
        if ( ! empty($this->url)) {
            return $this->url->getId();
        }

        return NULL;
    }
    /**
     * Checks if a database record exists and return the id or false.
     *
     * @param   string              $table              The name of the database table.
     * @param   string              $column             The column name.
     * @param   mixed               $value              The expected column value.
     * @return  integer|boolean
     */
    public function exists($table, $column, $value)
    {
        // Build query

        $sql = 'SELECT id FROM ' . $this->db->quote($table) . ' WHERE ? = ?';

        $data   = array();
        $data[] = $column;
        $data[] = $value;

        // Execute query

        return $this->db->fetchColumn($sql, $data, 'id');
    }

    /**
     * Saves the data
     *
     * @return DataProvider
     */
    abstract public function save();

    /**
     * Loads the data
     *
     * @return DataProvider
     */
    abstract public function load(UrlIdentifier $urlId);
}