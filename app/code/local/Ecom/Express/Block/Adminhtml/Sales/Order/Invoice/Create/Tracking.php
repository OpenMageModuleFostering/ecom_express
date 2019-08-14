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

class Ecom_Express_Block_Adminhtml_Sales_Order_Invoice_Create_Tracking extends Mage_Adminhtml_Block_Sales_Order_Invoice_Create_Tracking
{
	public function _construct()
	{
		
		$this->setTemplate('ecomexpress/sales/order/tracking.phtml');
		
	}
	
	
	/**
	 * Prepares layout of block
	 *
	 * @return Mage_Adminhtml_Block_Sales_Order_View_Giftmessage
	 */
	protected function _prepareLayout()
	{
		$this->setChild('add_button',
				$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
						'label'   => Mage::helper('sales')->__('Add Tracking Number'),
						'class'   => '',
						'onclick' => 'trackingControl.add()'
				))
		);
	}

	/**
	 * Retrieve shipment model instance
	 *
	 * @return Mage_Sales_Model_Order_Shipment
	 */
	public function getShipment()
	{
		return Mage::registry('current_shipment');
	}

	/**
	 * Retrieve shipment model instance
	 *
	 * @return Mage_Sales_Model_Order_Shipment
	 */
	public function getInvoice()
	{
		return Mage::registry('current_invoice');
	}

	/**
	 * Retrieve
	 *
	 * @return unknown
	 */
	public function getCarriers()
	{

		$carriers = array();
		$carrierInstances = Mage::getSingleton('shipping/config')->getAllCarriers(
				$this->getInvoice()->getStoreId()
		);
		$carriers['custom'] = Mage::helper('sales')->__('Custom Value');
		foreach ($carrierInstances as $code => $carrier) {
			if ($carrier->isTrackingAvailable()) {
				$carriers[$code] = $carrier->getConfigData('title');
			}
		}
		return $carriers;
	}
}
