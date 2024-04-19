<?php

class Ccc_Banner_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('bannerBlockGrid');
        $this->setDefaultSort('block_identifier');
        $this->setDefaultDir('ASC');
        // var_dump(Mage::getStoreConfig('banner/'))
    }

    // MAss Actions 
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setFormFieldName('banner_id'); // Change to 'banner_id'

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => Mage::helper('banner')->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('banner')->__('Are you sure you want to delete selected banners?')
            )
        );

        $statuses = Mage::getSingleton('banner/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label' => Mage::helper('banner')->__('Change status'),
                'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('banner')->__('Status'),
                        'values' => $statuses
                    )
                )
            )
        );

        Mage::dispatchEvent('banner_adminhtml_banner_grid_prepare_massaction', array('block' => $this));
        return $this;
    }



    // protected function _prepareCollection()
    // {
    //     $collection = Mage::getModel('banner/banner')->getCollection();
    //     // / @var $collection Mage_Cms_Model_Mysql4_Block_Collection /
    //     $this->setCollection($collection);
    //     return parent::_prepareCollection();
    // }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('banner/banner')->getCollection();

        // Check if the "Five Rows" resource is allowed
        $acl = Mage::getSingleton('admin/session')->isAllowed('banner/display/showall');

        if (!$acl) {
            // Limit the collection to the first five rows
            $collection->setOrder('banner_id', 'DESC')
                ->getSelect()
                ->limit(5);
            $this->setCollection($collection);
            $this->getCollection()->load();
        } else {
            $this->setCollection($collection);
        }


        return parent::_prepareCollection();
    }

    public function addColumn($columnId, $column)
    {
        if (isset($column['is_allowed']) && $column['is_allowed'] === false) {
            return;
        }
        return parent::addColumn($columnId, $column);
    } 
    //this function overides the addd column and the condition of true false is added 

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

       
            $this->addColumn(
                'banner_id',
                array(
                    'header' => Mage::helper('banner')->__('ID'),
                    'width' => '50px',
                    'type' => 'number',
                    'index' => 'banner_id',
                    'is_allowed' => Mage::getSingleton('admin/session')->isAllowed('banner/field/id')
                )
            );
        


        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('banner')->__('Name'),
                'align' => 'left',
                'index' => 'name',
                'is_allowed' => Mage::getSingleton('admin/session')->isAllowed('banner/field/name')
            )
        );


        
            $this->addColumn(
                'image',
                array(
                    'header' => Mage::helper('banner')->__('Image'),
                    'align' => 'left',
                    'index' => 'image',
                    'renderer' => 'banner/adminhtml_banner_grid_renderer_image',
                    'is_allowed' => Mage::getSingleton('admin/session')->isAllowed('banner/field/image')

                )
            );
        

        
            $this->addColumn(
                'content',
                array(
                    'header' => Mage::helper('banner')->__('Content'),
                    'align' => 'left',
                    'index' => 'content',
                    'is_allowed' => Mage::getSingleton('admin/session')->isAllowed('banner/field/content')
                )
            );
        

      
            $this->addColumn(
                'show_on',
                array(
                    'header' => Mage::helper('banner')->__('Show On'),
                    'align' => 'left',
                    'index' => 'show_on',
                    'type' => 'options',
                    'is_allowed' => Mage::getSingleton('admin/session')->isAllowed('banner/field/show_on'),
                    'options' => array(
                        1 => Mage::helper('banner')->__('Homepage'),
                        2 => Mage::helper('banner')->__('Checkout')
                    ),
                )
            );
        


      
            $this->addColumn(
                'is_active',
                array(
                    'header' => Mage::helper('banner')->__('Status'),
                    'index' => 'status',
                    'type' => 'options',
                    'is_allowed' => Mage::getSingleton('admin/session')->isAllowed('banner/field/status'),
                    'options' => array(
                        2 => Mage::helper('banner')->__('Disabled'),
                        1 => Mage::helper('banner')->__('Enabled')
                    ),
                )
            );
        

        return parent::_prepareColumns();
    }


    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('banner_id' => $row->getId()));
    }
}