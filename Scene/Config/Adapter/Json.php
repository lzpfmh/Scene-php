<?php
/**
 * JSON Adapter
 *
*/

namespace Scene\Config\Adapter;

use \Scene\Config;
use \Scene\Config\Exception;

/**
 * Scene\Config\Adapter\Json
 *
 * Reads JSON files and converts them to Scene\Config objects.
 *
 * Given the following configuration file:
 *
 *<code>
 *{"Scene":{"baseuri":"\/Scene\/"},"models":{"metadata":"memory"}}
 *</code>
 *
 * You can read it as follows:
 *
 *<code>
 *  $config = new Scene\Config\Adapter\Json("path/config.json");
 *  echo $config->Scene->baseuri;
 *  echo $config->models->metadata;
 *</code>
 *
 */
class Json extends Config
{
    /**
     * \Scene\Config\Adapter\Json constructor
     *
     * @param string $filePath
     * @throws Exception
     */
    public function __construct($filePath)
    {
        if (!is_string($filePath)) {
            throw new Exception('Invalid parameter type.');
        }

        if (!file_exists($filePath)) {
            throw new Exception('The file is not exists.');
        }

        parent::__construct(json_decode(file_get_contents($filePath), true));
    }
}
