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

class Ecom_Express_Block_Adminhtml_Awb_Grid extends Ecom_Express_Block_Adminhtml_Widget_Grid {
 
	/**
	 * 
	 * setting grid parameter
	 * @return void
	 * */
    public function __construct() {
        parent::__construct();
        $this->setId('awbGrid');
        $this->setDefaultSort('awb_id');
        $this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);		
    }
    /**
     * returning grid url
     * 
     * */
	public function getGridUrl()
	{
	return $this->getUrl('*/*/grid', array('_current'=>true));
	}
	/**
	 *
	 * preparing collection and setting collection to grid
	 * @return Ecom_Express_Block_Adminhtml_AWB_Grid
	 */
    protected function _prepareCollection() {
        $collection = Mage::getModel('ecomexpress/awb')->getCollection();
        $this->setCollection($collection);
		$this->setDefaultFilter(array('state'=>1)); 
        return parent::_prepareCollection();
    }

    /**
     *
     * preparing columns for grid
     * @return Ecom_Express_Block_Adminhtml_AWB_Grid
     */
    protected function _prepareColumns() {
    	
        $this->addColumn('awb_id', array(
            'header' => Mage::helper('ecomexpress')->__('Awb ID'),
            'align' => 'center',
            'width' => '30px',
            'index' => 'awb_id',
        ));

        $this->addColumn('awb', array(
            'header' => Mage::helper('ecomexpress')->__('AWB'),
            'index' => 'awb',
        ));
		$this->addColumn('shipment_to', array(
            'header' => Mage::helper('ecomexpress')->__('Ship to'),
            'index' => 'shipment_to',
        ));
        $this->addColumn('shipment_id', array(
            'header' => Mage::helper('ecomexpress')->__('Shipment#'),
            'index' => 'shipment_id',
        	'renderer' =>  'Ecom_Express_Block_Adminhtml_Renderer_Shipmentid'
        		
        ));		
        $this->addColumn('state', array(
            'header' => Mage::helper('ecomexpress')->__('State'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'state',
            'type' => 'options',
            'options' => array(
                1 => 'Used',
                0 => 'UnUsed',
            ),
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('ecomexpress')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
            	"Manifested"=>'Manifested',
                "Assigned" => 'Assigned',
                "InTransit" => 'In Transit',
				"Dispatched" => 'Dispatched',
				"Pending" => 'Pending',
            	"Cancelled"=>'Cancelled',
				"Delivered" => 'Delivered',
				"Returned" => 'Returned',
				"RTO" => 'RTO',
				"DL" => 'DL',
				"UD" => 'UD',
				"RT" => 'RT',
				"RTO" => 'RTO',
            		
            ),
        ));		
         $this->addColumn('orderid', array(
            'header' => Mage::helper('ecomexpress')->__('Order#'),
        	'index'=>'orderid',
         	'renderer' =>  'Ecom_Express_Block_Adminhtml_Renderer_Orderid'
           
        ));
		$this->addColumn('awb_type', array(
            'header' => Mage::helper('ecomexpress')->__('AWB Type'),
			'index'=>'awb_type',
			
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('ecomexpress')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('ecomexpress')->__('XML'));

        return parent::_prepareColumns();
    }
    
}