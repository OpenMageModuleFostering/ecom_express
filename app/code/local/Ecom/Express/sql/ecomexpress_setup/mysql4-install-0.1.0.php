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

/**
* installer query to setup database table at the time of module installation
*/

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ecomexpress_awb')};
CREATE TABLE {$this->getTable('ecomexpress_awb')} (
  `awb_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `awb` varchar(255) NOT NULL DEFAULT '',
  `shipment_id` int(11) DEFAULT NULL,
  `shipment_to` varchar(255) NOT NULL DEFAULT '',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0= Unused, 1= Used',
  `orderid` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`awb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;		
		
-- DROP TABLE IF EXISTS {$this->getTable('ecomexpress_pincode')};
CREATE TABLE {$this->getTable('ecomexpress_pincode')} (
 `pincode_id` int(11) unsigned NOT NULL auto_increment,
 `pincode` varchar(20) NOT NULL default '',
 `city` varchar(20) NOT NULL default '',
 `state` varchar(5) NOT NULL default '', 
 `state_code` varchar(5) NOT NULL default '',
 `city_code` varchar(5) NOT NULL default '',
 `dccode` varchar(5) NOT NULL default '',
 `date_of_discontinuance` varchar(5) NOT NULL default '',    
 `created_at` DATETIME NULL,
 `updated_at` DATETIME NULL,
 PRIMARY KEY (`pincode_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
$installer->endSetup();
