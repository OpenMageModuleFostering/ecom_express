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

class Ecom_Express_Model_Pincode extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('ecomexpress/pincode');
    }
    /**
     * Function to load pincode details by pincode number
     *
     * @return pincode object
     */		
     public function loadByPincode($pincode)
    {         
    	
        $resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$query = "SELECT * FROM " . $resource->getTableName('ecomexpress/pincode')." WHERE pincode = $pincode";
		$data = $readConnection->fetchOne($query);
        return $data;
    }	  
 
    /**
     * Function to delete all pincode
     *
     */		
    public function delete_pinocdeAll()
    {    
    	
        $resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$query = "TRUNCATE TABLE ".$resource->getTableName('ecomexpress/pincode');  
		$readConnection->query($query);		
    }					
}