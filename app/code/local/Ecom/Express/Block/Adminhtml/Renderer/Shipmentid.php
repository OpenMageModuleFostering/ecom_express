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


class Ecom_Express_Block_Adminhtml_Renderer_Shipmentid extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

	/**
	 * 
	 * @param Varien_Object $row
	 * @see Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract::render()
	 * @return integer
	 */
     public function render(Varien_Object $row)
    {
    	
		if($row->getOrderid() != NULL)
		{
			
        $order =Mage::getModel('sales/order')->load($row->getOrderid());
        $shipmentCollection = $order->getShipmentsCollection()->getData();
        
        foreach ($shipmentCollection as $ship){
        	
        	$shipment_id = $ship['entity_id'];
        }
        
        $shipment_url = Mage::helper("adminhtml")->getUrl("adminhtml/sales_shipment/view/",[ 'shipment_id'=> $shipment_id]);
     
        $link = '<a href="'. $shipment_url .'">'.$order->getData('entity_id').'</a>';
     
		return	$link;
		}
		else
		return "";
    }

}