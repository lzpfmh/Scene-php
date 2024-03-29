<?php
/**
 * Base64 Cache Frontend
 *
*/

namespace Scene\Cache\Frontend;

use \Scene\Cache\FrontendInterface;
use \Scene\Cache\Exception;

/**
 * Scene\Cache\Frontend\Base64
 *
 * Allows to cache data converting/deconverting them to base64.
 *
 * This adapters uses the base64_encode/base64_decode PHP's functions
 *
 *<code>
 *
 * // Cache the files for 2 days using a Base64 frontend
 * $frontCache = new Scene\Cache\Frontend\Base64(array(
 *    "lifetime" => 172800
 * ));
 *
 * //Create a MongoDB cache
 * $cache = new Scene\Cache\Backend\Mongo($frontCache, array(
 *      'server' => "mongodb://localhost",
 *      'db' => 'caches',
 *      'collection' => 'images'
 * ));
 *
 * // Try to get cached image
 * $cacheKey = 'some-image.jpg.cache';
 * $image    = $cache->get($cacheKey);
 * if ($image === null) {
 *
 *     // Store the image in the cache
 *     $cache->save($cacheKey, file_get_contents('tmp-dir/some-image.jpg'));
 * }
 *
 * header('Content-Type: image/jpeg');
 * echo $image;
 *</code>
 *
 */
class Base64 implements FrontendInterface
{
    
    /**
     * Frontend Options
     *
     * @var null|array
     * @access protected
    */
    protected $_frontendOptions;

    /**
     * \Scene\Cache\Frontend\Base64 constructor
     *
     * @param array|null $frontendOptions
     */
    public function __construct($frontendOptions = null)
    {
        $this->_frontendOptions = $frontendOptions;
    }

    /**
     * Returns the cache lifetime
     *
     * @return integer
     */
    public function getLifetime()
    {
        $options = $this->_frontendOptions;
        if (is_array($options)) {
            if (isset($options['lifetime'])) {
                return $options['lifetime'];
            }
        }
        return 1;
    }

    /**
     * Check whether if frontend is buffering output
     *
     * @return boolean
     */
    public function isBuffering()
    {
        return false;
    }

    /**
     * Starts output frontend. Actually, does nothing
     */
    public function start()
    {
    
    }

    /**
     * Returns output cached content
     *
     * @return string|null
     */
    public function getContent()
    {
        return null;
    }

    /**
     * Stops output frontend
     */
    public function stop()
    {
    
    }

    /**
     * Serializes data before storing them
     *
     * @param mixed $data
     * @return string
     */
    public function beforeStore($data)
    {
        return base64_encode($data);
    }

    /**
     * Unserializes data after retrieval
     *
     * @param mixed $data
     * @return mixed
     */
    public function afterRetrieve($data)
    {
        return base64_decode($data);
    }
}
