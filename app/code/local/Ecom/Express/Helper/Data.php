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



class Ecom_Express_Helper_Data extends Mage_Core_Helper_Abstract {
    
	/*
	 * Function to execute curl
	 * @return API response
	 */

	 public function executeCurl($url,$type,$params)
	{
		try {
		     
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_FAILONERROR,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);
				
			if($type == 'post'):
				
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	
			endif;
	
			$retValue = curl_exec($ch);
			$info_code = curl_getinfo($ch);
		 
	
			if (!curl_errno($ch))
			{   
				switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
					case 200:
						if(!strcmp($retValue,"Unauthorised Request"))
						{ 
							
							$myMsg ="Unauthorised username and Password";
							Mage::getSingleton('core/session')->setMyMsg($myMsg);
						}
						if(!strcmp($retValue,"You are not authorised!"))
						{
							$myMsg ="Unauthorised Username and Password";
							Mage::getSingleton('core/session')->setMyMsg($myMsg);
						}
						if(!$retValue)
						{
							return false;
						}
						curl_close($ch);
						return $retValue;
						break;
					default:
						if($errno = curl_errno($ch)) {
							$error_message = curl_strerror($errno);
							break;				
						}
						$infoMsg="Ecom service is currently Unavilable , please try after Sometime";
						Mage::getSingleton(‘core/session’)->setInfoMsg($myMsg);
				}
			}
			
		}
		catch(Exception $e)
		{    
			return	$e->getMessage();
		}
	}
	 
}
