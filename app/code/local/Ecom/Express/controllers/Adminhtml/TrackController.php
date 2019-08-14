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
  * @author   	 Ecom Dev Team <developer@ecomexpress.com >
  * @copyright   Copyright EcomExpress (http://ecomexpress.com/)
  * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  */

class Ecom_Express_Adminhtml_TrackController extends Mage_Adminhtml_Controller_Action {
	
	/**
	 * Function to fetch the track status
	 */
	
	public function trackingAWBAction()	
	{
		
		$params= array();
		$shipment_ids = $this->getRequest()->getParam('shipment_ids');
		
		if($shipment_ids){ 
			
			$track_awb= Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('shipment_id',$shipment_ids)->getData();			
			$type = 'post';
			$params['username'] = Mage::getStoreConfig('carriers/ecomexpress/username');
			$params['password'] = Mage::getStoreConfig('carriers/ecomexpress/password');
			$params_info = array();
			
			foreach($track_awb as $awb){
				
				$order= Mage::getModel('sales/order')->load($awb['orderid'])->getData();
				$params_info['awb'][] =  $awb['awb'];
				$params_info['orderid'][] = $order['increment_id'];
			//	$params_info['orderid'][] = $awb['orderid']; 
			}
	
			
			$params['awb']     =  implode(",",$params_info['awb']);      	      
		    $params['orderid'] =  implode(",",$params_info['orderid']); 
				
			if(Mage::getStoreConfig('carriers/ecomexpress/sanbox') ==1){
				
				$url = 'http://ecomm.prtouch.com/track_me/api/mawbd/';
				
			}
			else {
				$url = 'http://api.ecomexpress.in/track_me/api/mawbd/';
			}
			
			if($params)
			{
				 $retValue = Mage::helper('ecomexpress')->executeCurl($url,$type,$params);	
				 $getSessionInfo = Mage::getSingleton('core/session')->getInfoMsg();
				
				 $xml   = simplexml_load_string($retValue, 'SimpleXMLElement', LIBXML_NOCDATA);
				 $array = json_decode(json_encode($xml), TRUE);
				 
				 if(empty($array['object'])){
				 	echo '<h4>Ecom service is currently Unavilable , please try after Sometime !!!<h4>';
				 	return ;
				 	
				 }
							 
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
				 						 							 
				 echo" 	<h2> Track Order Status</h2>
						<table border=\"5\" cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#808080\" width=\"100&#37;\" id=\"AutoNumber2\" bgcolor=\"#C0C0C0\">
				             	 <tr>
				 		             <td>S.No</td>
				          			 <td>AWB</td>
									 <td>OrderId</td>
									 <td>Weight</td>
									 <td>Origin</td>
				 					 <td>Destination</td>
				 					 <td>Location Name</td>
				 					 <td>Current Location Name</td>
				 		 			 <td>Zip Code</td>
									 <td>Shipping Name</td>
									 <td>Consignee Name</td>
									 <td>Pick up Date</td>
				 					 <td>Status</td>
									 <td>Expected Date</td>
									 <td>Last Date</td>
				      			</tr>";
				  $i=1;
			 foreach($array['object'] as $key => $val){
			 	if (!is_int($key) && $key == 'field'){
			 		if($val[15] =='Shipment RTO Lock') {
			 			$val[10]= 'Cancelled';
			 		}
			 		echo "<tr>
			 		<td>".$i."</td>
			 		<td>".$val[0]."</td>
					<td>".$val[1]."</td>
					<td>".$val[2]."</td>
					<td>".$val[3]."</td>
					<td>".$val[4]."</td>
					<td>".$val[5]."</td>
					<td>".$val[6]."</td>
					<td>".$val[25]."</td>
					<td>".$val[7]."</td>
					<td>".$val[8]."</td>
					<td>".$val[9]."</td>
					<td>".$val[10]."</td>
					<td>".$val[17]."</td>
					<td>".$val[18]."</td>
				  </tr>";
			 	} else if(is_int($key)){
			 		if($val['field'][15] =='Shipment RTO Lock') {
			 			$val['field'][10]= 'Cancelled';
			 		}
			 		echo "<tr>
			 		<td>".$i."</td>
			 		<td>".$val['field'][0]."</td>
				<td>".$val['field'][1]."</td>
				<td>".$val['field'][2]."</td>
				<td>".$val['field'][3]."</td>
				<td>".$val['field'][4]."</td>
				<td>".$val['field'][5]."</td>
				<td>".$val['field'][6]."</td>
				<td>".$val['field'][25]."</td>
				<td>".$val['field'][7]."</td>
				<td>".$val['field'][8]."</td>
				<td>".$val['field'][9]."</td>
				<td>".$val['field'][10]."</td>
				<td>".$val['field'][17]."</td>
				<td>".$val['field'][18]."</td>
			  </tr>";
			 		$i =$i+1;
			 	}	 		
			 }
			 echo"</tr></table>";
				
				if(empty($retValue))
				{
				      echo "Please add valid Username,Password ,AWB and Order_id  in plugin configuration";
				}
				else {
				      echo "<br> AWB Tracked Successfully"; 
				}
			}			
		}
		else
		{   
			echo "AWB is not Tracked";
		}
	}	
}
