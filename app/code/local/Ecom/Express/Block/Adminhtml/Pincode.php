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

class Ecom_Express_Block_Adminhtml_Pincode extends Mage_Adminhtml_Block_Widget_Grid_Container 
{
    /**
     * prepare button over here
     * 
     * */
    public function __construct() {
    	
    	
        $this->_controller = 'adminhtml_pincode';
        $this->_blockGroup = 'ecomexpress';
        $this->_headerText = Mage::helper('ecomexpress')->__('Pincode Manager');
        $this->_addButtonLabel = Mage::helper('ecomexpress')->__('Import Pincode');
        
		$this->_addButton('Pincode', array(
			   'label'      => Mage::helper('ecomexpress')->__('Import Pincode'),
			   'onclick'    => 'setLocation(\'' . $this->getUrl('*/*/fetch') . '\')',
			   'class'      => 'add' 
			));	        
		parent::__construct();
		$this->_removeButton('add');
		
    }

}