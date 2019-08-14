<?php 
/**
 * EcomExpress
  *
  * NOTICE OF LICENSE
  *
  * This source file is subject to the Open Source License (OSL 3.0)
  * It is also available through the world-wide-web at this URL:
  * http://opensource.org/licenses/osl-3.0.php
  *
  * @category    Ecom
  * @package     Ecom_Express
  * @author      Ecom Dev Team <developer@ecomexpress.com >
  * @copyright   Copyright EcomExpress (http://ecomexpress.com/)
  * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  */

$installer = $this;
$installer->startSetup();
$installer->getConnection();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

if(!Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','ecom_length')->getId()) {  
	
	$setup->addAttribute('catalog_product', 'ecom_length', array(
	'group'     	=> 'general',
	'input'         => 'text',
	'type'          => 'int',
	'label'         => 'Length',
	'backend'       => '',
	'visible'       => 1,
    'required'		=> true,
	'user_defined' => 1,
	'searchable' => 1,
	'filterable' => 0,
	'comparable'	=> 0,
	'visible_on_front' => 0,
	'visible_in_advanced_search'  => 0,
	'is_html_allowed_on_front' => 0,
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
	));
}

if(!Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','ecom_width')->getId()) {
	
	$setup->addAttribute('catalog_product', 'ecom_width', array(
			'group'     	=> 'general',
			'input'         => 'text',
			'type'          => 'int',
			'label'         => 'Width',
			'backend'       => '',
			'visible'       => 1,
			'required'		=> true,
			'user_defined' => 1,
			'searchable' => 1,
			'filterable' => 0,
			'comparable'	=> 0,
			'visible_on_front' => 0,
			'visible_in_advanced_search'  => 0,
			'is_html_allowed_on_front' => 0,
			'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
	));
 
 }  
 if(!Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','ecom_height')->getId()) {
 
 	$setup->addAttribute('catalog_product', 'ecom_height', array(
 			'group'     	=> 'general',
 			'input'         => 'text',
 			'type'          => 'int',
 			'label'         => 'Height',
 			'backend'       => '',
 			'visible'       => 1,
 			'required'		=> true,
			'user_defined' => 1,
 			'searchable' => 1,
 			'filterable' => 0,
 			'comparable'	=> 0,
 			'visible_on_front' => 0,
 			'visible_in_advanced_search'  => 0,
 			'is_html_allowed_on_front' => 0,
 			'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
 	));
 
 }

 
$installer->endSetup();
?>