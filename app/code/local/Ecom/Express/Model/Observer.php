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

class Ecom_Express_Model_Observer
{
	/**
	 * Function to add waybill if shipping method is of Ecomexpress
	 */
	public function salesOrderShipmentSaveBefore($observer)
	{
		
	
	if(empty(Mage::app()->getRequest()->getParam("manual"))){
		
		$invoice = $observer->getEvent()->getInvoice();
		$shipment = $observer->getEvent()->getShipment();
		$order = $shipment->getOrder();
		$shipping_method= $order->getShippingMethod();
		
		if(strpos($shipping_method, 'ecomexpress') !== false)
		{
			$order = Mage::getModel('sales/order')->load($order->getEntityId());
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
				$response = Mage::getModel('ecomexpress/automaticawb')->authenticateAwb($order,$pay_type,$awbno);
		
					
				 
				foreach($response as $res) {
					foreach ($res as $value){
					}
				}
				if(isset($value['success']) && $value['success']==1){
						
					$track = Mage::getModel('sales/order_shipment_track')
					->setNumber($awbno)
					->setCarrierCode('ecomexpress')
					->setTitle('ecomexpress');
					$shipment->addTrack($track);
				}
				else {
					$track = Mage::getModel('sales/order_shipment_track')
					->setNumber($value['awb'])
					->setCarrierCode('ecomexpress')
					->setTitle('ecomexpress');
					$shipment->addTrack($track);
				}
			}
			else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('AWB number is not available'));
					
			}
		}
		
	}

}
	
	
	/**
	 * Function to update waybill if order tracking is of Ecomexpress
	 */
	public function salesOrderShipmentTrackSaveAfter($observer)
	{
		if(empty(Mage::app()->getRequest()->getParam("manual"))){
		
		$track = $observer->getEvent()->getTrack();
		$order = $track->getShipment()->getOrder();
		$shippingMethod = $order->getShippingMethod();
		
		if(!$shippingMethod)
		{
			return;
		}
		
		if($track->getCarrierCode() !='ecomexpress')
		{
			return ;
		}
		
	 	$model = Mage::getModel('ecomexpress/awb');
		$awbobj = $model->loadByAwb($track->getNumber());
		$awb_data=array();
		$awb_data['status']='Assigned';
		$awb_data['state'] = 1;
		$awb_data['orderid'] = $order->getId();
		$awb_data['shipment_to']  = $order->getShippingAddress()->getName();
		$awb_data['shipment_id']  = $track->getShipment()->getEntityId();
		$awb_data['updated_at']   = Mage::getModel('core/date')->date('Y-m-d H:i:s');
		$model->setData($awb_data);
		$model->setId($awbobj);
		$model->save(); 
		return;
	
	}
	
	 else{
		
	 	$track = $observer->getEvent()->getTrack();
		$order = $track->getShipment()->getOrder();
		$shippingMethod = $order->getShippingMethod();
		
		if(!$shippingMethod)
		{
			return;
		}
		
		if($track->getCarrierCode() !='ecomexpress')
		{
			return ;
		} 
		
		$model = Mage::getModel('ecomexpress/awb');
		$awbobj = $model->loadByAwb($track->getNumber());
		$awb_data=array();
		$awb_data['status']='Assigned';
		$awb_data['state'] = 1;
		$awb_data['orderid'] = $order->getId();
		$awb_data['shipment_to']  = $order->getShippingAddress()->getName();
		$awb_data['shipment_id']  = $track->getShipment()->getEntityId();
		$awb_data['updated_at']   = Mage::getModel('core/date')->date('Y-m-d H:i:s');
		$model->setData($awb_data);
		$model->setId($awbobj);
		$model->save();
		return;
	} 

}

	
	/**
	 * Execute function for fetchAWB of COD and PPD type through CRON
	 */
	
	public function fetchAwb()
	{
		
		$params = array();
		$params['type'] = 'COD';
		$count = count(Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('awb_type',$params['type'])->addFieldToFilter('state',0)->getData());
		$fetch_awb     = Mage::getStoreConfig('carriers/ecomexpress/fetch_awb');
		$max_fetch_awb = Mage::getStoreConfig('carriers/ecomexpress/max_fetch_awb');
		$min_fetch_awb = Mage::getStoreConfig('carriers/ecomexpress/min_fetch_awb');
	
		if($count < $min_fetch_awb){ 
			return false;
		}
		else {
			if($fetch_awb <= $max_fetch_awb) {
	
				$type = 'post';
				$params['username'] = Mage::getStoreConfig('carriers/ecomexpress/username');
				$params['password'] = Mage::getStoreConfig('carriers/ecomexpress/password');
				$params['count'] = $fetch_awb;
					
				if(Mage::getStoreConfig('carriers/ecomexpress/sanbox') ==1){
					$url = 'http://ecomm.prtouch.com/apiv2/fetch_awb/';
				}
				else {
					$url = 'http://api.ecomexpress.in/apiv2/fetch_awb/';
				}
				if($params)
				{
					$retValue = Mage::helper('ecomexpress')->executeCurl($url,$type,$params);
					$awb_codes   =  Mage::helper('core')->jsonDecode($retValue);
					if(empty($awb_codes))
					{
						return false;
					}	
					foreach ($awb_codes['awb'] as  $key => $item)
					{
						try{
							$model = Mage::getModel('ecomexpress/awb');
							$data = array();
							$data['awb'] = $item;
							$data['state'] = 0;
							$data['awb_type'] = $params['type'];
							$data['created_at'] = Mage::getModel('core/date')->date('Y-m-d H:i:s');
							$data['updated_at'] = Mage::getModel('core/date')->date('Y-m-d H:i:s');
							$model->setData($data);
							$model->save();
						}
						catch (Exception $e)
						{
							echo 'Caught exception: ',  $e->getMessage(), "\n";
							Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						}
					}
				}
				else
				{   
					return false;
				}
				return;
			}
			else {
					return false;
			}
			return;
		}
		
		$params['type'] = 'PPD';
		$count = count(Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('awb_type',$params['type'])->addFieldToFilter('state',0)->getData());
		$fetch_awb     = Mage::getStoreConfig('carriers/ecomexpress/fetch_awb');
		$max_fetch_awb = Mage::getStoreConfig('carriers/ecomexpress/max_fetch_awb');
		$min_fetch_awb = Mage::getStoreConfig('carriers/ecomexpress/min_fetch_awb');
		
		if($count < $min_fetch_awb){
			return false;
		}	
		
		else {
			if($fetch_awb <= $max_fetch_awb) {
				$type = 'post';
				$params['username'] = Mage::getStoreConfig('carriers/ecomexpress/username');
				$params['password'] = Mage::getStoreConfig('carriers/ecomexpress/password');
				$params['count'] = $fetch_awb;
					
					
				if(Mage::getStoreConfig('carriers/ecomexpress/sanbox') ==1){
					$url = 'http://ecomm.prtouch.com/apiv2/fetch_awb/';
				}
				else {
					$url = 'http://api.ecomexpress.in/apiv2/fetch_awb/';
				}
				if($params)
				{
					$retValue = Mage::helper('ecomexpress')->executeCurl($url,$type,$params);
					$awb_codes   =  Mage::helper('core')->jsonDecode($retValue);
					if(empty($awb_codes))
					{
						return false;
					}
					foreach ($awb_codes['awb'] as  $key => $item)
					{
						try{
							$model = Mage::getModel('ecomexpress/awb');
							$data = array();
							$data['awb'] = $item;
							$data['state'] = 0;
							$data['awb_type'] = $params['type'];
							$data['created_at'] = Mage::getModel('core/date')->date('Y-m-d H:i:s');
							$data['updated_at'] = Mage::getModel('core/date')->date('Y-m-d H:i:s');
							$model->setData($data);
							$model->save();
						}
						catch (Exception $e)
						{
							echo 'Caught exception: ',  $e->getMessage(), "\n";
							Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						}
					}
				}
				else
				{
					return false;
				}
				return;
			}
			else {
				return false;
			}
			return;
		}	
	}
	
 }