<?php
/**
 * Hello World! Module Entry Point
 *
 * @package    vicumg
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * payment button by liqpay Api
 */

// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once


$app = JFactory::getApplication();

$menu = $app->getMenu()->getActive()->id;

require_once dirname(__FILE__) . '/helper.php';


/*

*/


$liqpay = modLiqpayHelper::getLiqpay($params);

require JModuleHelper::getLayoutPath('mod_liqpay');