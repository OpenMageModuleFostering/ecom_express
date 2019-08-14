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


class Ecom_Express_Block_Adminhtml_Pincode_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	/**
	 * 
	 * setting grid parameter
	 * @return void
	 */
    public function __construct() {
    	
    	
        parent::__construct();
        $this->setId('pincodeGrid');
        $this->setDefaultSort('pincode_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * 
     * preparing collection and setting collection to grid
     * @return Ecom_Express_Block_Adminhtml_Pincode_Grid
     */
    protected function _prepareCollection() {
       
        $collection = Mage::getModel('ecomexpress/pincode')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     *
     * preparing column of grid
     * @return Ecom_Express_Block_Adminhtml_Pincode_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('pincode_id', array(
            'header' => Mage::helper('ecomexpress')->__('Pincode ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'pincode_id',
        ));

        $this->addColumn('pincode', array(
            'header' => Mage::helper('ecomexpress')->__('Pincode'),
            'index' => 'pincode',
        ));
        
        $this->addColumn('city', array(
            'header' => Mage::helper('ecomexpress')->__('City'),
            'width' => '100px',
            'index' => 'city',
        ));
        $this->addColumn('state', array(
        		'header' => Mage::helper('ecomexpress')->__('State'),
        		'width' => '100px',
        		'index' => 'state',
        ));
        $this->addColumn('dccode', array(
        		'header' => Mage::helper('ecomexpress')->__('DCcode'),
        		'width' => '100px',
        		'index' => 'dccode',
        ));
        
        $this->addColumn('created_at', array(
        		'header' => Mage::helper('ecomexpress')->__('Created Date & Time'),
        		'width' => '100px',
        		'index' => 'created_at',
        ));
	
        $this->addColumn('city_code', array(
        		'header' => Mage::helper('ecomexpress')->__('City Code'),
        		'width' => '150px',
        		'align' => 'left',
        		'index' => 'city_code',
        ));
        $this->addColumn('state_code', array(
            'header' => Mage::helper('ecomexpress')->__('State Code'),
            'width' => '150px',
            'align' => 'left',
            'index' => 'state_code',
        ));
        return parent::_prepareColumns();
    }

    /**
     *
     * preparing mass action for grid
     * @return Ecom_Express_Block_Adminhtml_Pincode_Grid
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('pincode_id');
        $this->getMassactionBlock()->setFormFieldName('pincode');
        return $this;
    }
}