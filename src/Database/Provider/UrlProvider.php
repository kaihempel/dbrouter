<?php namespace Dbrouter\Database;

use Dbrouter\Url\Url;
use Dbrouter\Url\UrlIdentifier;
use Dbrouter\Url\UrlFactory;
use Dbrouter\Url\Segment\UrlSegmentItem;
use Dbrouter\Url\Segment\UrlSegmentMerger;
use Dbrouter\Database\SegmentProvider;
use Dbrouter\Exception\Database\DataProviderException;
use Doctrine\DBAL\Connection;
use PDO;
use Carbon\Carbon;

/**
 * Url dataprovider class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlProvider extends DataProvider
{
    /**
     * Constructor
     *
     * @param   Connection $db
     * @param   Url $url
     * @return  void
     */
    public function __construct(Connection $db, $url = NULL)
    {
        parent::__construct($db, $url);
    }

    /**
     * Sets the url instance
     *
     * @param   Url $url
     * @return  UrlProvider
     */
    public function setUrl(Url $url)
    {
        $this->url = $url;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns current url instance
     *
     * @return  Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Insert a new url entry including the segments
     *
     * @return  void
     */
    private function insertNewUrl()
    {
        // Check if URL already exists
        //if () {
        //  return;
        //}

        $id = $this->insertNewUrlData();
        $this->url->setId(new UrlIdentifier($id));
    }

    /**
     * Performs the URL insert query
     *
     * @return  integer
     */
    private function insertNewUrlData()
    {
        $data = array();
        $data['segmentcount']       = $this->url->getSegmentcount();
        $data['weight']             = $this->url->getWeight();
        $data['uses_placeholder']   = $this->url->usesPlaceholder();
        $data['uses_wildcard']      = $this->url->usesWildcard();
        $data['created']            = Carbon::now();

        $types = array();
        $types['segmentcount']      = PDO::PARAM_INT;
        $types['weight']            = PDO::PARAM_INT;
        $types['uses_placeholder']  = PDO::PARAM_BOOL;
        $types['uses_wildcard']     = PDO::PARAM_BOOL;
        $types['created']           = PDO::PARAM_STR;

        $this->db->insert('dbr_url', $data, $types);
        return $this->db->lastInsertId();
    }

    /**
     * Saves the data
     *
     * @return  UrlProvider
     */
    public function save()
    {
        if (empty($this->url)) {
            throw DataProviderException::make('No URL instance set!');
        }

        // Start transaction

        $this->db->beginTransaction();

        if ( ! $this->url->hasId()) {
            $this->insertNewUrl();
        }

        // Save the segments

        $segmentProvider = new SegmentProvider($this->db);
        $segmentProvider->setSegmentItem($this->url->getSegments(), $this->url);
        $segmentProvider->save();

        // Save the whole transaction

        $this->db->commit();

        // Return self for chaining

        return $this;
    }

    /**
     * Loads the data
     *
     * @param   UrlIdentifier $urlId
     * @return  UrlProvider
     */
    public function load(UrlIdentifier $urlId)
    {
        $items      = $this->loadUrlSegmentData($urlId);

        if (empty($items)) {
            throw DataProviderException::make('No items could be loaded!');
        }

        $urlString  = $this->buildUrlString($items);
        $urlData    = $this->loadUrlData($urlId);

        // Add the other values to the url data

        $urlData['urlId']       = $urlId;
        $urlData['url']         = $urlString;
        $urlData['segments']    = $items;

        // Create new url object

        $this->url = UrlFactory::make($urlData);

        return $this;
    }

    /**
     * Loads the url database entry
     *
     * @param   UrlIdentifier $urlId
     * @return  array
     */
    private function loadUrlData(UrlIdentifier $urlId)
    {
        $sql = 'SELECT segmentcount, weight, uses_placeholder, uses_wildcard FROM dbr_url WHERE id = ?';

        $data = $this->db->fetchAssoc($sql, array($urlId->getId()), array(PDO::PARAM_INT));

        if (empty($data)) {
            throw DataProviderException::make('Url doesn\'t exists!');
        }

        return $data;
    }


    /**
     * Loads all url segments and return the item chain
     *
     * @param   UrlIdentifier $urlId
     * @return  UrlSegmentItem
     */
    private function loadUrlSegmentData(UrlIdentifier $urlId)
    {
        $segmentProvider = new SegmentProvider($this->db);
        $segmentProvider->load($urlId);

        return $segmentProvider->getItems();
    }

    /**
     * Build the url string
     *
     * @param   UrlSegmentItem $item
     * @return  string
     */
    private function buildUrlString(UrlSegmentItem $item)
    {
        $merger = new UrlSegmentMerger();
        $merger->merge($item);

        return $merger->getUrl();
    }
}