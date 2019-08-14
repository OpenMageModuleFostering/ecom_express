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

class Ecom_Express_IndexController extends Mage_Core_Controller_Front_Action {
	
	public function printAction()
	{
		die('index');
		/** @see Mage_Adminhtml_Sales_Order_InvoiceController */
		if ($shipmentId = $this->getRequest()->getParam('invoice_id')) { // invoice_id o_0
			if ($shipment = Mage::getModel('sales/order_shipment')->load($shipmentId)) {
				$pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf(array($shipment));
				$this->_prepareDownloadResponse('packingslip'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
			}
		}
		else {
			$this->_forward('noRoute');
		}
	}
	
	
	
}