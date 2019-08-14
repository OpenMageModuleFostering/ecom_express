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

/**
 * 
 * Sales_Order_Shipment_View grid
 */
class Ecom_Express_Block_Adminhtml_Sales_Order_Shipment_View extends Mage_Adminhtml_Block_Sales_Order_Shipment_View
{

	public function __construct()
	{
		$this->_objectId    = 'shipment_id';
		$this->_controller  = 'sales_order_shipment';
		$this->_mode        = 'view';
		
		parent::__construct();

		$this->_removeButton('reset');
		$this->_removeButton('delete');
		if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/emails')) {
			$this->_updateButton('save', 'label', Mage::helper('sales')->__('Send Tracking Information'));
			$confirmationMessage = Mage::helper('core')->jsQuoteEscape(
					Mage::helper('sales')->__('Are you sure you want to send Shipment email to customer?')
					);
			$this->_updateButton('save',
					'onclick', "deleteConfirm('" . $confirmationMessage . "', '" . $this->getEmailUrl() . "')"
					);
		}
		
		
		$shipment = Mage::getModel('sales/order_shipment')->load($this->getRequest()->getParam('shipment_id'));
		$current_shipping_method = Mage::getModel('sales/order')
								   ->load($shipment->getData('order_id'))
								   ->getShippingMethod();
		
		
		if ($this->getShipment()->getId()) {
			$this->_addButton('print', array(
					'label'     => Mage::helper('sales')->__('Print'),
					'class'     => 'save',
					'onclick'   => 'setLocation(\''.$this->getPrintUrl().'\')'
			)
					);
			
			if(strpos($current_shipping_method, 'ecomexpress') !== false)
			{
				$url =$this->getUrl('*/adminhtml_track/trackingAWB/',array('shipment_ids'=>$this->getShipment()->getId()));
			
				$this->_addButton('ecom_shipping_label', array(
						'label'     => Mage::helper('sales')->__('Ecom Shipping Label'),
						'class'     => 'save',
						'onclick'   => 'setLocation(\''.$this->getPrintUrl().'\')'
				)
						);
				
				
			$this->_addButton('track_order', array(
					'label'     => Mage::helper('sales')->__('Track Order'),
					'class'     => 'save',
					'onclick'    =>'javascript:popup(\''.$url.'\')' 
					
			)
					);
			$this->_addButton('cancel_order', array(
					'label'     => Mage::helper('sales')->__('Cancel Order'),
					'class'     => 'save',
					'onclick'   => 'setLocation(\''. $this->getUrl('*/adminhtml_cancel/cancelAWB/',array('shipment_ids'=>$this->getShipment()->getId())).'\')'
								
			)
					);
			}
		}
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

	public function getHeaderText()
	{
		if ($this->getShipment()->getEmailSent()) {
			$emailSent = Mage::helper('sales')->__('the shipment email was sent');
		}
		else {
			$emailSent = Mage::helper('sales')->__('the shipment email is not sent');
		}
		return Mage::helper('sales')->__('Shipment #%1$s | %3$s (%2$s)', $this->getShipment()->getIncrementId(), $emailSent, $this->formatDate($this->getShipment()->getCreatedAtDate(), 'medium', true));
	}

	public function getBackUrl()
	{
		return $this->getUrl(
				'*/sales_order/view',
				array(
						'order_id'  => $this->getShipment()->getOrderId(),
						'active_tab'=> 'order_shipments'
				));
	}

	public function getEmailUrl()
	{
		return $this->getUrl('*/sales_order_shipment/email', array('shipment_id'  => $this->getShipment()->getId()));
	}

	
	public function getPrintUrl()
	{
		return $this->getUrl('*/*/print', array(
				'invoice_id' => $this->getShipment()->getId()
		));
	}

	public function getPrintshipUrl()
	{
		return $this->getUrl('*/*/print', array(
				'invoice_id' => $this->getShipment()->getId()
		));
	}
	
	public function updateBackButtonUrl($flag)
	{
		if ($flag) {
			if ($this->getShipment()->getBackUrl()) {
				return $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->getShipment()->getBackUrl()
						. '\')');
			}
			return $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->getUrl('*/sales_shipment/') . '\')');
		}
		return $this;
	}
}