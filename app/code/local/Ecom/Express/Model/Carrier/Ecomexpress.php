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

class Ecom_Express_Model_Carrier_Ecomexpress extends Mage_Shipping_Model_Carrier_Abstract
implements Mage_Shipping_Model_Carrier_Interface
{
	
	protected $_code = 'ecomexpress';
	protected $price = 0;

	/**
	 * Bluedart Shipping Rates Collector
	 *
	 * @param Mage_Shipping_Model_Rate_Request $request
	 * @return Mage_Shipping_Model_Rate_Result
	 */
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{ 
		$price = 0;
		if (!Mage::getStoreConfig('carriers/ecomexpress/active')) {
				
  			return false;
		}
		$result = Mage::getModel('shipping/rate_result');
		//$quote = Mage::getSingleton('checkout/session')->getQuote();		
		//$shippingAddress = $quote->getShippingAddress();
		$to_country = $request->getCountryId(); 
		//$to_city = $shippingAddress->getCity();
		$to_zipcode = $request->getDestPostcode();
		$allowed_country = array();
		$allowed_country = explode(',',Mage::getStoreConfig('carriers/ecomexpress/specificcountry'));
		if(!Mage::getStoreConfig('carriers/ecomexpress/sallowspecific')){
			$allowed_countries = Mage::getModel('directory/country')->getResourceCollection()->loadByStore()->toOptionArray(false);
			foreach($allowed_countries as $allowed_countrys)
				$allowed_country[] = $allowed_countrys['value'];
		}
//print_r();die('--');
		if($to_zipcode || ctype_digit($to_zipcode))
			$model = Mage::getModel('ecomexpress/pincode')->load($to_zipcode,'pincode');
		//print_r($model);die;
		
		if(count($model->getData())>0 && in_array($to_country,$allowed_country)== true)
		{ 
			
			if(Mage::getStoreConfig('carriers/ecomexpress/handling') == true){
				$price = Mage::getStoreConfig("carriers/ecomexpress/handling_charge");
			}
			$method = Mage::getModel('shipping/rate_result_method');
			$method->setCarrier($this->_code);
			$method->setCarrierTitle($this->_code);
			$method->setMethod($this->_code);
			$method->setMethodTitle(Mage::getStoreConfig('carriers/ecomexpress/name'));
			$method->setPrice($price);
			$method->setCost($price);
			$result->append($method);
		}
		else{
			$error = Mage::getModel('shipping/rate_result_error');
			$error->setCarrier($this->_code);
			$error->setCarrierTitle($this->_code);
			$error->setErrorMessage(Mage::getStoreConfig('carriers/ecomexpress/specificerrmsg'));
			$result->append($error);
		} 
					
		return $result;
	}

	/**
	 * Get Allowed Methods
	 *
	 * @return array
	 */
	public function getAllowedMethods()
	{
		return array('ecomexpress'=>$this->getConfigData('title'));
	}
	
	/**
	 * show Allowed Methods	 
	 */
	public function isTrackingAvailable(){
	
		return true;
	}
}
