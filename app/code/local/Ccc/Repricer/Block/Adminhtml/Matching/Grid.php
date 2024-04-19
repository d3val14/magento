<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('matchingBlockGrid');
        $this->setDefaultSort('block_identifier');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {

        $collection = Mage::getModel('repricer/matching')->getCollection();
        $select = $collection->getSelect();
        $columns = [
            'updated_date' => 'updated_date',
            'product_id' => 'product_id',
            'repricer_id' => 'repricer_id',
            'reason' => 'reason',
            'competitor_url' => 'competitor_url',
            'competitor_sku' => 'competitor_sku',
            'competitor_price' => 'competitor_price',
            'competitor_name' => 'rc.name',
            'product_name' => 'ev.value'
        ];

        $select->join(
            array('rc' => Mage::getSingleton('core/resource')->getTableName('repricer/competitor')),
            'rc.competitor_id = main_table.competitor_id',
            ['']
        )
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);

        $select->join(
            array('ev' => 'catalog_product_entity_varchar'),
            'ev.entity_id = product_id AND ev.attribute_id = 71 AND ev.store_id = 0',
            ['']
        )
            ->reset(Zend_Db_Select::COLUMNS)
            ->order('repricer_id', 'ASC')
            ->columns($columns);

        $select->join(
            array('sta' => 'catalog_product_entity_int'),
            'sta.entity_id = product_id AND sta.attribute_id = 96 AND sta.value = 1 AND sta.store_id = 0',
            ['']
        )
            ->reset(Zend_Db_Select::COLUMNS)
            ->order('repricer_id', 'ASC')
            ->columns($columns);

        $this->setCollection($collection);
        return parent::_prepareCollection();


    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'repricer_id',
            array(
                'header' => Mage::helper('repricer')->__('Repricer Id'),
                'width' => '50px',
                'type' => 'number',
                'index' => 'repricer_id',
            )
        );
        $this->addColumn(
            'product_id',
            array(
                'header' => Mage::helper('repricer')->__('Product Id'),
                'width' => '50px',
                'type' => 'number',
                'index' => 'product_id',
            )
        );
        $this->addColumn(
            'product_name',
            array(
                'header' => Mage::helper('repricer')->__('Product Name'),
                'width' => '50px',
                'type' => 'text',
                'index' => 'product_name',
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
            )
        );
        $this->addColumn(
            'competitor_name',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Name'),
                'width' => '50px',
                'type' => 'text',
                'index' => 'competitor_name',
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
            )
        );
        $this->addColumn(
            'competitor_url',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Url'),
                'align' => 'left',
                'index' => 'competitor_url',
            )
        );
        $this->addColumn(
            'competitor_sku',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Sku'),
                'align' => 'left',
                'index' => 'competitor_sku',
            )
        );
        $this->addColumn(
            'competitor_price',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Price'),
                'align' => 'left',
                'index' => 'competitor_price',
            )
        );
        $this->addColumn(
            'reason',
            array(
                'header' => Mage::helper('repricer')->__('Reason'),
                'index' => 'reason',
                'type' => 'options',
                'options' => Mage::getModel('repricer/matching')->getReasonOptionArray(),
            )
        );
        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('repricer')->__('updated_date'),
                'align' => 'left',
                'index' => 'updated_date',
                'type' => 'datetime',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_datetime',
            )
        );
        return parent::_prepareColumns();
    }

    public function addColumn($columnId, $column)
    {
        if (isset($column['is_allowed']) && $column['is_allowed'] === false) {
            return;
        }
        return parent::addColumn($columnId, $column);
    }
    // protected function _afterLoadCollection()
    // {
    //     $this->getCollection()->walk('afterLoad');
    //     parent::_afterLoadCollection();
    // }
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $fieldName = $column->getFilterIndex() ?: $column->getIndex();
        switch ($fieldName) {
            case 'product_name':
                $this->_filterProductNameCondition($collection, $column);
                break;
            case 'competitor_name':
                $this->_filterCompetitorNameCondition($collection, $column);
                break;
            // Add more cases for additional custom columns if needed
        }
    }


    protected function _filterProductNameCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addFieldToFilter('ev.value', $value);
    }

    protected function _filterCompetitorNameCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addFieldToFilter('rc.name', $value);
    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('repricer_id' => $row->getId()));
    }
}
