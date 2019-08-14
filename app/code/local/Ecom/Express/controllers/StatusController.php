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

class Ecom_Express_StatusController extends Mage_Core_Controller_Front_Action {
	
	/**
	 *
	 * Checking the Status and Update the current Status
	 */
	public function updateAction()	
	{
		  $params= array();
		  $type = 'post';
		  $params['username'] = $this->getRequest()->getParams('username');
		  $params['password'] = $this->getRequest()->getParams('password');
		  $params['awb']      = $this->getRequest()->getParams('awb');
		  $params['status']   = $this->getRequest()->getParams('status'); 
		  $msg = '';

		  if(($params['username'] != Mage::getStoreConfig('carriers/ecomexpress/username')) || ($params['password'] != Mage::getStoreConfig('carriers/ecomexpress/password')))
		  {
		    $msg = "User Authentication is incorrect";
		  }
		  
		  else{  
	          $model = Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('state',1)->getData();
			  $flag = false;
			  
			  foreach ($model as $val)
			  { 
			  	if($val['awb']==$params['awbs'])
			  	{ 	
			  		if($val['status']==$params['status']){
			  			$msg =  "Status is already Updated";
			  			$flag= true;
			  		}
			  		else {
			  		    $awb_model = Mage::getModel('ecomexpress/awb');
			  			$awb_data = $awb_model->getCollection()->addFieldToFilter('awb',$val['awb'])->getFirstItem()->getData();
						$awb_model->load($awb_data['awb_id'])->setData('status',$params['status']);
						$awb_model->save();
						$flag = true;
			  			$msg= "Status is Updated Successfully";
			  			break;
			  		}  
			  	} 
			  }  
			  if($flag == false)
			  	 $msg= " Wrong AWB Number";
		  }
		  return $msg;
	}
}
