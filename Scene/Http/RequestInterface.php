<?php
/**
 * Request Interface
 *
*/
namespace Scene\Http;

/**
 * Scene\Http\RequestInterface
 *
 * Interface for Scene\Http\Request
 */
interface RequestInterface
{
    /**
     * Gets a variable from the $_REQUEST superglobal applying filters if needed
     *
     * @param string!|null $name
     * @param string|array|null $filters
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($name = null, $filters = null, $defaultValue = null);

    /**
     * Gets a variable from the $_POST superglobal applying filters if needed
     *
     * @param string!|null $name
     * @param string|array|null $filters
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getPost($name = null, $filters = null, $defaultValue = null);

    /**
     * Gets variable from $_GET superglobal applying filters if needed
     *
     * @param string!|null $name
     * @param string|array|null $filters
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getQuery($name = null, $filters = null, $defaultValue = null);

    /**
     * Gets variable from $_SERVER superglobal
     *
     * @param string! $name
     * @return mixed
     */
    public function getServer($name);

    /**
     * Checks whether $_REQUEST superglobal has certain index
     *
     * @param string! $name
     * @return boolean
     */
    public function has($name);

    /**
     * Checks whether $_POST superglobal has certain index
     *
     * @param string! $name
     * @return boolean
     */
    public function hasPost($name);

    /**
     * Checks whether the PUT data has certain index
      *
      * @param string $name
      * @return boolean
      * 
     */
    public function hasPut($name);

    /**
     * Checks whether $_GET superglobal has certain index
     *
     * @param string! $name
     * @return boolean
     */
    public function hasQuery($name);

    /**
     * Checks whether $_SERVER superglobal has certain index
     *
     * @param string! $name
     * @return mixed
     */
    public function hasServer($name);

    /**
     * Gets HTTP header from request data
     *
     * @param string! $header
     * @return string
     */
    public function getHeader($header);

    /**
     * Gets HTTP schema (http/https)
     *
     * @return string
     */
    public function getScheme();

    /**
     * Checks whether request has been made using ajax. Checks if $_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'
     *
     * @return boolean
     */
    public function isAjax();

    /**
     * Checks whether request has been made using SOAP
     *
     * @return boolean
     */
    public function isSoapRequested();

    /**
     * Checks whether request has been made using any secure layer
     *
     * @return boolean
     */
    public function isSecureRequest();

    /**
     * Gets HTTP raws request body
     *
     * @return string
     */
    public function getRawBody();

    /**
     * Gets active server address IP
     *
     * @return string
     */
    public function getServerAddress();

    /**
     * Gets active server name
     *
     * @return string
     */
    public function getServerName();

    /**
     * Gets information about schema, host and port used by the request
     *
     * @return string
     */
    public function getHttpHost();

    /**
     * Gets most possibly client IPv4 Address. This methods search in $_SERVER['REMOTE_ADDR'] and optionally in $_SERVER['HTTP_X_FORWARDED_FOR']
     *
     * @param boolean|null $trustForwardedHeader
     * @return string
     */
    public function getClientAddress($trustForwardedHeader = false);

    /**
     * Gets HTTP method which request has been made
     *
     * @return string
     */
    public function getMethod();

    /**
     * Gets HTTP user agent used to made the request
     *
     * @return string
     */
    public function getUserAgent();

    /**
     * Check if HTTP method match any of the passed methods
     *
     * @param string|array $methods
     * @param blloean $strict
     * @return boolean
     */
    public function isMethod($methods, $strict = false);

    /**
     * Checks whether HTTP method is POST. if $_SERVER['REQUEST_METHOD']=='POST'
     *
     * @return boolean
     */
    public function isPost();

    /**
     *
     * Checks whether HTTP method is GET. if $_SERVER['REQUEST_METHOD']=='GET'
     *
     * @return boolean
     */
    public function isGet();

    /**
     * Checks whether HTTP method is PUT. if $_SERVER['REQUEST_METHOD']=='PUT'
     *
     * @return boolean
     */
    public function isPut();

    /**
     * Checks whether HTTP method is HEAD. if $_SERVER['REQUEST_METHOD']=='HEAD'
     *
     * @return boolean
     */
    public function isHead();

    /**
     * Checks whether HTTP method is DELETE. if $_SERVER['REQUEST_METHOD']=='DELETE'
     *
     * @return boolean
     */
    public function isDelete();

    /**
     * Checks whether HTTP method is OPTIONS. if $_SERVER['REQUEST_METHOD']=='OPTIONS'
     *
     * @return boolean
     */
    public function isOptions();

    /**
     * Checks whether request include attached files
     *
     * @param boolean $onlySuccessful
     * @return boolean
     */
    public function hasFiles($onlySuccessful = false);

    /**
     * Gets attached files as \Scene\Http\Request\FileInterface compatible instances
     *
     * @param boolean|null $onlySuccessful
     * @return \Scene\Http\Request\FileInterface[]
     */
    public function getUploadedFiles($onlySuccessful = false);

    /**
     * Gets web page that refers active request. ie: http://www.google.com
     *
     * @return string
     */
    public function getHTTPReferer();

    /**
     * Gets array with mime/types and their quality accepted by the browser/client from $_SERVER['HTTP_ACCEPT']
     *
     * @return array
     */
    public function getAcceptableContent();

    /**
     * Gets best mime/type accepted by the browser/client from $_SERVER['HTTP_ACCEPT']
     *
     * @return array
     */
    public function getBestAccept();

    /**
     * Gets charsets array and their quality accepted by the browser/client from $_SERVER['HTTP_ACCEPT_CHARSET']
     *
     * @return array
     */
    public function getClientCharsets();

    /**
     * Gets best charset accepted by the browser/client from $_SERVER['HTTP_ACCEPT_CHARSET']
     *
     * @return string
     */
    public function getBestCharset();

    /**
     * Gets languages array and their quality accepted by the browser/client from $_SERVER['HTTP_ACCEPT_LANGUAGE']
     *
     * @return array
     */
    public function getLanguages();

    /**
     * Gets best language accepted by the browser/client from $_SERVER['HTTP_ACCEPT_LANGUAGE']
     *
     * @return string
     */
    public function getBestLanguage();

    /**
     * Gets auth info accepted by the browser/client from $_SERVER['PHP_AUTH_USER']
     *
     * @return array
     */
    public function getBasicAuth();

    /**
     * Gets auth info accepted by the browser/client from $_SERVER['PHP_AUTH_DIGEST']
     *
     * @return array
     */
    public function getDigestAuth();
}
