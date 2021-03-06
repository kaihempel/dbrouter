<?php namespace Dbrouter\Url;

use Dbrouter\Url\UrlIdentifier;
use Dbrouter\Url\Segment\UrlSegmentItem;
use Dbrouter\Url\Segment\UrlSegmentParser;
use Dbrouter\Exception\Url\UrlException;

/**
 * Url container class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class Url
{
    /**
     * Max supported segments
     */
    const MAX_SEGMENTS      = 255;

    /**
     * Url identifier object
     *
     * @var UrlIdentifier
     */
    private $id             = NULL;

    /**
     * The raw url string
     *
     * @var string
     */
    protected $url          = NULL;

    /**
     * Segments after dispatch process
     *
     * @var UrlSegmentItem
     */
    protected $segments     = NULL;

    /**
     * Current number of segments
     *
     * @var integer
     */
    protected $segmentcount = 0;

    /**
     * Current calculated url weight
     *
     * @var integer
     */
    protected $weight       = 0;

    /**
     * Status flag if the url has placeholder
     *
     * @var type
     */
    protected $usesPlaceholder = false;

    /**
     * Status flag if the url has wildcards
     *
     * @var boolean
     */
    protected $usesWildcard = false;

    /**
     * Constructor
     *
     * @param   string $url The url string
     * @throws  UrlException
     */
    public function __construct($url) {

        if (empty($url) || ! is_string($url)) {
            throw UrlException::make('Unexpected URL value "' . $url . '" given!');
        }

        $this->url = $url;
    }

    /**
     * Checks if the ID object is set
     *
     * @return  boolean
     */
    public function hasId()
    {
        return (empty($this->id)) ? false : true;
    }

    /**
     * Sets the url identifier instance
     *
     * @param   UrlIdentifier $urlId
     * @return  Url
     * @throws  UrlException
     */
    public function setId(UrlIdentifier $urlId)
    {
        if ( ! empty($this->id) && $this->id->getId() != $urlId->getId()) {
            throw UrlException::make('Url already exists with ID "' . $this->id->getId() . '"!');
        }

        $this->id = $urlId;

        return $this;
    }

    /**
     * Returns the ID object
     *
     * @return  UrlIdentifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the raw url string
     *
     * @return  string
     */
    public function getRawUrl()
    {
        return $this->url;
    }

    /**
     * Returns the base segment of the item chain
     *
     * @return  UrlSegmentItem
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * Sets the url segment chain
     *
     * @param   UrlSegmentItem $item
     * @return  Url
     */
    public function setSegments(UrlSegmentItem $item)
    {
        if ( ! $item->isFirstItem()) {
            $item = self::setChainOnFirstItem($item);
        }

        $this->segments = $item;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the segment count
     *
     * @return  integer
     */
    public function getSegmentcount()
    {
        return $this->segmentcount;
    }

    /**
     * Sets the segment count
     *
     * @param   integer $count
     * @return  Url
     */
    public function setSegmentcount($count)
    {
        $this->segmentcount = (int)$count;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the whole calculated url weight
     *
     * @return  integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Sets the weight value
     *
     * @param   integer $weight
     * @return  Url
     */
    public function setWeight($weight)
    {
        $this->weight = (int)$weight;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the placeholder flag
     *
     * @return  boolean
     */
    public function usesPlaceholder()
    {
        return $this->usesPlaceholder;
    }

    /**
     * Sets the use placeholder flag
     *
     * @param boolean $usesPlaceholder
     * @return Url
     */
    public function setUsesPlaceholder($usesPlaceholder)
    {
        $this->usesPlaceholder = (bool)$usesPlaceholder;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the wildcard flag
     *
     * @return  boolean
     */
    public function usesWildcard()
    {
        return $this->usesWildcard;
    }

    /**
     * Sets the wildcard flag
     *
     * @param boolean $usesWildcard
     * @return Url
     */
    public function setUsesWildcard($usesWildcard)
    {
        $this->usesWildcard = (bool)$usesWildcard;

        // Return self for chaining

        return $this;
    }

    /**
     * Parse the raw url strint into his segments
     *
     * @return  Url
     */
    public function parse()
    {
        $parser = new UrlSegmentParser($this);
        $parser->process();

        return $this;
    }

    /**
     * Attach on item to the segment chain.
     *
     * @param   UrlSegmentItem  $item
     * @param   string          $mode
     * @return  Url
     */
    public function attachUrlSegmentItem(UrlSegmentItem $item, $mode = UrlSegmentItem::ATTACH_MODE_BELOW)
    {
        // Check mode

        if ( ! is_string($mode) || ($mode != UrlSegmentItem::ATTACH_MODE_ABOVE && $mode != UrlSegmentItem::ATTACH_MODE_BELOW)) {
            throw UrlException::make('Unexpected mode "' . $mode . '" given!');
        }

        // If no item set, set it mode indipendently

        if (empty($this->segments)) {
            $this->segments = $item;
            $this->segmentcount++;

        // Attach "below" (the easy way)
        //
        // This build the item chain from the top down.
        // The current item will be registered as the item below and
        // the current segment item stores them self as item above
        // inside. After, the current item is the new segment reference,
        // because it is the lowest!

        } else if ($mode == UrlSegmentItem::ATTACH_MODE_BELOW) {
            $this->segments->attachSegmentItemBelow($item);
            $this->segments = $item;
            $this->segmentcount++;

        // Attach "above" (have to finde the last item)
        //
        // This build the item chain from the lowest item up.
        // The current item will be stored. Then the last item is
        // searched. If the last item is reached, the given item
        // will be attached above.

        } else if ($mode == UrlSegmentItem::ATTACH_MODE_ABOVE) {

            // Store the current

            $current = $this->segments;

            // Iterate to the last item

            while ($current->isLastItem() === false) {
                $current = $current->getAbove();
            }

            // Attach the new item as item above

            $current->attachSegmentItemAbove($item);
            $this->segmentcount++;
        }

        // Calculate the new url weight

        $this->calculateNewWeight($item);

        // Set flags

        $this->updatePlaceholderFlag($item);
        $this->updateWildcardFlag($item);

        // Return self for chaining

        return $this;
    }

    /**
     * Increments the weight with the item weight
     *
     * @param   UrlSegmentItem $item
     * @return  void
     */
    private function calculateNewWeight(UrlSegmentItem $item)
    {
        $this->weight += $item->getWeight();
    }

    /**
     * Updates the placeholde state flag
     *
     * @param   UrlSegmentItem $item
     * @return  void
     */
    private function updatePlaceholderFlag(UrlSegmentItem $item)
    {
        if ($this->usesPlaceholder === false) {
            $this->usesPlaceholder = ($item->getType() == UrlSegmentItem::TYPE_PLACEHOLDER) ? true : false;
        }

    }

    /**
     * Updates the wildcard state flag
     *
     * @param   UrlSegmentItem $item
     * @return  void
     */
    private function updateWildcardFlag(UrlSegmentItem $item)
    {
        if ($this->usesWildcard === false) {
            $this->usesWildcard = ($item->getType() == UrlSegmentItem::TYPE_WILDCARD) ? true : false;
        }

    }

    /**
     * Set the given item chain on the first item
     *
     * @param   UrlSegmentItem $item
     * @return  UrlSegmentItem $item
     */
    public static function setChainOnFirstItem(UrlSegmentItem $item)
    {
        $count = 0;

        // Loops to the first item.

        while ( ! $item->isFirstItem()) {

            // Emergency exits check.

            if ($count > self::MAX_SEGMENTS) {
                throw UrlException::make('Merge process reached max segment count!');
            }

            // Reset item on the below one and increment count.

            $item = $item->getBelow();
            $count++;
        }

        return $item;
    }

}
