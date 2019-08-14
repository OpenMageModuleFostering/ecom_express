<?php
/**
 * CedCommerce
  *
  * NOTICE OF LICENSE
  *
  * This source file is subject to the Academic Free License (AFL 3.0)
  * You can check the licence at this URL: http://cedcommerce.com/license-agreement.txt
  * It is also available through the world-wide-web at this URL:
  * http://opensource.org/licenses/afl-3.0.php
  *
  * @category    Ecom
  * @package     Ecom_Express
  * @author      CedCommerce Core Team <connect@cedcommerce.com >
  * @copyright   Copyright CEDCOMMERCE (http://cedcommerce.com/)
  * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  */

class Ecom_Express_Adminhtml_AssignController extends Mage_Adminhtml_Controller_Action {
	
	/**
	 * Function to fetch more waybills for the current client
	 */
	
	public function fetchAction()
	{
		$orderId = $this->getRequest()->getParam('order');
		$order = Mage::getModel('sales/order')->load($orderId);
		$address = $order->getShippingAddress();
		
		$model = Mage::getModel('ecomexpress/pincode')->loadByPincode($address->getPostcode());
		//print_r($model);die(get_class($this));
		/*$allowed_country = array();
		$allowed_country = explode(',',Mage::getStoreConfig('carriers/ecomexpress/specificcountry'));
		 if(!Mage::getStoreConfig('carriers/ecomexpress/sallowspecific')){
			$allowed_countries = Mage::getModel('directory/country')->getResourceCollection()->loadByStore()->toOptionArray(false);
			foreach($allowed_countries as $allowed_countrys)
				$allowed_country[] = $allowed_countrys['value'];
		} */
		if($model)
		{
			$payment = $order->getPayment()->getMethodInstance()->getCode();
			$pay_type = 'PPD';
			if($payment == 'cashondelivery' || $payment=='checkmo' || $payment == 'msp_cashondelivery')
				$pay_type = 'COD';

			$collection = Mage::getModel('ecomexpress/awb')->getCollection()
						->addFieldToFilter('state',0)
						->addFieldToFilter('awb_type',$pay_type)->getFirstItem()->getData();
			if(count($collection)>0 && ctype_digit($collection['awb']))
				echo $collection['awb'];
			else
				echo 'AWB number is not available';
			
			
		}else{
			echo 'Pincode is not serviceable';
		}
	}

	protected function _isAllowed()
	{ 
		return true;
	
	}
}