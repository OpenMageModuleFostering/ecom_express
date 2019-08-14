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

class Ecom_Express_Model_Automaticawb extends Mage_Core_Model_Abstract {
	
	
	/**
	 * Function to fetch more waybills dynamically 
	 */
	public function authenticateAwb($order,$pay_type, $awbno)
	{ 	
				
		$params = array();
		$type = 'post';
		$params['username'] = Mage::getStoreConfig('carriers/ecomexpress/username');
		$params['password'] = Mage::getStoreConfig('carriers/ecomexpress/password');
		$params['json_input']['AWB_NUMBER']=$awbno;
		$params['json_input']['ORDER_NUMBER']= $order['increment_id'];
		$params['json_input']['PRODUCT'] = $pay_type;
		
		$address = Mage::getModel('sales/order_address')->load($order['shipping_address_id']);
					
		
		if(isset($address['middlename'])){
			$params['json_input']['CONSIGNEE'] = $address['firstname']." ".$address['middlename']." ".$address['lastname'];
		}
		else{
			$params['json_input']['CONSIGNEE'] = $address['firstname']." ".$address['lastname'];
		}
		
		$params['json_input']['CONSIGNEE_ADDRESS1']=$address['street'];
		$params['json_input']['CONSIGNEE_ADDRESS2']=$address['postcode'];
		$params['json_input']['CONSIGNEE_ADDRESS3']=$address['city'];
		$params['json_input']['DESTINATION_CITY']=$address['city'];
		$params['json_input']['PINCODE']=$address['postcode'];
		$params['json_input']['STATE'] = $address['region'];
		$params['json_input']['MOBILE']=$address['telephone'];
		$params['json_input']['TELEPHONE']=$address['telephone'];
		
		$item = $order->getAllItems();
		
		
		$description = array();
		foreach($item as $item_description)
		{
			$params['json_input']['ITEM_DESCRIPTION'] = $item_description->getName(); 
			$item_description['weight']= $item_description['weight'];
		}
		
		$params['json_input']['ACTUAL_WEIGHT'] = $item_description['weight'];
		$params['json_input']['PIECES'] =	$order['total_item_count'];
		
		
		if($pay_type=='COD')
			$params['json_input']['COLLECTABLE_VALUE'] = $order['grand_total'];
		else
			$params['json_input']['COLLECTABLE_VALUE'] = 0;
		
				
			$params['json_input']['DECLARED_VALUE'] = $order['grand_total'];
			$params['json_input']['VOLUMETRIC_WEIGHT']=0;	
				
		 	$package_collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
			 ->addAttributeToFilter('ecom_length', array('notnull' => true))
			 ->addAttributeToFilter('ecom_height',array('notnull' => true))
			 ->addAttributeToFilter('ecom_width',array('notnull' => true))
			 ->addAttributeToFilter('sku', array('in' => $item_description->getData('sku')));
			 	
			 foreach($package_collection as $packge_dimension){
			 $params['json_input']['LENGTH']  = $packge_dimension->getData('ecom_length');
			 $params['json_input']['BREADTH'] =  $packge_dimension->getData('ecom_width');
			 $params['json_input']['HEIGHT'] = $packge_dimension->getData('ecom_height');
			 } 
								
			$params['json_input']['PICKUP_NAME'] = Mage::getStoreConfig('general/store_information/name');
			$params['json_input']['PICKUP_ADDRESS_LINE1'] = Mage::getStoreConfig('shipping/origin/street_line1');
			$params['json_input']['PICKUP_ADDRESS_LINE2'] = Mage::getStoreConfig('shipping/origin/street_line2');
			$params['json_input']['PICKUP_PINCODE'] = Mage::getStoreConfig('shipping/origin/postcode');
			$params['json_input']['PICKUP_PHONE'] = Mage::getStoreConfig('general/store_information/phone');
			$params['json_input']['PICKUP_MOBILE'] = Mage::getStoreConfig('general/store_information/phone');
			$params['json_input']['RETURN_PINCODE'] = Mage::getStoreConfig('shipping/origin/postcode');
			$params['json_input']['RETURN_NAME']  = Mage::getStoreConfig('general/store_information/name');
			$params['json_input']['RETURN_ADDRESS_LINE1'] = Mage::getStoreConfig('shipping/origin/street_line1');
			$params['json_input']['RETURN_ADDRESS_LINE2'] = Mage::getStoreConfig('shipping/origin/street_line2');
			$params['json_input']['RETURN_PHONE'] =  Mage::getStoreConfig('general/store_information/phone'); 
			$params['json_input']['RETURN_MOBILE'] =  Mage::getStoreConfig('general/store_information/phone');
			
		
		if(Mage::getStoreConfig('carriers/ecomexpress/sanbox') ==1)	
			$url = 'http://ecomm.prtouch.com/apiv2/manifest_awb/';   
		else 
			$url = 'http://api.ecomexpress.in/apiv2/manifest_awb/';
		
			
		if($params)
			{	
				$params['json_input'] = json_encode($params['json_input'],true);
				$params['json_input'] =  "[ ".$params['json_input']."]";
				$retValue = Mage::helper('ecomexpress')->executeCurl($url,$type,$params); 
				$getSessionInfo = Mage::getSingleton('core/session')->getInfoMsg();
			
				
				if(isset($getSessionInfo)){
						
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__($getSessionInfo));
					Mage::getSingleton('core/session')->unsInfoMsg();
					$this->_redirect('adminhtml/sales_shipment/index/');
					return ;
				}
				$getSessionMsg = Mage::getSingleton('core/session')->getMyMsg();
					
				
				if(isset($getSessionMsg)){
						
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__($getSessionMsg));
					Mage::getSingleton('core/session')->unsMyMsg();
					$this->_redirect('adminhtml/sales_shipment/index/');
					return;
						
				}
				$awb_codes   =  Mage::helper('core')->jsonDecode($retValue);
				
				if(empty($awb_codes))
				{	
				
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('Please add valid Username,Password and Count in plugin configuration'));
				}
				return $awb_codes;
			}
		else
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('Please add valid Username and Password in plugin configuration'));
			}
	}
}
