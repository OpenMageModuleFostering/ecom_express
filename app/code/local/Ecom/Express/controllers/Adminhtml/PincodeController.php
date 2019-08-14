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

class Ecom_Express_Adminhtml_PincodeController extends Mage_Adminhtml_Controller_Action {

	
	 /**
     * Init Action to specify active menu and breadcrumb of the module
     */    
    protected function _initAction() {
    	
    	$this->loadLayout()
                ->_setActiveMenu('ecomexpress/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('ecomexpress/pincode')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) { 
                $model->setData($data);
            }
        }
        Mage::register('pincode_data', $model);
        return $this;
    }
     /**
     * Function to render pincode layout block
     */ 
    public function indexAction() {  
    	$this->_initAction()
                ->renderLayout();
    }
     /**
     * Loads default grid view of admin module
     */ 
    public function pincodegridAction() {
        $this->_initAction();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('ecomexpress/adminhtml_pincode_edit_tab_pincode')->toHtml()
        );
    }

     /**
     * Function to download Ecomexpress serviceable pincodes
     */		
    public function fetchAction() 
    {
    	$params = array();
    	$params['username'] = Mage::getStoreConfig('carriers/ecomexpress/username');
		$params['password'] = Mage::getStoreConfig('carriers/ecomexpress/password');
		if(Mage::getStoreConfig('carriers/ecomexpress/sanbox') ==1){
			$url = 'http://ecomm.prtouch.com/apiv2/pincodes/';
		}
		else {
			$url = 'http://api.ecomexpress.in/apiv2/pincodes/';
		}
		
		if($params)
		{
			$type = 'post';
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
			
			$pin_codes   =  Mage::helper('core')->jsonDecode($retValue);
			$sort_pincodes = array();
			foreach ($pin_codes as $key => $row)
			{
				$sort_pincodes[$key] = $row['pincode'];
			}
			array_multisort($sort_pincodes,SORT_ASC, $pin_codes);
			if(empty($pin_codes))
			{
				return false;
			}
						
			// Delete all pincodes
			$delete = Mage::getModel('ecomexpress/pincode')->delete_pinocdeAll();
			if(sizeof($pin_codes))
			{		
			   foreach ($pin_codes as $key => $item) {
			 
			   	try  {    
						   $model = Mage::getModel('ecomexpress/pincode');
						   $data = array();
						   $data['pincode'] = $item['pincode']; 
						   $data['city'] = $item['city'];
						   $data['state'] = $item['state'];
						   $data['dccode'] = $item['dccode'];
						   $data['city_code'] = $item['city_code'];
						   $data['state_code'] = $item['state_code'];
						   $data['date_of_discontinuance'] = $item['date_of_discontinuance'];
						   $data['created_at']=Mage::getModel('core/date')->date('Y-m-d H:i:s');
						   $data['updated_at']=Mage::getModel('core/date')->date('Y-m-d H:i:s');
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
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ecomexpress')->__('Pincode Updated Successfully'));
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('Please add valid Username and Password'));
		}
		$this->_redirect('*/*/');
    }
    protected function _isAllowed()
    {
    	return Mage::getSingleton('admin/session')->isAllowed('ecomexpress/pincode');
    
    }
}