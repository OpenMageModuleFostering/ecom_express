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

class Ecom_Express_Block_Adminhtml_Widget_Grid_Column extends Mage_Adminhtml_Block_Widget_Grid_Column {
/**
 * 
 * 
 * @see Mage_Adminhtml_Block_Widget_Grid_Column::_getRendererByType()
 */
    protected function _getRendererByType() {
        switch (strtolower($this->getType())) {
            case 'awb':
                $rendererClass = 'ecomexpress/adminhtml_widget_grid_column_renderer_awb';
                break;
            default:
                $rendererClass = parent::_getRendererByType();
                break;
        }
        return $rendererClass;
    }

    /**
     * 
     * 
     * @see Mage_Adminhtml_Block_Widget_Grid_Column::_getFilterByType()
     */
    protected function _getFilterByType() {
        switch (strtolower($this->getType())) {
            case 'awb':
                $filterClass = 'ecomexpress/adminhtml_widget_grid_column_filter_awb';
                break;
            default:
                $filterClass = parent::_getFilterByType();
                break;
        }
        return $filterClass;
    }

}