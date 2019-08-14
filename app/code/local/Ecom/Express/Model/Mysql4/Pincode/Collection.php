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

class Ecom_Express_Model_Mysql4_Pincode_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

     /**
	 * construct mysql collection object for pincode
	 */
    public function _construct() {
    	
        parent::_construct();
        $this->_init('ecomexpress/pincode');
        
    }
}