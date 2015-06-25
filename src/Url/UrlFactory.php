<?php namespace Dbrouter\Url;

use Dbrouter\Exception\Url\UrlException;

/**
 * UrlFactory container class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlFactory
{
    /**
     * Creates a new url object and fills them with data
     *
     * @param   array $data
     * @return  Url
     * @throws  UrlException
     */
    public static function make(array $data)
    {
        if ( ! isset($data['url']) || empty($data['url']) || ! is_string($data['url'])) {
            throw UrlException::make('Missing or unecpected url value!');
        }

        $url = new Url($data['url']);

        self::addUrlData($url, $data);

        return $url;
    }

    /**
     * Sets the url object attributes
     *
     * @param   Url $url
     * @param   array $data
     * @return  void
     */
    private static function addUrlData(Url $url, array $data)
    {
        // Url identifier object
        //
        // Supported keys: "id" and "urlId"

        if (isset($data['id']) && $data['id'] instanceof UrlIdentifier) {
            $url->setId($data['id']);
        }

        if (isset($data['urlId']) && $data['urlId'] instanceof UrlIdentifier) {
            $url->setId($data['urlId']);
        }

        // Segment item chain

        if (isset($data['segments']) && $data['segments'] instanceof UrlSegmentItem) {
            $url->setSegments($data['segments']);
        }

        // Explict segment count

        if (isset($data['segmentcount']) && is_numeric($data['segmentcount'])) {
            $url->setSegmentcount($data['segmentcount']);
        }

        // Current url weight

        if (isset($data['weight']) && is_numeric($data['weight'])) {
            $url->setWeight($data['weight']);
        }

        // Placeholde
        //
        // Supported keys: "uses_placeholde" and "usesPlaceholders

        if (isset($data['uses_placeholder']) && is_numeric($data['uses_placeholder'])) {
            $url->setUsesPlaceholder($data['uses_placeholder']);
        }

        if (isset($data['usesPlaceholder']) && is_numeric($data['usesPlaceholder'])) {
            $url->setUsesPlaceholder($data['usesPlaceholder']);
        }

        // Wildcard
        //
        // Supported keys: "uses_wildcard" and "usesWildcard"

        if (isset($data['uses_wildcard']) && is_numeric($data['uses_wildcard'])) {
            $url->setUsesPlaceholder($data['uses_wildcard']);
        }

        if (isset($data['usesWildcard']) && is_numeric($data['usesWildcard'])) {
            $url->setUsesPlaceholder($data['usesWildcard']);
        }
    }
}