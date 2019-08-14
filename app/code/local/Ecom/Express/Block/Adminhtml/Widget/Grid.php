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

class Ecom_Express_Block_Adminhtml_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	/**
	 * 
	 * @see Mage_Adminhtml_Block_Widget_Grid::addColumn()
	 */
    public function addColumn($columnId, $column) {
    	
    	
        if (is_array($column)) {
            $this->_columns[$columnId] = $this->getLayout()->createBlock('ecomexpress/adminhtml_widget_grid_column')
                            ->setData($column)
                            ->setGrid($this);
        }
         else {
            throw new Exception(Mage::helper('adminhtml')->__('Wrong column format'));
        }

        $this->_columns[$columnId]->setId($columnId);
        $this->_lastColumnId = $columnId;
        return $this;
    }
}
