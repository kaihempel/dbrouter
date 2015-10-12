<?php namespace Dbrouter\Database;

use Dbrouter\Url\Url;
use Dbrouter\Url\Segment\UrlSegmentItem;
use Dbrouter\Exception\Database\UrlMatcherException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PDO;

/**
 * Url matcher class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlMatcher
{
    /**
     * Database connection instance
     *
     * @var Connection
     */
    protected $db           = null;

    /**
     * Url instance
     *
     * @var type
     */
    protected $url          = null;

    /**
     * Count of matches
     *
     * @var integer
     */
    protected $matchCount   = 0;

    /**
     * All loaded urls
     *
     * @var array
     */
    protected $matched      = array();

    /**
     * Constructor
     *
     * @param   Connection  $db
     * @param   Url         $url
     * @return  void
     */
    public function __construct(Connection $db, Url $url)
    {
        // Set the PDO fetch mode

        $db->setFetchMode(PDO::FETCH_OBJ);

        // Store objects

        $this->db   = $db;
        $this->url  = $url;

        // Execute match

        $this->match();
    }

    /**
     * Returns the match flag
     *
     * @return boolean
     */
    public function isMatch()
    {
        return (empty($this->matched)) ? false : true;
    }

    /**
     * Returns the current match count
     *
     * @return integer
     */
    public function getMatchCount()
    {
        return $this->matchCount;
    }

    /**
     * Return all matches
     *
     * @return array
     */
    public function getAllMatches()
    {
        return $this->matched;
    }

    /**
     * Return the best match
     *
     * @return stdObject|null
     */
    public function getBestMatch()
    {
        if (isset($this->matched[0])) {
            return $this->matched[0];
        }

        return null;
    }

    /**
     * Builds the match query and loads data from database
     *
     * @return void
     */
    private function match()
    {
        $segments = $this->getUrlSegments();

        if (empty($segments)) {
            throw UrlMatcherException::make('No segments!');
        }

        // Load data and store the result

        $data = $this->loadMatchingUrls($segments);

        if ( ! empty($data)) {
            $this->storeResult($data);
        }

    }

    /**
     * Returns current url segments
     *
     * @return UrlSegmentItem
     */
    private function getUrlSegments()
    {
        $segments = $this->url->getSegments();

        if (empty($segments)) {
            return $this->url->parse()
                             ->getSegments();
        }

        return $segments;
    }

    /**
     * Loads the matching urls
     *
     * @param   UrlSegmentItem $segment
     * @return  array
     */
    private function loadMatchingUrls(UrlSegmentItem $segment)
    {
        // Initialize the querybuilder.

        $queryBuilder = $this->db->createQueryBuilder();

        // Build select query

        $queryBuilder->select('dbr_url.id AS id')
                     ->from('dbr_url', 'url')
                     ->groupBy('dbr_url.id')
                     ->orderBy('dbr_url.weight DESC');

        $this->addAllSegmentConditions($queryBuilder, $segment);

        // Load matching urls from database

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * Adds the conditions for the whole segment chain
     *
     * @param   QueryBuilder $queryBuilder
     * @param   UrlSegmentItem $segment
     * @return  void
     */
    private function addAllSegmentConditions(QueryBuilder $queryBuilder, UrlSegmentItem $segment)
    {

        // Check if the current item is the first one

        if ( ! $segment->isFirstItem()) {
            Url::setChainOnFirstItem($segment);
        }

        // Initialize position

        $postition = 1;

        // Add all joins to the query

        do {
            // Add the segment depending join

            $this->addSegmentConditions($queryBuilder, $segment, $postition);

            // Increment position and reset current item on the next one

            $postition++;
            $segment = $segment->getAbove();

        } while ( ! empty($segment));

        // Add the whole position value as segmentcount.

        $queryBuilder->andWhere('url.segmentcount = :count');
        $queryBuilder->setParameter(':count', $postition, PDO::PARAM_INT);

    }

    /**
     * Adds the segment entries to the query
     *
     * @param   QueryBuilder $queryBuilder
     * @param   UrlSegmentItem $segment
     * @param   integer $position
     * @return  void
     */
    private function addSegmentConditions(QueryBuilder $queryBuilder, UrlSegmentItem $segment, $position)
    {
        if (empty($position) || ! is_numeric($position)) {
            throw UrlMatcherException::make('Unexpected position value!');
        }

        // Security type cast

        $position   = (int)$position;

        // Table alias and corresponding join condition

        $aliasMap   = 'map' . $position;
        $aliasSeg   = 'seg' . $position;
        $paramSeg   = ':seg' . $position;
        $condition  = 'url.id = ' . $aliasMap . '.dbr_url_id AND ' . $aliasMap . '.position = ' . $position;

        // Add the join to current query

        $queryBuilder->join('url', 'dbr_url_urlsegment', $aliasMap, $condition);
        $queryBuilder->join($aliasMap, 'dbr_urlsegment', $aliasSeg, 'map1.dbr_urlsegment_id = seg1.id' );

        // Add segment where

        $queryBuilder->andWhere($aliasSeg . '.segment = ' . $paramSeg);
        $queryBuilder->setParameter($paramSeg, $segment->getValue(), PDO::PARAM_STR);
    }

    /**
     * Sets the object attributes
     *
     * @param   array $data
     * @return  void
     */
    private function storeResult(array $data)
    {
        $this->matchCount   = count($data);
        $this->matched      = $data;
    }
}