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
$installer->getConnection()
->addColumn($installer->getTable('ecomexpress/awb'), 'awb_type',
		array    
		   (
				'nullable' => false,
				'length' => 255,
				'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
				'comment' => 'type COD/PPD'
			)
	);
$installer->endSetup();
?>