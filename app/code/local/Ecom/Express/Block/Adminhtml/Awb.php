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


class Ecom_Express_Block_Adminhtml_Awb extends Mage_Adminhtml_Block_Widget_Grid_Container {

	/**
	 * 
	 * prepare button here
	 * @return void
	 * */
    public function __construct() {
    	
        $this->_controller = 'adminhtml_awb';
        $this->_blockGroup = 'ecomexpress';
        $this->_headerText = Mage::helper('ecomexpress')->__('AWB Manager'); 
        
        $confirmationMessage = Mage::helper('core')->jsQuoteEscape(
        		Mage::helper('ecomexpress')->__('Are you sure you want to flush the AWB no?')
        		);
        
        $this->_addButton('flushAwb', array(
        		'name'		=> 'FLUSH AWB',
        		'value' => 'FLUSH AWB',
        		'label'      => Mage::helper('ecomexpress')->__('Flush AWB'),
        		'onclick' =>  "deleteConfirm('" . $confirmationMessage . "', '" . $this->getUrl('*/*/flushAwb') . "')"
         	 
        ));
                       
		$this->_addButton('COD', array(
				'name'		=> 'COD',
			    'value' => 'COD',
			    'label'      => Mage::helper('ecomexpress')->__('Import COD AWB'),
			    'onclick'    => 'setLocation(\'' . $this->getUrl('*/*/fetch').'awb/COD'.'\')',
			    'class'      => 'add',
			
        ));
		$this->_addButton('PPD', array(
				'name'		 =>'POD',
				'value'		 => 'POD',
			    'label'      => Mage::helper('ecomexpress')->__('Import PPD AWB'),
			    'onclick'    => 'setLocation(\'' . $this->getUrl('*/*/fetch') .'awb/PPD'. '\')',
			    'class'      => 'add'
			));				   		
        parent::__construct();
		$this->_removeButton('add');
    }
}
     