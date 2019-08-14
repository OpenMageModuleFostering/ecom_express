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
 * Sales Order Shipment PDF model
 *
 */
class Ecom_Express_Model_Order_Pdf_Shipment extends Mage_Sales_Model_Order_Pdf_Abstract
{
    /**
     * Draw table header for product items
     *
     * @param  Zend_Pdf_Page $page
     * @return void
     */
	
	public $y;
	public $ax1=40;
	public $zshipments=0;
	
	
    protected function _drawHeader(Zend_Pdf_Page $page)
    {	
    	
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y-15);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));        
        $invoice_id = Mage::app()->getRequest()->getParam('invoice_id');
        $shipment = Mage::getModel('sales/order_shipment')->load($invoice_id);
   
        $my_order = Mage::getModel('ecomexpress/awb')->getCollection()->addFieldToFilter('orderid',$shipment->getData('order_id'))->getData();
  
        foreach($my_order as $order_data)
        {
      	  $waybill_no	= $order_data['awb'];
        }
         
        $admin_address= $this->_formatAddress(Mage::getStoreConfig('shipping/origin/street_line1').",".Mage::getStoreConfig('shipping/origin/street_line2'));
		
        
        if(strlen(Mage::getStoreConfig('shipping/origin/street_line1').",".Mage::getStoreConfig('shipping/origin/street_line2'))< 30){
         	$i=0;
        	$page->drawText(Mage::helper('sales')->__(Mage::getStoreConfig('shipping/origin/street_line1').",".Mage::getStoreConfig('shipping/origin/street_line2')),410, ($this->y+310), 'UTF-8');
       
        }
        else{
        	$line = 822;
        	$i=20;
        	foreach ($admin_address as $value){
        		 
        		$textChunk = wordwrap(trim($value), 45, "\n");
        		foreach(explode("\n", $textChunk) as $textLine){
        			if ($textLine!=='') {
        				$page->drawText(strip_tags(ltrim($textLine)), 340, $line, 'UTF-8');
        				$line -=10;
        			}
        		}$i++;
        	}
        }       
        $page->drawText(Mage::helper('sales')->__('Email: '.Mage::getStoreConfig('trans_email/ident_general/email')), 410, $this->y+284-$i, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Phone: '.Mage::getStoreConfig('general/store_information/phone')), 410, $this->y+273-$i, 'UTF-8');
                       
        $this->_setFontBold($page, 15);
        $page->drawText(Mage::helper('sales')->__('ECOM EXPRESS'), 240, $this->y+265, 'UTF-8');
        
        
        $fontPath = Mage::getBaseDir() . '/media/ecomexpress/font/FRE3OF9X.TTF';
        $page->setFont(Zend_Pdf_Font::fontWithPath($fontPath), 30);
        $barcodeImage = "*".trim($waybill_no)."*";
        $page->drawText($barcodeImage, 230,$this->y+230);
        
        
        $this->_setFontRegular($page, 10);
        $page->drawText(Mage::helper('sales')->__($waybill_no), 255, $this->y+218, 'UTF-8');
        $this->_setFontBold($page, 12);
        $page->drawText(Mage::helper('sales')->__('Dimension:'), 40, $this->y+210, 'UTF-8');
        $this->_setFontRegular($page, 10);
        $this->_setFontBold($page, 12);
        $page->drawText(Mage::helper('sales')->__('Actual Weight:'), 440, $this->y+210, 'UTF-8');
        $this->_setFontRegular($page, 10);
        $this->_setFontBold($page, 12);
        $page->drawText(Mage::helper('sales')->__('SHIPPER:'), 200, $this->y+190, 'UTF-8');
        $this->_setFontRegular($page, 10);
        $page->drawText(Mage::helper('sales')->__(Mage::getStoreConfig('general/store_information/name')), 250, $this->y+190, 'UTF-8');
       
        $this->_setFontBold($page, 12);
        $page->drawText(Mage::helper('sales')->__('CONSIGNEE ADDRESS:'), 40, $this->y+170, 'UTF-8');
        $this->_setFontRegular($page, 10);
   
        
        $current_shipping_method = Mage::getModel('sales/order')
                                      ->load($shipment->getData('order_id'))
                                      ->getShippingMethod();
         
        //columns headers
        
        if(strpos($current_shipping_method, 'ecomexpress') !== false)
        {
        
        	$lines[0][] = array(
        			'text'  => Mage::helper('sales')->__('Order number'),
        			'feed'  => 110,
        			'align' => 'right'
        	);
        
        	$lines[0][] = array(
        			'text'  => Mage::helper('sales')->__('Order Date'),
        			'feed'  => 190,
        			'align' => 'right'
        	);
        	$lines[0][] = array( 
        			'text'  => Mage::helper('sales')->__('Product Type'),
        			'feed'  => 320,
        			'align' => 'right'
        	);
        
        	$lines[0][] = array(
        			'text'  => Mage::helper('sales')->__('Quantity'),
        			'feed'  => 450,
        			'align' => 'right'
        	);
        	$lines[0][] = array(
        			'text'  => Mage::helper('sales')->__('value'),
        			'feed'  => 560,
        			'align' => 'right'
        	);
        }else {   
        	$lines[0][] = array(
        			'text' => Mage::helper('sales')->__('Products'),
        			'feed' => 100,
        	);
        	
        	$lines[0][] = array(
        			'text'  => Mage::helper('sales')->__('Qty'),
        			'feed'  => 35
        	);
        	
        	$lines[0][] = array(
        			'text'  => Mage::helper('sales')->__('SKU'),
        			'feed'  => 565,
        			'align' => 'right'
        	);
        }
        
        $lineBlock = array(
            'lines'  => $lines,
            'height' => 10
        );

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    /**
     * Return PDF document
     *
     * @param  array $shipments
     * @return Zend_Pdf
     */
    
    public function getPdf($shipments = array())
    {
    	
    	$invoice_id = Mage::app()->getRequest()->getParam('invoice_id');
    	$shipment = Mage::getModel('sales/order_shipment')->load($invoice_id);
    	 
    	$current_shipping_method = Mage::getModel('sales/order')
                                	->load($shipment->getData('order_id'))
                                	->getShippingMethod();
    	
    	if(strcmp($current_shipping_method, 'ecomexpress_ecomexpress') == true)
    	{
    		return Mage::getModel('sales/order_pdf_Shipment')->getPdf($shipments);
    	}
        	
        $this->_beforeGetPdf();
        $this->_initRenderer('shipment');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
        foreach ($shipments as $shipment) {
            if ($shipment->getStoreId()) {
                Mage::app()->getLocale()->emulate($shipment->getStoreId());
                Mage::app()->setCurrentStore($shipment->getStoreId());
            }
            $page  = $this->newPage();
            $order = $shipment->getOrder();
            $pdf_logo_name = Mage::getStoreConfig('carriers/ecomexpress/logo_image');
            $pdf_logo_url  = Mage::getBaseUrl('media').'ecomexpress-logo-images/' .$pdf_logo_name;
            
            /* Add image */
            
            $this->insertEcomLogo($page, $shipment->getStore());

            /* Add address */
            $this->insertEcomAddress($page, $shipment->getStore());
            /* Add head */
            $this->insertEcomOrder(
                $page,
                $shipment,
            		
                Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_SHIPMENT_PUT_ORDER_ID, $order->getStoreId())
            );
    
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($shipment->getAllItems() as $item) {
            	if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                $cartitem=array();
                foreach($shipment->getAllItems() as $item){
                	$cartitem[]=$item->getData();
                } 
                                
                $yShipments = $this->y;
                $topMargin = 15;
                $yShipments -= $topMargin-10;
                $subtotal = 0;
                $weight = 0;
                $prod_length =array();
                $prod_width =array();
                $prod_height =array();
                
                $length_yship = array();
	              foreach($cartitem as $key=>$value){
	              	
	              	$weight += ($value['weight'] * $value['qty']) ;
	              	
	                $package_collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
	               							->addAttributeToFilter('ecom_length', array('notnull' => true))
	              							->addAttributeToFilter('ecom_height',array('notnull' => true))
	              							->addAttributeToFilter('ecom_width',array('notnull' => true))
	              							->addAttributeToFilter('sku', array('in' => $value['sku']));
	              	
	                	foreach($package_collection as  $product_data) { 
			              		$prod_length['length'][] = $product_data['ecom_length'];
			              		$prod_width['width'][]= $product_data['ecom_width'];
			              		$prod_height['height'][] = $product_data['ecom_height'];
	              			} 
	               	$page->drawText($order->getRealOrderId(), 41, $yShipments, 'UTF-8');
	               	
	               	
	               	$date = Mage::helper('core')->formatDate($order->getCreatedAt());
	               	$page->drawText(date("d.m.Y", strtotime($date)), 140, $yShipments, 'UTF-8');
	               	
	               	
	               	if(strlen($value['name']) > 15){
	               		
	               		$productName = substr($value['name'],0,15).' ...';
	               	}else{
	               		$productName = $value['name'];
	               	}
	               	
	               	$text = array();
	               	foreach (Mage::helper('core/string')->str_split($productName, 25, true, true) as $_value){
	               		$text[] = $_value;
	               	}
	               	$addressy = $yShipments;
	               	foreach ($text as $part){
	               		$page->drawText(strip_tags(ltrim($part)), 230, $addressy , 'UTF-8');
	               		$addressy -= 10;
	               	} 
	               	
	     	       	$page->drawText((int)$value["qty"], 410, $yShipments, 'UTF-8');
	               	$paymentInfo = Mage::helper('payment')->getInfoBlock($order->getPayment())
					               	->setIsSecureMode(true)
					               	->toPdf();
	               	$paymentInfo = htmlspecialchars_decode($paymentInfo, ENT_QUOTES);
	               	$payment = explode('{{pdf_row_separator}}', $paymentInfo);
	               		               		
               		$grand_total= $order->getTaxAmount() + $order->getGrandTotal();
               		$page->drawText(Mage::helper('sales')->__('₹') .$value["price"] * $value["qty"], 535, ($yShipments), 'UTF-8');
	          
	              	$subtotal+=$value["price"]*$value["qty"];
	              	$page->drawLine(120,  $yShipments + 45, 120,  $yShipments+30); //left
	              	$page->drawLine(220,  $yShipments + 45, 220,  $yShipments+30); //left
	              	$page->drawLine(360,  $yShipments + 45, 360,  $yShipments+30); //left
	              	$page->drawLine(490,  $yShipments + 45, 490,  $yShipments+30); //left
	              	
	              	$page->drawLine(25,  $yShipments-10, 25,  $yShipments+30);   //left
	              	$page->drawLine(120,  $yShipments-10, 120,  $yShipments+30); //left
	              	$page->drawLine(220,  $yShipments-10, 220,  $yShipments+30); //left
	              	$page->drawLine(360,  $yShipments-10, 360,  $yShipments+30); //left
	              	$page->drawLine(490,  $yShipments-10, 490,  $yShipments+30); //left
	              	$page->drawLine(570,  $yShipments-10, 570,  $yShipments+30); //left
	              	$page->drawLine(25,  $yShipments-10,     570, $yShipments-10); //bottom
	             
	              	$yShipments -= $topMargin + 10; 	
	              	
	              }
           	  
              $page->drawText(($weight), 520, $this->y+240, 'UTF-8');
              $page->drawText((max($prod_length['length'])."*".array_sum($prod_width['width'])."*".max($prod_height['height'])), 105, $this->y+240, 'UTF-8');
               if($order->getShippingAmount())
              	$page->drawText(Mage::helper('sales')->__('Shipping & Handling Charge :    ₹') .round($order->getShippingAmount(),2),190, $yShipments-45, 'UTF-8');
              if(strcmp($payment[0] ,'Cash On Delivery')==0){
              	$grand_total= $order->getTaxAmount() + $order->getGrandTotal();
              	$this->_setFontBold($page, 12);
              	$page->drawText(Mage::helper('sales')->__('COLLECT ONLY') ." (".strtoupper($payment[0]).") : ",190, ($yShipments-60), 'UTF-8');
              	$this->_setFontRegular($page, 10);
              	$page->drawText(Mage::helper('sales')->__('₹').$grand_total,405, ($yShipments-60), 'UTF-8');
              	$length_yship[] =$yShipments;
              }
              else
              {
              	$this->_setFontBold($page, 12);
              	$page->drawText(Mage::helper('sales')->__('PRE - PAID'),210, ($yShipments-70), 'UTF-8');
              	$this->_setFontRegular($page, 10);
              	$length_yship[] =$yShipments;
              
              }
              $this->_setFontBold($page, 12);
              $page->drawText(Mage::helper('sales')->__('IF UNDELIVERD RETURN TO'),35, ($yShipments-85), 'UTF-8');
              $this->_setFontRegular($page, 10);
              $page->drawText(Mage::helper('sales')->__(Mage::getStoreConfig('general/store_information/name')),35, ($yShipments-105), 'UTF-8');
              
              $admin_add = $this->_formatAddress(Mage::getStoreConfig('shipping/origin/street_line1').",".Mage::getStoreConfig('shipping/origin/street_line2').",".Mage::getStoreConfig('shipping/origin/city').
             					  ",".Mage::getStoreConfig('shipping/origin/region_id').",".Mage::getStoreConfig('shipping/origin/postcode').
              					  ",".Mage::helper('sales')->__(Mage::app()->getLocale()->getCountryTranslation(Mage::getStoreConfig('shipping/origin/country_id'))));
              
              $newline=$yShipments-125;
              foreach ($admin_add as $add_value){
              	 
              	$add_textChunk = wordwrap($add_value, 45, "\n");
              	foreach(explode("\n", $add_textChunk) as $add_textLine){
              		if ($add_textLine!=='') {
              			$page->drawText(strip_tags(ltrim($add_textLine)), 35, $newline, 'UTF-8');
              			$newline -=14;
              		}
              	}
              }
     
             $page = end($pdf->pages);
              	
            } 
            
        }
        $this->zShipments -= 15;
        $yShipments -= 15;
        $this->_afterGetPdf();
        if ($shipment->getStoreId()) {
            Mage::app()->getLocale()->revert();
        }
        return $pdf;
    }

    /**
     * Create new page and assign to PDF object
     *
     * @param  array $settings
     * @return Zend_Pdf_Page
     */
    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_A4);
        $this->_getPdf()->pages[] = $page;
        $this->y = 800;
        if (!empty($settings['table_header'])) {
            $this->_drawHeader($page);
        }
        return $page;
    }
    
    
    
    /**
     * Set font as regular
     *
     * @param  Zend_Pdf_Page $object
     * @param  int $size
     * @return Zend_Pdf_Resource_Font
     */
      protected function _setFontRegular($object, $size = 7)
    {
    	$font = Zend_Pdf_Font::fontWithPath(Mage::getBaseDir() . '/lib/LinLibertineFont/LinLibertine_Re-4.4.1.ttf');
    	$font = Zend_Pdf_Font::fontWithPath(Mage::getBaseDir() . '/lib/LinLibertineFont/ecomexpress/DejaVuSans.ttf');
    	$object->setFont($font, $size);
    	return $font;
    }  
    
    
    
    /**
     * Insert logo to pdf page
     *
     * @param Zend_Pdf_Page $page
     * @param null $store
     */
    
    protected function insertEcomLogo(&$page, $store = null){
    	$this->y = $this->y ? $this->y : 815;
    	$image = Mage::getStoreConfig('carriers/ecomexpress/logo_image', $store);
    	    	
    	if ($image) {
    		
    		$image = Mage::getBaseDir('media') . '/ecomexpress-logo-images/' . $image;
   
    		if (is_file($image)) {
    			$image       = Zend_Pdf_Image::imageWithPath($image);
    			$top         = 830; //top border of the page
    			$widthLimit  = 270; //half of the page width
    			$heightLimit = 270; //assuming the image is not a "skyscraper"
    			$width       = $image->getPixelWidth();
    			$height      = $image->getPixelHeight();
    	
    			//preserving aspect ratio (proportions)
    			$ratio = $width / $height;
    			if ($ratio > 1 && $width > $widthLimit) {
    				$width  = $widthLimit;
    				$height = $width / $ratio;
    			} elseif ($ratio < 1 && $height > $heightLimit) {
    				$height = $heightLimit;
    				$width  = $height * $ratio;
    			} elseif ($ratio == 1 && $height > $heightLimit) {
    				$height = $heightLimit;
    				$width  = $widthLimit;
    			}
    	
    			$y1 = $top - $height;
    			$y2 = $top;
    			$x1 = 25;
    			$x2 = $x1 + $width;
    	
    			//coordinates after transformation are rounded by Zend
    			$page->drawImage($image, $x1, $y1, $x2, $y2);
    	
    			$this->y = $y1 - 10;
    		}
    	}
    }
    
    
    /**
     * Insert address to pdf page
     *
     * @param Zend_Pdf_Page $page
     * @param null $store
     */
    protected function insertEcomAddress(&$page, $store = null)
    {
    	$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
    	$font = $this->_setFontRegular($page, 10);
    	$page->setLineWidth(0);
    	$this->y = $this->y ? $this->y : 815;
    	$top = 815;
    	
    	foreach (explode("\n", Mage::getStoreConfig('sales/identity/address', $store)) as $value){
    		if ($value !== '') {
    			$value = preg_replace('/<br[^>]*>/i', "\n", $value);
    			foreach (Mage::helper('core/string')->str_split($value, 45, true, true) as $_value) {
    				$page->drawText(trim(strip_tags($_value)),
    						$this->getAlignRight($_value, 130, 440, $font, 10),
    						$top,
    						'UTF-8');
    						$top -= 10;
    			}
    		}
    	}
    	
    	$this->y = ($this->y > $top) ? $top : $this->y;
    }
    
    /**
     * Format address
     *
     * @param  string $address
     * @return array
     */
    protected function _formatAddress($address)
    {
    	$return = array();
    	foreach (explode('|', $address) as $str) {
    		foreach (Mage::helper('core/string')->str_split($str, 45, true, true) as $part) {
    			if (empty($part)) {
    				continue;
    			}
    			$return[] = $part;
    		}
    	}
    	return $return;
    }
    
    /**
     * Calculate address height
     *
     * @param  array $address
     * @return int Height
     */
    protected function _calcAddressHeight($address)
    {
    	$y = 0;
    	foreach ($address as $value){
    		if ($value !== '') {
    			$text = array();
    			foreach (Mage::helper('core/string')->str_split($value, 55, true, true) as $_value) {
    				$text[] = $_value;
    			}
    			foreach ($text as $part) {
    				$y += 15;
    			}
    		}
    	}
    	return $y;
    }
    
    /**
     * Insert order to pdf page
     *
     * @param Zend_Pdf_Page $page
     * @param Mage_Sales_Model_Order $obj
     * @param bool $putOrderId
     */
    protected function insertEcomOrder(&$page, $obj, $putOrderId = true)
    {
    	$yShipments =60; 
    	if ($obj instanceof Mage_Sales_Model_Order) {
    		$shipment = null;
    		$order = $obj;
    	} elseif ($obj instanceof Mage_Sales_Model_Order_Shipment) {
    		$shipment = $obj;
    		$order = $shipment->getOrder();
    	}
    	$this->y = $this->y ? $this->y : 815;
    	$top = $this->y;
    	$margin = $this->ax1 =100;
    	$page->setFillColor(new Zend_Pdf_Color_GrayScale(0.45));
    	$page->setLineColor(new Zend_Pdf_Color_GrayScale(0.45));
    	$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
    	$this->setDocHeaderCoordinates(array(25, $top, 570, $top - 55));
    	$this->_setFontRegular($page, 10);
    	$page->drawText(Mage::helper('sales')->__() , 35, ($top -= 30), 'UTF-8');
    	$page->drawText(Mage::helper('sales')->__(),35,($top -= 15),'UTF-8');
    	$top -= 10;
    	$page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
    	$page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
    	$page->setLineWidth(0.5);
  
    	/* Calculate blocks info */
    
    	/* Billing Address */
    	
    	$billingAddress = $this->_formatAddress($order->getBillingAddress()->format('pdf'));
  
    	/* Payment */
    	$paymentInfo = Mage::helper('payment')->getInfoBlock($order->getPayment())
    	->setIsSecureMode(true)
    	->toPdf();
    	$paymentInfo = htmlspecialchars_decode($paymentInfo, ENT_QUOTES);
    	$payment = explode('{{pdf_row_separator}}', $paymentInfo);
    	
    	foreach ($payment as $key=>$value){
    		if (strip_tags(trim($value)) == '') {
    			unset($payment[$key]);
    		}
    	}
    	reset($payment);
    
    	/* Shipping Address and Method */
    	if (!$order->getIsVirtual()) {
    		/* Shipping Address */
    		$shippingAddress = $this->_formatAddress($order->getShippingAddress()->format('pdf'));
    		$shippingMethod  = $order->getShippingDescription();
    	}
    
    	$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
  
    	$addressesHeight = $this->_calcAddressHeight($billingAddress);
    	if (isset($shippingAddress)) {
    		$addressesHeight = max($addressesHeight, $this->_calcAddressHeight($shippingAddress));
    	}
    	$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
    	$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
    	$this->_setFontRegular($page, 10);
    	$this->y = $top - 40;
    	$addressesStartY = $this->y;
    	$this->_setFontBold($page, 12);
    	$this->_setFontRegular($page, 10);
    	$yShipments +=55;
  
    
    	$addressesEndY = $this->y;
    	if (!$order->getIsVirtual()) {
    		
    		$this->y = $addressesStartY;
    		$shippingAddress = $order->getShippingAddress()->getData();
    		    $this->_setFontBold($page, 12);
      		  	$page->drawText(Mage::helper('sales')->__('MOBILE NO:'),40, $this->y-60, 'UTF-8');
      		  	$this->_setFontRegular($page, 10);
      		  	$page->drawText(Mage::helper('sales')->__($shippingAddress['telephone']),110, $this->y-60, 'UTF-8');
     		  	$this->y -= 15;
     		  	if(isset($shippingAddress['middlename'])){
     		  		$page->drawText($shippingAddress['firstname']." ".$shippingAddress['middlename']." ".$shippingAddress['lastname'], 40, $this->y-65, 'UTF-8');
     		  	}
     		  	else{
     		  		$page->drawText($shippingAddress['firstname']." ".$shippingAddress['lastname'], 40, $this->y-65, 'UTF-8');
     		  	}
     		  	$this->y -= 25;
     		  	$page->drawText(preg_replace('/\s+/', ' ', $shippingAddress['street']. " " .trim($shippingAddress['city'])) , $this->ax1-60 , $this->y-65, 'UTF-8');
     		  	     		  	
     		  	$this->y -= 25;
     		  	$this->_setFontBold($page, 12);
     		  	$page->drawText(Mage::helper('sales')->__('Destination: ' ),$this->ax1-60, $this->y-60, 'UTF-8');
     		  	$this->_setFontRegular($page, 10);
     		  	$page->drawText(Mage::helper('sales')->__($shippingAddress['city'].",".$shippingAddress['region'].",".$shippingAddress['postcode']),$this->ax1+5, $this->y-60, 'UTF-8');
     		  	$this->_setFontBold($page, 15);
     		  	$page->drawText(Mage::helper('sales')->__('ORDER DETAILS:'),260, $this->y-100, 'UTF-8');
     		  	$this->_setFontRegular($page, 10);
    	
    		$addressesEndY = min($addressesEndY, $this->y);
    		$this->y = $addressesEndY;
    		$page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
    		$page->setLineWidth(0.5);
    	
    		$this->y -= 15;
    		$this->_setFontBold($page, 12);
    		$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
    
    		$this->y -=10;
    		$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
    		$this->_setFontRegular($page, 10);
    		$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
    		$paymentLeft = 35;
    		$yPayments   = $this->y - 15;
    	}
    	else {
    		$yPayments   = $addressesStartY;
    		$paymentLeft = 285;
    	}
    
    	foreach ($payment as $value){
    		if (trim($value) != '') {
    			//Printing "Payment Method" lines
    			$value = preg_replace('/<br[^>]*>/i', "\n", $value);
    			
    			foreach (Mage::helper('core/string')->str_split($value, 45, true, true) as $_value) {
    				$yPayments -= 15;
    			}
    		}
    	}
    
    	if ($order->getIsVirtual()) {
    		// replacement of Shipments-Payments rectangle block
    		$yPayments = min($addressesEndY, $yPayments);
    		$page->drawLine(25,  ($top - 25), 25,  $yPayments);
    		$page->drawLine(570, ($top - 25), 570, $yPayments);
    		$page->drawLine(25,  $yPayments,  570, $yPayments);
    
    		$this->y = $yPayments - 15;
    	} else {
    		$topMargin    = 15;
    		$methodStartY = $this->y;
    		$this->y     -= 15;
    
    		foreach (Mage::helper('core/string')->str_split($shippingMethod, 45, true, true) as $_value) {
    			$this->y -= 15;
    		}
    
    		$yShipments = $this->y;
    				$yShipments -= $topMargin + 10;
    				$tracks = array();
    				if ($shipment) {
    					$tracks = $shipment->getAllTracks();
    				}
    				if (count($tracks)) {
    					$page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
    					$page->setLineWidth(0.5);
    			
    					$this->_setFontRegular($page, 9);
    					$yShipments -= 20;
    					$this->_setFontRegular($page, 8);
    					foreach ($tracks as $track) {
    
    						$CarrierCode = $track->getCarrierCode();
    						if ($CarrierCode != 'custom') {
    							$carrier = Mage::getSingleton('shipping/config')->getCarrierInstance($CarrierCode);
    							$carrierTitle = $carrier->getConfigData('title');
    						} else {
    							$carrierTitle = Mage::helper('sales')->__('Custom Value');
    						}
    						$maxTitleLen = 45;
    						$endOfTitle = strlen($track->getTitle()) > $maxTitleLen ? '...' : '';
    						$truncatedTitle = substr($track->getTitle(), 0, $maxTitleLen) . $endOfTitle;
    			
    						$yShipments -= $topMargin - 5;
    					}
    				} else {
    					$yShipments -= $topMargin - 5;
    				}
    				$currentY = min($yPayments, $yShipments);
    				$this->y = $currentY;
    				$this->y -= 15;
    	}
    	$yShipments -=20;
    }
    
    /**
     * Insert title and number for concrete document type
     *
     * @param  Zend_Pdf_Page $page
     * @param  string $text
     * @return void
     */
    public function insertEcomDocumentNumber(Zend_Pdf_Page $page, $text)
    {
    	$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
    	$this->_setFontRegular($page, 10);
    	$docHeader = $this->getDocHeaderCoordinates();
    	$page->drawText($text, 35, $docHeader[1] - 15, 'UTF-8');
    }
    
    /**
     * Sort totals list
     *
     * @param  array $a
     * @param  array $b
     * @return int
     */
    protected function _sortTotalsList($a, $b) {
    	if (!isset($a['sort_order']) || !isset($b['sort_order'])) {
    		return 0;
    	}
    
    	if ($a['sort_order'] == $b['sort_order']) {
    		return 0;
    	}
    
    	return ($a['sort_order'] > $b['sort_order']) ? 1 : -1;
    }
}

