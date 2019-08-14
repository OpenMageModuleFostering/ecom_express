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

class Ecom_Express_Block_Adminhtml_Sales_Shipment_Grid extends Mage_Adminhtml_Block_Sales_Shipment_Grid
{

    /**
     * Prepare and set options for massaction
     *
     * @return Mage_Adminhtml_Block_Sales_Shipment_Grid
     */
    protected function _prepareMassaction()
    { 

    	parent::_prepareMassaction();
      
        $this->getMassactionBlock()->addItem('ecom_track_order', array(
        		'label'=> Mage::helper('sales')->__('Track Order'),
        	 	 'url'  => $this->getUrl('*/adminhtml_track/trackingAWB/'),
      
        ));
        $this->getMassactionBlock()->addItem('ecom_cancel_order', array(
        		'label'=> Mage::helper('sales')->__('Cancel Order'),
        		'url'  => $this->getUrl('*/adminhtml_cancel/cancelAWB'),
        ));
    

       
    }
	
}