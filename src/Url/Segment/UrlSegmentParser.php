<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Exception\Url\UrlException;
use Dbrouter\Url\Segment\UrlSegmentItemFactory;
use Dbrouter\Url\Segment\Analyzer;
use Dbrouter\Url\Url;

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
class UrlSegmentParser
{
    /**
     * Url instance
     *
     * @var Url
     */
    private $url                = null;

    /**
     * Segment analyzer instance
     *
     * @var Analyzer
     */
    private $segmentAnalyzer    = null;

    /**
     * Constructor
     *
     * @param Url $url
     */
    public function __construct(Url $url, Analyzer $analyzer)
    {
        $this->url              = $url;
        $this->segmentAnalyzer  = $analyzer;
    }

    /**
     * Parses the url string into single item segment objects.
     * The item objects will be stored inside the url object.
     *
     * @return UrlSegmentParser
     * @throws UrlException
     */
    public function process()
    {
        // Split the url

        $segments       = explode('/', $this->getPreparedUrlString());
        $segmentcount   = count($segments);

        // No segments or first segment is empty

        if ($segmentcount === 0 || (isset($segments[0]) && empty($segments[0]))) {
            throw UrlException::make('Unparseable string given ("' . $this->url->getRawUrl() . '")!');
        }

        // Add the items from the last to the first by using the "below" mode.

        for ($i = ($segmentcount -1); $i >= 0; $i--) {

            $this->url->attachUrlSegmentItem(
                $this->createUrlSegmentItem($segments[$i]),
                UrlSegmentItem::ATTACH_MODE_BELOW
            );

        }

        return $this;
    }

    /**
     * Creates on segment item
     *
     * @param string $segment
     * @return UrlSegmentItem
     */
    private function createUrlSegmentItem($segment)
    {
        if (empty($segment) || ! is_string($segment)) {
            throw UrlException::make('Unexpected segment given ("' . $segment . '")!');
        }

        return UrlSegmentItemFactory::make($segment, $this->url->getId(), null, $this->segmentAnalyzer);
    }

    /**
     * Returns a prepared url string
     *
     * @return string
     */
    private function getPreparedUrlString()
    {
        $urlstring = $this->url->getRawUrl();

        // Remove schem if its set

        $urlstring = preg_replace('/^([a-z]+:\/\/)/i', '', $urlstring);

        // Remove double slashes

        $urlstring = preg_replace('/(\/\/)/i', '/', $urlstring);

        // Remove everything before the fist slash and the the slash too.

        $slashpos = strpos($urlstring, '/');

        if ($slashpos !== false) {
            $urlstring = substr($urlstring, $slashpos +1);
        }

        // Check if the last sign a slash to

        if (substr($urlstring, -1) == '/') {
            $urlstring = substr($urlstring, 0, strlen($urlstring) -1);
        }

        return $urlstring;
    }
}