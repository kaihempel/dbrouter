<?php namespace Dbrouter\Database\Provider;

use Dbrouter\Database\Mapper\TypeMapper;
use Dbrouter\Database\Mapper\ExtentsionMapper;
use Dbrouter\Url\Segment\UrlSegmentItem;
use Dbrouter\Url\Segment\UrlSegmentIdentifier;
use Dbrouter\Url\Segment\UrlSegmentItemFactory;
use Dbrouter\Url\UrlIdentifier;
use Dbrouter\Url\Url;
use Dbrouter\Exception\Database\DataProviderException;
use Doctrine\DBAL\Connection;
use PDO;

/**
 * Segment dataprovider class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class SegmentProvider extends DataProvider
{
    const MODE_CHAIN   = 'chain';
    const MODE_SINGLE  = 'single';

    /**
     * Current mode
     *
     * @var string
     */
    private $mode = NULL;

    /**
     * Current segment item
     *
     * @var type
     */
    private $item = NULL;

    /**
     * Segment position in current Url
     *
     * @var type
     */
    private $position = 1;

    /**
     * Constructor
     *
     * @param   Connection          $db         Doctrine DBAL database connection
     * @param   Url                 $url        Optional: Url instance
     * @return  void
     */
    public function __construct(Connection $db, $url = NULL)
    {
        parent::__construct($db, $url);

        if ( ! empty($this->url) && $this->url->getSegmentcount() > 0) {
            $this->setSegmentItem($this->url->getSegments());
        }
    }

    /**
     * Returns the url identifier instance.
     * Overwrites the parent method. If no url instance is set try
     * to get the url identifier from the item!
     *
     * @return UrlIdentifier|null
     */
    public function getUrlId()
    {
        $id = parent::getUrlId();

        if (empty($id) && $this->item instanceof UrlSegmentItem) {
            return $this->item->getUrlId();

        } else {
            return $id;
        }
    }

    /**
     * Sets the items
     *
     * @param   UrlSegmentItem      $item
     * @return  SegmentProvider
     */
    public function setSegmentItem(UrlSegmentItem $item)
    {
        if ($item->isFirstItem() && $item->getAbove() !== NULL) {
            $this->setSegmentChain($item);

        } else {
            $this->setSingleSegmentItem($item);
        }

        return $this;
    }

    /**
     * Sets the segment item chain for insert into database.
     * Given item will have to be the root!
     *
     * @param   UrlSegmentItem      $root
     * @return  SegmentProvider
     */
    private function setSegmentChain(UrlSegmentItem $root)
    {
        $this->mode = self::MODE_CHAIN;
        $this->item = $root;

        // Return self for chaining

        return $this;
    }

    /**
     *  Sets a single item for insert into database.
     *
     * @param   UrlSegmentItem      $item
     * @return  SegmentProvider
     */
    private function setSingleSegmentItem(UrlSegmentItem $item)
    {
        $this->mode = self::MODE_SINGLE;
        $this->item = $item;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns all stored items as itemchain
     *
     * @return  UrlSegmentItem
     */
    public function getItems()
    {
        return $this->item;
    }

    /**
     * Set the item position manualy
     *
     * @param   integer             $position
     * @return  SegmentProvider
     * @throws  DataProviderException
     */
    public function setPosition($position)
    {
        if ( ! is_numeric($position)) {
            throw DataProviderException::make('Unexpected position value "' . $position . '"!');
        }

        // Store position except in chain mode.
        // In chain mode, position will be set by the save loop!

        if ($this->mode != self::MODE_CHAIN) {
            $this->position = (int)$position;
        }

        return $this;
    }

    /**
     * Checks if a segment exists
     *
     * @param   string                  $segmentvalue
     * @return  integer|boolean
     */
    public function segmentExists($segmentvalue)
    {
        return $this->exists('dbr_segment', 'segment', $segmentvalue);
    }

    /**
     * Insert the segment values from the whole chain.
     *
     * @param   TypeMapper              $typemapper             Mapping of the segment types
     * @param   ExtentsionMapper        $extentsionmapper       Mapping of the
     * @return  void
     * @throws  DataProviderException
     */
    private function insertSegments(TypeMapper $typemapper, ExtentsionMapper $extentsionmapper)
    {

        // Initialize loop values

        $position   = 1;
        $urlId      = $this->getUrlId();
        $current    = $this->item;

        // Iterate forward over all segment items.
        // This loop begins on the lowest (root) url segment and
        // goes forward to the top.
        // This loop is nested in his own transaction to ensecure correct
        // inserts inside this method.

        $this->db->beginTransaction();

        while( ! empty($current)) {

            // Insert the segment if it not exists

            if ($current->getId() === NULL) {
                $id = $this->insertSegmentData($current, $typemapper, $extentsionmapper);
                $current->setId($id);
            }

            // Insert the mapping

            $this->insertSegmentUrlMapping($urlId, $current->getId(), $position);

            // Set new loop values

            $position++;
            $current = $current->getAbove();
        }

        // Commit this transaction

        $this->db->commit();

    }

    /**
     * Inserts a single segment into database
     *
     * @param   UrlSegmentItem          $item
     * @param   TypeMapper              $typemapper
     * @param   ExtentsionMapper        $extentsionmapper
     * @return  UrlSegmentIdentifier
     */
    public function insertSegmentData(UrlSegmentItem $item, TypeMapper $typemapper, ExtentsionMapper $extentsionmapper)
    {

        $id = $this->segmentExists($item->getValue());

        if ($id !== false) {
            return new UrlSegmentIdentifier($id);
        }

        $data = array();
        $data['segment']                = $item->getValue();
        $data['dbr_segmenttype_id']     = $typemapper->getTypeId($item);

        $types = array();
        $types['segment']               = PDO::PARAM_STR;
        $types['dbr_segmenttype_id']    = PDO::PARAM_INT;

        // Check if the item has an extentsion

        if ($item->hasExtentsion()) {
            $data['dbr_extentsiontype_id']  = $extentsionmapper->getExtentsionId($item);
            $types['dbr_extentsiontype_id'] = PDO::PARAM_INT;
        }

        $this->db->insert('dbr_url', $data, $types);
        return new UrlSegmentIdentifier($this->db->lastInsertId());
    }

    /**
     * Inserts the Url-Segment mapping.
     *
     * @param   UrlIdentifier           $urlId
     * @param   UrlSegmentIdentifier    $segmentId
     * @param   integer                 $position
     * @return  void
     * @throws  DataProviderException
     */
    private function insertSegmentUrlMapping(UrlIdentifier $urlId, UrlSegmentIdentifier $segmentId, $position)
    {
        if ( ! is_numeric($position)) {
            throw DataProviderException::make('Unexpected position value given!');
        }

        // Define query

        $query  = 'INSERT IGNORE `dbr_url_urlsegment` (`dbr_url_id`, `dbr_urlsegment_id`, `position`) VALUES (?, ?, ?)';

        // Set data and types

        $data   = array($urlId->getId(), $segmentId->getId(), $position);
        $types  = array(PDO::PARAM_INT, PDO::PARAM_INT, PDO::PARAM_INT);

        // Execute query

        $this->db->executeQuery($query, $data, $types);
    }

    /**
     * Saves the data
     *
     * @return  DataProvider
     */
    public function save() {

        if (empty($this->item)) {
            throw DataProviderException::make('No segment items set!');
        }

        // Start new transaction

        $this->db->beginTransaction();

        // Type and extentsion mapper

        $typemapper         = new TypeMapper($this->db);
        $extentsionmapper   = new ExtentsionMapper($this->db);

        // Insert mode depending data

        if ($this->mode == self::MODE_CHAIN) {
            $this->insertSegments($typemapper, $extentsionmapper);

        } else {
            $this->insertSegmentData($this->item, $typemapper, $extentsionmapper, $this->position);
        }

        // Commit whole transaction

        $this->db->commit();

        return $this;
    }

    /**
     * Loads the data
     *
     * @return DataProvider
     */
    public function load(UrlIdentifier $urlId)
    {
        // Initialize the querybuilder.

        $querybuilder = $this->db->createQueryBuilder();

        // Build the select query.
        //
        // The data orderd descending to support a easy top to bottom build!

        $querybuilder->select('seg.id', 'seg.segment')
                     ->from('dbr_url', 'url')
                     ->leftJoin('url', 'dbr_url_urlsegement', 'segmap', 'url.id = segmap.dbr_url_id')
                     ->leftJoin('segmap', 'dbr_urlsegment', 'seg', 'segmap.dbr_urlsegment_id = seg.id')
                     ->where('url.id = ?')
                     ->orderBy('segmap.position DESC');

        // Add url ID.

        $querybuilder->setParameter(0, $urlId->getId());

        // Execute query.

        $rows = $querybuilder->execute()->fetchAll();

        if ( ! empty($rows)) {
            $this->storeLoadedItemChain($rows);
        }

        // Return self

        return $this;
    }

    /**
     * Stores the loaded rows as item chain
     *
     * @param   array $rows
     * @return  void
     */
    private function storeLoadedItemChain(array $rows)
    {

        // Loop thru all rows

        $current = null;
        foreach ($rows as $row) {
            $new = UrlSegmentItemFactory::make($row->segment, $this->getUrlId(), new UrlSegmentIdentifier($row->id));

            // If current is already empty, store the created

            if (empty($current)) {
                $current = $new;

            // Otherwise attach the new one as Item below and set it as new current.
            // The loop will go forward and build the chain from top to bottom.

            } else {
                $current->attachSegmentItemBelow($new);
                $current = $new;
            }

        }

        // Register current after the last loop

        $this->item = $current;
    }

    /**
     *
     * @param UrlSegmentItem $item
     */
    public function loadByItemValue(UrlSegmentItem $item)
    {

    }


}