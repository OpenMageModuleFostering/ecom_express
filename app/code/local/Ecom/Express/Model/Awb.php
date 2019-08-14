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

class Ecom_Express_Model_Awb extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('ecomexpress/awb');
    }

	 /**
	 * Function to get waybill details if waybill number is supplied
	 */	
	public function loadByAwb($awb)
    {
        $resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$query = "SELECT * FROM " . $resource->getTableName('ecomexpress/awb')." WHERE awb = $awb";
		mage::log("$query");		
		$data = $readConnection->fetchOne($query);
        return $data;
    }
}