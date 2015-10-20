<?php
/**
 * Engine
 *
*/

namespace Scene\Mvc\View;

use \Scene\DI\Injectable;
use \Scene\DI\InjectionAwareInterface;
use \Scene\Mvc\ViewBaseInterface;
use \Scene\Mvc\View\Exception;

/**
 * Scene\Mvc\View\Engine
 *
 * All the template engine adapters must inherit this class. This provides
 * basic interfacing between the engine and the Scene\Mvc\View component.
 *
 */
abstract class Engine extends Injectable
{
    
    /**
     * View
     *
     * @var null|\Scene\Mvc\ViewBaseInterface
     * @access protected
    */
    protected $_view;

    /**
     * \Scene\Mvc\View\Engine constructor
     *
     * @param \Scene\Mvc\ViewBaseInterface $view
     * @param \Scene\DiInterface|null $dependencyInjector
     * @throws Exception
     */
    public function __construct($view, $dependencyInjector = null)
    {
        if ($view instanceof ViewBaseInterface === false) {
            throw new Exception('Invalid parameter type.');
        }

        if (!is_null($dependencyInjector) &&
            !is_object($dependencyInjector)) {
            throw new Exception('Invalid parameter type.');
        }

        $this->_view = $view;
        $this->_dependencyInjector = $dependencyInjector;
    }

    /**
     * Returns cached ouput on another view stage
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_view->getContent();
    }

    /**
     * Renders a partial inside another view
     *
     * @param string $partialPath
     * @param mixed $params
     * @return string
     * @throws Exception
     */
    public function partial($partialPath, $params = null)
    {
        if (!is_string($partialPath)) {
            throw new Exception('Invalid parameter type.');
        }

        return $this->_view->partial($partialPath, $params);
    }

    /**
     * Returns the view component related to the adapter
     *
     * @return \Scene\Mvc\ViewBaseInterface
     */
    public function getView()
    {
        return $this->_view;
    }
}
