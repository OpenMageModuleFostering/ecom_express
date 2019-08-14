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

class Ecom_Express_Adminhtml_AwbController extends Mage_Adminhtml_Controller_Action {

   
     /**
     * Init Action to specify active menu and breadcrumb of the module
     */
    protected function _initAction() {
	     $this->loadLayout()
                ->_setActiveMenu('ecomexpress/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }
     /**
     * Function to render waybill layout block
     */
    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }
    
    /**
     * Loads default grid view of admin module
     */ 	
	
	public function gridAction()
	{
		$this->loadLayout();
		$this->getResponse()->setBody(
		$this->getLayout()->createBlock('ecomexpress/adminhtml_awb_grid')->toHtml());
	}
	
     /**
     * Function to fetch more waybills for the current client
     */
	
	public function fetchAction() 
	{
		$params = array();
		$params['type'] = $this->getRequest()->getParam('awb');
		
		$count = count(Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('awb_type',$params['type'])->addFieldToFilter('state',0)->getData());
		$fetch_awb     = Mage::getStoreConfig('carriers/ecomexpress/fetch_awb');
		$max_fetch_awb = Mage::getStoreConfig('carriers/ecomexpress/max_fetch_awb');
	
			
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
				
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ecomexpress')->__('AWB Downloaded Successfully'));
				}
				else
				{    Mage::log('cron fired', null , 'mylog.log' , true );
					
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('Please add valid Username,Password and Count in plugin configuration'));
				}
				$this->_redirect('*/*/');
			
			}  
			else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ecomexpress')->__('AWB Fetch cross the maximum limit'));
			}
		
    }
    
    
    public function flushAwbAction(){
    	 
    
    	$resource = Mage::getSingleton('core/resource');
    	$readConnection = $resource->getConnection('core_read');
    	$query = "TRUNCATE TABLE ".$resource->getTableName('ecomexpress/awb');
    	$readConnection->query($query);
    	 
    	Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ecomexpress')->__('AWB Flush Successfully'));
    
    	$this->_redirect('adminhtml/adminhtml_awb/index/');
    	 
    }
   
     /**
     * Function to export grid rows in CSV format
     */
    public function exportCsvAction() {
        $fileName = 'awb.csv';
        $content = $this->getLayout()->createBlock('ecomexpress/adminhtml_awb_grid')
                        ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }
     /**
     * Function to export grid rows in XML format
     */
    public function exportXmlAction() {
        $fileName = 'awb.xml';
        $content = $this->getLayout()->createBlock('ecomexpress/adminhtml_awb_grid')
                        ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
   		 
/**
 * 
 * @param  $fileName
 * @param  $content
 * @param string $contentType
 */
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
    	
    	
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        //die;
    }
    
    protected function _isAllowed()
    {
    	return Mage::getSingleton('admin/session')->isAllowed('ecomexpress/awb');
    
    }
		
}