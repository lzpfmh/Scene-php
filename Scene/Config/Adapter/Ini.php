<?php
/**
 * INI Adapter
 *
 */

namespace Scene\Config\Adapter;

use \Scene\Config;
use \Scene\Config\Exception;

/**
 * Scene\Config\Adapter\Ini
 *
 * Reads ini files and converts them to Scene\Config objects.
 *
 * Given the next configuration file:
 *
 *<code>
 * [database]
 * adapter = Mysql
 * host = localhost
 * username = scott
 * password = cheetah
 * dbname = test_db
 *
 * [Scene]
 * controllersDir = "../app/controllers/"
 * modelsDir = "../app/models/"
 * viewsDir = "../app/views/"
 * </code>
 *
 * You can read it as follows:
 *
 *<code>
 * $config = new Scene\Config\Adapter\Ini("path/config.ini");
 * echo $config->Scene->controllersDir;
 * echo $config->database->username;
 *</code>
 */
class Ini extends Config
{
    /**
     * \Scene\Config\Adapter\Ini constructor
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

        $iniConfig = parse_ini_file($filePath, true);

        if ($iniConfig === false) {
            throw new Exception('Configuration file ' . $filePath . " can't be loaded");
        }

        $config = [];

        foreach ($iniConfig as $section => $directives) {
            if (is_array($directives)) {
                $sections = [];
                foreach ($directives as $path => $lastValue) {
                    $sections[] = $this->_parseIniString($path, $lastValue);
                }
                if (count($sections)) {
                    $config[$section] = call_user_func_array('array_merge_recursive', $sections);
                }
            } else {
                $config[$section] = $directives;
            }
        }

        parent::__construct($config);
    }

    /**
     * Build multidimensional array from string
     *
     * <code>
     * $this->_parseIniString('path.hello.world', 'value for last key');
     *
     * // result
     * [
     *      'path' => [
     *          'hello' => [
     *              'world' => 'value for last key',
     *          ],
     *      ],
     * ];
     * </code>
     *
     * @param string $path
     * @param mixed $value
     * @return array
     */
    protected function _parseIniString($path, $value)
    {
        if (!is_string($path)) {
            throw new Exception('Invalid parameter type.');
        }

        $pos = strpos($path, '.');

        if ($pos === false) {
            return [$path => $value];
        }

        $key = substr($path, 0, $pos);
        $path = substr($path, $pos + 1);

        return [$key => $this->_parseIniString($path, $value)];
    }
}
