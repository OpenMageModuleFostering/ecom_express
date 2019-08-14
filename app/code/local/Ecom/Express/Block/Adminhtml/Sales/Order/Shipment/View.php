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

/**
 * 
 * Sales_Order_Shipment_View grid
 */
class Ecom_Express_Block_Adminhtml_Sales_Order_Shipment_View extends Mage_Adminhtml_Block_Sales_Order_Shipment_View
{

	public function __construct()
	{ //die('---');
		
		
		parent::__construct();
		
		$shipment = Mage::getModel('sales/order_shipment')->load($this->getRequest()->getParam('shipment_id'));
		
		
		if ($this->getShipment()->getId()) {
			
			$trackings=Mage::getResourceModel('sales/order_shipment_track_collection')->addAttributeToFilter('parent_id',$this->getRequest()->getParam('shipment_id'));
			foreach($trackings as $tracking)
				$current_shipping_method = $tracking->getCarrierCode();
			
			if(count($trackings->getData())>0){
				if(strpos($current_shipping_method, 'ecomexpress') !== false)
				{
				
					$url =$this->getUrl('*/adminhtml_track/trackingAWB/',array('shipment_ids'=>$this->getShipment()->getId()));
				
					$this->_addButton('ecom_shipping_label', array(
							'label'     => Mage::helper('sales')->__('Ecom Shipping Label'),
							'class'     => 'save',
							'onclick'   => 'setLocation(\''.$this->getPrintUrl().'\')'
					)
							);
					
					
				$this->_addButton('track_order', array(
						'label'     => Mage::helper('sales')->__('Track Order'),
						'class'     => 'save',
						'onclick'    =>'javascript:popup(\''.$url.'\')' 
						
				)
						);
				$this->_addButton('cancel_order', array(
						'label'     => Mage::helper('sales')->__('Cancel Order'),
						'class'     => 'save',
						'onclick'   => 'setLocation(\''. $this->getUrl('*/adminhtml_cancel/cancelAWB/',array('shipment_ids'=>$this->getShipment()->getId())).'\')'
									
				)
						);
				}
			}
		}
	}	
	
}
