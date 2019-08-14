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


class Ecom_Express_Block_Adminhtml_Addtrack extends Mage_Core_Block_Template {
   
  public function _prepareLayout() 
  {
      $head = $this->getLayout()->getBlock('head');
      $head->addJs('ecom/jquery-2.1.1.min.js');

      return parent::_prepareLayout();
  }

}