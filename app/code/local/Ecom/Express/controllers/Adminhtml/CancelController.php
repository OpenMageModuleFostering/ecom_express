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

class Ecom_Express_Adminhtml_CancelController extends Mage_Adminhtml_Controller_Action {
	
	/**
	 *
	 * Function to cancel AWB 
	 */
	public function cancelAWBAction()
	{
		
		$params= array();
		$shipment_ids = $this->getRequest()->getParam('shipment_ids');
		
		if($shipment_ids)
		{
			$track_awb= Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('shipment_id',$shipment_ids)->getData();
			$type = 'post';
			$params['username'] = Mage::getStoreConfig('carriers/ecomexpress/username');
			$params['password'] = Mage::getStoreConfig('carriers/ecomexpress/password');			
			$params_info =array();
			
			foreach($track_awb as $awb){
				$params_info['awb'][] =  $awb['awb']; 
			}
			
			$params['awbs'] =  implode(",",$params_info['awb']);
			if(Mage::getStoreConfig('carriers/ecomexpress/sanbox') ==1){
				$url = 'http://ecomm.prtouch.com/apiv2/cancel_awb/';
			}
			else {
				$url = 'http://api.ecomexpress.in/apiv2/cancel_awb/';
			}
		
			if($params)
			{
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
				$params_response =array();
				foreach($awb_codes as $awb_msg)
				{
					$params_response['reason']= $awb_msg['reason'];
					$params_response['order_number']= $awb_msg['order_number'];
					$params_response['awb'] = $awb_msg['awb'];
					
					if($awb_msg['success'] !=1)
					{
						$params_response['success'] = 'Cancelled Failure';
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ecomexpress')->__("Shipment for the AWB number " . $params_response['awb']. " is ". $params_response['success']." ".  $params_response['reason']));
						
					}
					else {
						
						$params_response['success'] = 'Cancelled Successfully';
						$model = Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('shipment_id',$shipment_ids)->getData();
						
						$cancel_model = Mage::getModel('ecomexpress/awb');
						
						foreach($model as  $cancel){
							
							$cancel['status']='Cancelled';
							$cancel_data = $cancel_model->getCollection()->addFieldToFilter('awb',$cancel['awb'])->getFirstItem()->getData();
							$cancel_model->load($cancel_data['awb_id'])->setData('status',$cancel['status']);
							$cancel_model->save();
						}
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ecomexpress')->__("Shipment for the order number " .$params_response['order_number'].  " and AWB number " . $params_response['awb']. " is  ". $params_response['success']));
						
					}
				}
				if(empty($awb_codes))
				{
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('Please add valid Username,Password and AWB in plugin configuration'));
				}
			}
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('Shipment is not Canceled'));
		}
		$this->_redirect('adminhtml/sales_shipment/index/');
	}
}