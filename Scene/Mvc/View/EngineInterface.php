<?php
/**
 * View
 *
*/
namespace Scene\Mvc\View;

/**
 * Scene\Mvc\View\EngineInterface
 *
 * Interface for Scene\Mvc\View engine adapters
 */
interface EngineInterface
{

    /**
     * Returns cached ouput on another view stage
     *
     * @return array
     */
    public function getContent();

    /**
     * Renders a partial inside another view
     *
     * @param string $partialPath
     * @param mixed $params
     * @return string
     */
    public function partial($partialPath, $params = null);

    /**
     * Renders a view using the template engine
     *
     * @param string $path
     * @param array $params
     * @param boolean $mustClean
     */
    public function render($path, $params, $mustClean = false);
}
