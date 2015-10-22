<?php
/**
 * Multiple Backends
 *
*/

namespace Scene\Cache;

use \Scene\Cache\Exception;

/**
 * Scene\Cache\Multiple
 *
 * Allows to read to chained backends writing to multiple backends
 *
 *<code>
 *   use Scene\Cache\Frontend\Data as DataFrontend,
 *       Scene\Cache\Multiple,
 *       Scene\Cache\Backend\Apc as ApcCache,
 *       Scene\Cache\Backend\Memcache as MemcacheCache,
 *       Scene\Cache\Backend\File as FileCache;
 *
 *   $ultraFastFrontend = new DataFrontend(array(
 *       "lifetime" => 3600
 *   ));
 *
 *   $fastFrontend = new DataFrontend(array(
 *       "lifetime" => 86400
 *   ));
 *
 *   $slowFrontend = new DataFrontend(array(
 *       "lifetime" => 604800
 *   ));
 *
 *   //Backends are registered from the fastest to the slower
 *   $cache = new Multiple(array(
 *       new ApcCache($ultraFastFrontend, array(
 *           "prefix" => 'cache',
 *       )),
 *       new MemcacheCache($fastFrontend, array(
 *           "prefix" => 'cache',
 *           "host" => "localhost",
 *           "port" => "11211"
 *       )),
 *       new FileCache($slowFrontend, array(
 *           "prefix" => 'cache',
 *           "cacheDir" => "../app/cache/"
 *       ))
 *   ));
 *
 *   //Save, saves in every backend
 *   $cache->save('my-key', $data);
 *</code>
 *
 */
class Multiple
{

    /**
     * Backends
     *
     * @var null|array
     * @access protected
    */
    protected $_backends;

    /**
     * \Scene\Cache\Multiple constructor
     *
     * @param \Scene\Cache\BackendInterface[]|null $backends
     * @throws Exception
     */
    public function __construct($backends = null)
    {
        if (!is_null($backends)) {
            if (!is_array($backends)) {
                throw new Exception('The backends must be an array');
            }
            $this->_backends = $backends;
        }
    }

    /**
     * Adds a backend
     *
     * @param \Scene\Cache\BackendInterface $backend
     * @return \Scene\Cache\Multiple
     * @throws Exception
     */
    public function push($backend)
    {
        if (!is_object($backend) ||
            $backend instanceof BackendInterface === false) {
            throw new Exception('The backend is not valid');
        }

        $this->_backends[] = $backend;
        return $this;
    }

    /**
     * Returns a cached content reading the internal backends
     *
     * @param string|int $keyName
     * @param long $lifetime
     * @return mixed
     */
    public function get($keyName, $lifetime = null)
    {
        foreach ($this->_backends as $backend) {
            $content = $backend->get($keyName, $lifetime);
            if ($content != null) {
                return $content;
            }
        }

        return null;
    }

    /**
     * Starts every backend
     *
     * @param string|int $keyName
     * @param long $lifetime
     */
    public function start($keyName, $lifetime = null)
    {
        foreach ($this->_backends as $backend) {
            $backend->start($keyName, $lifetime);
        }
    }

    /**
     * Stores cached content into all backends and stops the frontend
     *
     * @param string|null $keyName
     * @param string|null $content
     * @param long|null $lifetime
     * @param boolean|null $stopBuffer
     * @throws Exception
     */
    public function save($keyName = null, $content = null, $lifetime = null, $stopBuffer = null)
    {
        foreach ($this->_backends as $backend) {
            $backend->save($keyName, $content, $lifetime, $stopBuffer);
        }
    }

    /**
     * Deletes a value from each backend
     *
     * @param string|int $keyName
     * @throws Exception
     */
    public function delete($keyName)
    {
        foreach ($this->_backends as $backend) {
            $backend->delete($keyName);
        }
    }

    /**
     * Checks if cache exists in at least one backend
     *
     * @param string|int $keyName
     * @param int|null $lifetime
     * @return boolean
     * @throws Exception
     */
    public function exists($keyName = null, $lifetime = null)
    {
        foreach ($this->_backends as $backend) {
            if ($backend->exists($keyName, $lifetime) == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Flush all backend(s)
     *
     * @return boolean
     */
    public function flush()
    {
        foreach ($this->_backends as $key => $value) {
            $backend->flush();
        }

        return true;
    }


}
