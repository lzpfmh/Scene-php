<?php
/**
 * Files Adapter
 *
*/
namespace Scene\Session\Adapter;

use \Scene\Session\Adapter;
use \Scene\Session\AdapterInterface;

/**
 * Scene\Session\Adapter\Files
 *
 * This adapter store sessions in plain files
 *
 *<code>
 * $session = new Scene\Session\Adapter\Files(array(
 *    'uniqueId' => 'my-private-app'
 * ));
 *
 * $session->start();
 *
 * $session->set('var', 'some-value');
 *
 * echo $session->get('var');
 *</code>
 *
 */
class Files extends Adapter implements AdapterInterface
{

}
