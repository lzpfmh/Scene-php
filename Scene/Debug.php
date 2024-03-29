<?php
/**
 * Debug
 *
*/
namespace Scene;

/**
 * Scene\Debug
 *
 * Provides debug capabilities to Scene applications
 *
 */
class Debug
{
    /**
     * URI
     *
     * @var string
     * @access public
    */
    public $_uri = '//static.phalconphp.com/www/debug/2.0.0/';

    /**
     * Theme
     *
     * @var string
     * @access public
    */
    public $_theme = 'default';

    /**
     * Hide Document Root
     *
     * @var boolean
     * @access protected
    */
    protected $_hideDocumentRoot= false;

    /**
     * Show Backtrace
     *
     * @var boolean
     * @access protected
    */
    protected $_showBackTrace = true;

    /**
     * Show Files
     *
     * @var boolean
     * @access protected
    */
    protected $_showFiles = true;

    /**
     * Show File Fragment
     *
     * @var boolean
     * @access protected
    */
    protected $_showFileFragment = false;

    /**
     * Data
     *
     * @var null|array
     * @access protected
    */
    protected $_data;

    /**
     * Is active?
     *
     * @var null|boolean
     * @access protected
    */
    protected static $_isActive;

    /**
     * Change the base URI for static resources
     *
     * @param string $uri
     * @return \Scene\Debug
     * @throws Exception
     */
    public function setUri($uri)
    {
        $this->_uri = $uri;
        return $this;
    }

    /**
     * Sets if files the exception's backtrace must be showed
     *
     * @param boolean $showBackTrace
     * @return \Scene\Debug
     * @throws Exception
     */
    public function setShowBackTrace($showBackTrace)
    {
        $this->_showBackTrace = $showBackTrace;
        return $this;
    }

    /**
     * Set if files part of the backtrace must be shown in the output
     *
     * @param boolean $showFiles
     * @return \Scene\Debug
     * @throws Exception
     */
    public function setShowFiles($showFiles)
    {
        $this->_showFiles = $showFiles;
        return $this;
    }

    /**
     * Sets if files must be completely opened and showed in the output
     * or just the fragment related to the exception
     *
     * @param boolean $showFileFragment
     * @return \Scene\Debug
     * @throws Exception
     */
    public function setShowFileFragment($showFileFragment)
    {
        $this->_showFileFragment = $showFileFragment;
        return $this;
    }

    /**
     * Listen for uncaught exceptions and unsilent notices or warnings
     *
     * @param boolean|null $exceptions
     * @param boolean|null $lowSeverity
     * @return \Scene\Debug
     * @throws Exception
     */
    public function listen($exceptions = true, $lowSeverity = false)
    {
        if ($exceptions) {
            $this->listenExceptions();
        }
        if ($lowSeverity) {
            $this->listenLowSeverity();
        }
        return $this;
    }

    /**
     * Listen for uncaught exceptions
     *
     * @return \Scene\Debug
     */
    public function listenExceptions()
    {
        set_exception_handler(array($this, 'onUncaughtException'));

        return $this;
    }

    /**
     * Listen for unsilent notices or warnings
     *
     * @return \Scene\Debug
     */
    public function listenLowSeverity()
    {
        set_error_handler(array($this, 'onUncaughtLowSeverity'));
        set_exception_handler(array($this, 'onUncaughtException'));

        return $this;
    }

    /**
     * Halts the request showing a backtrace
     */
    public function halt()
    {
        throw new Exception("Halted request");
    }

    /**
     * Adds a variable to the debug output
     *
     * @param mixed $var
     * @param string|null $key
     * @return \Scene\Debug
     * @throws Exception
     */
    public function debugVar($varz, $key = null)
    {
       
        $this->_data[] = [$varz, debug_backtrace(), time()];
        return $this;
    }

    /**
     * Clears are variables added previously
     *
     * @return \Scene\Debug
     */
    public function clearVars()
    {
        $this->_data = null;
        return $this;
    }

    /**
     * Escapes a string with htmlentities
     *
     * @param mixed $value
     * @return string
     */
    protected function _escapeString($value)
    {
        if (is_string($value)) {
            return htmlentities(str_replace('\n', '\\n', $value), ENT_COMPAT, 'utf-8');
        }

        return $value;
    }

    /**
     * Produces a recursive representation of an array
     *
     * @param array $argument
     * @param int $n
     * @return string|int|null
     * @throws Exception
     */
    protected function _getArrayDump($argument, $n = 0)
    {

        $numberArguments = count($argument);

        if ($n >= 3 || $numberArguments == 0) {
            return null;
        }

        if ($numberArguments >= 10) {
            return $numberArguments;
        }

        $dump = [];

        foreach ($argument as $k => $v) {
            
            if (is_scalar($v)) {
                if ($v == '') {
                    $varDump = '['.$k.'] =&gt; (empty string)'; 
                } else {
                    $varDump = '['.$k.'] = &gt; '.$this->_escapeString($v);
                }
                $dump[] = $varDump;
                continue;
            }

            if (is_array($v)) {
                $dump[] = '['.$k.'] =&gt; Array('.$this->_getArrayDump($v, $n + 1).')';
                continue;
            }

            if (is_object($v)) {
                $dump[] = '['.$k.'] =&gt; Object('.get_class($v).')';
                continue;
            }

            if (is_null($v)) {
                $dump[] = '['.$k.'] = &gt; null';
                continue;
            }

            $dump[] = '['.$k.'] =&gt; '.$v;

        }

        return join(', ', $dump);
    }

    /**
     * Produces an string representation of a variable
     *
     * @param mixed $variable
     * @return string
     */
    protected function _getVarDump($variable)
    {
        
        if (is_scalar($variable)) {

            /**
             * Boolean variables are represented as "true"/"false"
             */
            if (is_bool($variable)) {
                if ($variable) {
                    return 'true';
                } else {
                    return 'false';
                }
            }

            /**
             * String variables are escaped to avoid XSS injections
             */
            if (is_string($variable)) {
                return $this->_escapeString($variable);
            }

            /**
             * Other scalar variables are just converted to strings
             */
            return $variable;
        }

        /**
         * If the variable is an object print its class name
         */
        
        if (is_object($variable)) {
            $className = get_class($variable);

            /**
             * Try to check for a "dump" method, this surely produces a better printable representation
             */
            if (method_exists($variable, 'dump')) {
                $dumpedObject = $variable->dump();

                /**
                 * dump() must return an array, generate a recursive representation using getArrayDump
                 */
                return 'Object(' . $className . ': ' . $this->_getArrayDump($dumpedObject) . ')';
            } else {

                /**
                 * If dump() is not available just print the class name
                 */
                return 'Object(' . $className . ')';
            }
        }

        /**
         * Recursively process the array and enclose it in []
         */
        if (is_array($variable)) {
            return 'Array(' . $this->_getArrayDump($variable) . ')';
        }

        /**
         * Null variables are represented as "null"
         */
        if (is_null($variable)) {
            return 'null';
        }

        /**
         * Other types are represented by its type
         */
        return gettype($variable);
    }

    /**
     * Returns the major framework's version
     *
     * @return string
     */
    public function getMajorVersion()
    {
        $parts = explode(' ', Version::get());
        return (string)$parts[0];
    }

    /**
     * Generates a link to the current version documentation
     *
     * @return string
     */
    public function getVersion()
    {
        $majorVersion = $this->getMajorVersion();
        $version = \Scene\Version::get();
        return '<div class="version">Scene Framework <a target="_new" href="//scene.ainstant.com/t'.$majorVersion.'" />' . $version . '</a></div>';
    }

    /**
     * Returns the css sources
     *
     * @return string
     */
    public function getCssSources()
    {
        $uri = $this->_uri;
        $sources = '<link href="' . $uri .'jquery/jquery-ui.css" type="text/css" rel="stylesheet" />';
        $sources .= '<link href="' . $uri . 'themes/default/style.css" type="text/css" rel="stylesheet" />';
        return $sources;
    }

    /**
     * Returns the javascript sources
     *
     * @return string
     */
    public function getJsSources()
    {
        $uri = $this->_uri;
        $sources = '<script type="text/javascript" src="' . $uri . 'jquery/jquery.js"></script>';
        $sources .= '<script type="text/javascript" src="' . $uri . 'jquery/jquery-ui.js"></script>';
        $sources .= '<script type="text/javascript" src="' . $uri . 'jquery/jquery.scrollTo.js"></script>';
        $sources .= '<script type="text/javascript" src="' . $uri . 'prettify/prettify.js"></script>';
        $sources .= '<script type="text/javascript" src="' . $uri . 'pretty.js"></script>';
        return $sources;
    }

    /**
     * Shows a backtrace item
     *
     * @param int $n
     * @param array $trace
     * @return string
     * @throws Exception
     */
    protected function showTraceItem($n, $trace)
    {
        
        /**
         * Every trace in the backtrace have a unique number
         */
        $html = '<tr><td align="right" valign="top" class="error-number">#' . $n .'</td><td>';

        if (isset($trace["class"])) {
            $className = $trace["class"];

            /**
             * We assume that classes starting by Phalcon are framework's classes
             */
            if (preg_match("/^Scene/", $className)) {

                /**
                 * Prepare the class name according to the Phalcon's conventions
                 */
                $prepareUriClass = str_replace("\\", "/", $className);

                /**
                 * Generate a link to the official docs
                 */
                $html .= '<span class="error-class"><a target="_new" href="//api.scene.ainstant.com/class/' . $prepareUriClass . '.html">' . $className . '</a></span>';

            } else {

                $classReflection = new \ReflectionClass($className);

                /**
                 * Check if classes are PHP's classes
                 */
                if ($classReflection->isInternal()) {
                    $prepareInternalClass = str_replace("_", "-", strtolower($className));

                    /**
                     * Generate a link to the official docs
                     */
                    $html .= '<span class="error-class"><a target="_new" href="http://php.net/manual/en/class.' . $prepareInternalClass . '.php">' . $className . '</a></span>';

                } else {
                    $html .= '<span class="error-class">' . $className . '</span>';
                }
            }

            /**
             * Object access operator: static/instance
             */
            $html .= $trace['type'];
        }

        /**
         * Normally the backtrace contains only classes
         */
        $functionName = $trace['function'];
        if (isset($trace['class'])) {
            $html .= '<span class="error-function">' . $functionName . '</span>';
        } else {

            /**
             * Check if the function exists
             */
            if (function_exists($functionName)) {

                $functionReflection = new \ReflectionFunction($functionName);

                /**
                 * Internal functions links to the PHP documentation
                 */
                if ($functionReflection->isInternal()) {

                    /**
                     * Prepare function's name according to the conventions in the docs
                     */
                    $preparedFunctionName = str_replace("_", "-", $functionName);
                    $html .= '<span class="error-function"><a target="_new" href="http://php.net/manual/en/function.' . $preparedFunctionName . '.php">' .$functionName . '</a></span>';

                } else {
                    $html .= '<span class="error-function">' .$functionName . '</span>';
                }

            } else {
                $html .= '<span class="error-function">' . $functionName . '</span>';
            }
        }

        /**
         * Check for arguments in the function
         */
        
        if (isset($trace['args'])) {
            $traceArgs = $trace['args'];

            if (count($traceArgs)) {
                $arguments = [];

                foreach ($traceArgs as $argument) {
                    
                    /**
                     * Every argument is generated using _getVarDump
                     * Append the HTML generated to the argument's list
                     */
                    $arguments[] = '<span class="error-parameter">' . $this->_getVarDump($argument) . '</span>';
                }

                /**
                 * Join all the arguments
                 */
                $html .= '(' . join(', ', $arguments) . ')';
            } else {
                $html .= '()';
            }
        }

        /**
         * When "file" is present, it usually means the function is provided by the user
         */
        if (isset($trace['file'])) {
            $filez = $trace['file'];

            $line = (string) $trace['line'];

            /**
             * Realpath to the file and its line using a special header
             */
            $html .= '<br/><div class="error-file">' . $filez . ' (' . $line . ')</div>';

            $showFiles = $this->_showFiles;

            if ($showFiles) {

                /**
                 * Open the file to an array using "file", this respects the openbase-dir directive
                 */
                $lines = file($filez);

                $numberLines = count($lines);
                $showFileFragment = $this->_showFileFragment;

                /**
                 * File fragments just show a piece of the file where the exception is located
                 */
                if ($showFileFragment) {

                    /**
                     * Take seven lines back to the current exception's line, @TODO add an option for this
                     */
                    $beforeLine = $line - 7;

                    /**
                     * Check for overflows
                     */
                    if ($beforeLine < 1) {
                        $firstLine = 1;
                    } else {
                        $firstLine = $beforeLine;                    
                    }

                    /**
                     * Take five lines after the current exception's line, @TODO add an option for this
                     */
                    $afterLine = $line + 5;

                    /**
                     * Check for overflows
                     */
                    if ($afterLine > $numberLines) {
                        $lastLine = $numberLines;
                    } else {
                        $lastLine = $afterLine;
                    }

                    $html .= '<pre class="prettyprint highlight:' . $firstLine . ':' . $line . " linenums:" . $firstLine . '">';
                
                } else {

                    $firstLine = 1;
                    $lastLine = $numberLines;
                    $html .= '<pre class="prettyprint highlight:' . $firstLine . ':' . $line . ' linenums error-scroll">';
                
                }

                $i = $firstLine;
                while ($i <= $lastLine) {
                    
                    /**
                     * Current line in the file
                     */
                    $linePosition = $i - 1;

                    /**
                     * Current line content in the piece of file
                     */
                    $currentLine = $lines[$linePosition];

                    /**
                     * File fragments are cleaned, removing tabs and comments
                     */
                    if ($showFileFragment) {
                        if ($i == $firstLine) {
                            if (preg_match("#\\*\\/#", rtrim($currentLine))) {
                                $currentLine = str_replace("* /", " ", $currentLine);
                            }
                        }
                    }

                    /**
                     * Print a non break space if the current line is a line break, this allows to show the html zebra properly
                     */
                    if ($currentLine == "\n" || $currentLine == "\r\n") {
                        $html .= "&nbsp;\n";
                    } else {

                        /**
                         * Don't escape quotes
                         * We assume the file is utf-8 encoded, @TODO add an option for this
                         */
                        $html .= htmlentities(str_replace("\t", "  ", $currentLine), ENT_COMPAT, "UTF-8");
                    }

                    $i++;                  
                }

                $html .= '</pre>';                
            }
        }

        $html .= '</td></tr>';

        return $html;

    }

    /**
     * Throws an exception when a notice or warning is raised
     */
    public function onUncaughtLowSeverity($severity, $message, $file, $line)
    {
        if (error_reporting() & $severity) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        }
    }

    /**
     * Handles uncaught exceptions
     *
     * @param \Exception $exception
     * @return boolean
     */
    public function onUncaughtException(\Exception $exception)
    {
        
        
        $obLevel = ob_get_level();

        /**
         * Cancel the output buffer if active
         */
        if ($obLevel > 0) {
            ob_get_clean();
        }

        /**
         * Avoid that multiple exceptions being showed
         */
        if (self::$_isActive) {
            echo $exception->getMessage();
            return;
        }

        /**
         * Globally block the debug component to avoid other exceptions to be shown
         */
        self::$_isActive = true;

        $className = get_class($exception);

        /**
         * Escape the exception's message avoiding possible XSS injections?
         */
        $escapedMessage = $this->_escapeString($exception->getMessage());

        /**
         * CSS static sources to style the error presentation
         * Use the exception info as document's title
         */
        $html = '<html><head><title>' . $className . ':' .$escapedMessage . '</title>';
        $html .= $this->getCssSources() . '</head><body>';

        /**
         * Get the version link
         */
        $html .= $this->getVersion();

        /**
         * Main exception info
         */
        $html .= '<div align="center"><div class="error-main">';
        $html .= '<h1>' . $className . ':' . $escapedMessage . '</h1>';
        $html .= '<span class="error-file">' . $exception->getFile() . ' (' . $exception->getLine() . ')</span>';
        $html .= '</div>';

        $showBackTrace = $this->_showBackTrace;

        /**
         * Check if the developer wants to show the backtrace or not
         */
        if ($showBackTrace) {

            $dataVar = $this->_data;

            /**
             * Create the tabs in the page
             */
            $html .= '<div class="error-info"><div id="tabs"><ul>';
            $html .= '<li><a href="#error-tabs-1">Backtrace</a></li>';
            $html .= '<li><a href="#error-tabs-2">Request</a></li>';
            $html .= '<li><a href="#error-tabs-3">Server</a></li>';
            $html .= '<li><a href="#error-tabs-4">Included Files</a></li>';
            $html .= '<li><a href="#error-tabs-5">Memory</a></li>';
            if (is_array($dataVar)) {
                $html .= '<li><a href="#error-tabs-6">Variables</a></li>';
            }

            $html .= '</ul>';

            /**
             * Print backtrace
             */
            $html .= '<div id="error-tabs-1"><table cellspacing="0" align="center" width="100%">';
            foreach ($exception->getTrace() as $n => $traceItem) {
                /**
                 * Every line in the trace is rendered using "showTraceItem"
                 */
                $html .= $this->showTraceItem($n, $traceItem);
            }
            $html .= '</table></div>';

            /**
             * Print _REQUEST superglobal
             */
            $html .= '<div id="error-tabs-2"><table cellspacing="0" align="center" class="superglobal-detail">';
            $html .= '<tr><th>Key</th><th>Value</th></tr>';
            foreach ($_REQUEST as $keyRequest => $value) {
                if (is_array($value)) {
                    $html .= '<tr><td class="key">' . $keyRequest . '</td><td>' . $value . "</td></tr>";
                } else {
                    $html .= '<tr><td class="key">' . $keyRequest . '</td><td>' . print_r($value, true) . "</td></tr>";
                }
            }
            $html .= '</table></div>';

            /**
             * Print _SERVER superglobal
             */
            $html .= '<div id="error-tabs-3"><table cellspacing="0" align="center" class="superglobal-detail">';
            $html .= '<tr><th>Key</th><th>Value</th></tr>';
            foreach ($_SERVER as $keyServer => $value) {
                $html .= '<tr><td class="key">' . $keyServer . '</td><td>' . $this->_getVarDump($value) . '</td></tr>';
            }
            $html .= '</table></div>';

            /**
             * Show included files
             */
            $html .= '<div id="error-tabs-4"><table cellspacing="0" align="center" class="superglobal-detail">';
            $html .= '<tr><th>#</th><th>Path</th></tr>';
            foreach (get_included_files() as $keyFile => $value) {
                $html .= '<tr><td>' . $keyFile . '</th><td>' . $value . '</td></tr>';
            }
            $html .= '</table></div>';

            /**
             * Memory usage
             */
            $html .= '<div id="error-tabs-5"><table cellspacing="0" align="center" class="superglobal-detail">';
            $html .= '<tr><th colspan="2">Memory</th></tr><tr><td>Usage</td><td>' . memory_get_usage(true) . '</td></tr>';
            $html .= '</table></div>';

            /**
             * Print extra variables passed to the component
             */
            
            if (is_array($dataVar)) {
                $html .= '<div id="error-tabs-6"><table cellspacing="0" align="center" class="superglobal-detail">';
                $html .= '<tr><th>Key</th><th>Value</th></tr>';
                foreach ($dataVar as $keyVar => $dataVar) {
                    $html .= '<tr><td class="key">' . $keyVar . '</td><td>' . $this->_getVarDump($dataVar[0]) . '</td></tr>';
                }
                $html .= '</table></div>';
            }

            $html .= '</div>';           
        }

        /**
         * Get Javascript sources
         */
        $html .= $this->getJsSources() . '</div></body></html>';

        /**
         * Print the HTML, @TODO, add an option to store the html
         */
        echo $html;

        /**
         * Unlock the exception renderer
         */
        self::$_isActive = false;

        return true;

    }
}
