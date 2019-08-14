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

class Ecom_Express_AjaxController extends Mage_Core_Controller_Front_Action {
	
	/**
	 * Function to fetch the awb no
	 */
	
	public function ajaxTrackAction()	
	{
		
		$shipment = Mage::getModel('sales/order_shipment')->load($this->getRequest()->getParam('id'));
		$current_shipping_method = Mage::getModel('sales/order')
		->load($shipment->getData('order_id'))
		->getShippingMethod();
		
		if(strpos($current_shipping_method, 'ecomexpress') !== false)
		{
			$order = Mage::getModel('sales/order')->load($this->getRequest()->getParam('id'));
			$payment = $order->getPayment()->getMethodInstance()->getCode();
		
		
			if($payment == 'cashondelivery'){
				$pay_type = 'COD';
				$model = Mage::getModel('ecomexpress/awb')->getCollection()
				->addFieldToFilter('state',0)
				->addFieldToFilter('awb_type','COD')->getData();
			}
			else{
					
				$pay_type = 'PPD';
				$model = Mage::getModel('ecomexpress/awb')->getCollection()
				->addFieldToFilter('state',0)
				->addFieldToFilter('awb_type','PPD')->getData();
		
			}
			if(count($model)>0){
			
				foreach($model as $models)
				{
					$awbno = $models['awb'];
					break;
				}
				return $awbno;
			}
		}
	}
}
