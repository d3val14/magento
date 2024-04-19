<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_repricer_matching');
if ($installer->getConnection()->isTableExists($tableName) !== true) {
    $table = $installer->getConnection()
        ->newTable($tableName)
        ->addColumn('repricer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Repricer ID')
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'nullable' => false,
        ), 'Product Id')
        ->addColumn('competitor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'nullable' => false,
        ), 'Competitor Id')
        ->addColumn('competitor_url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable' => false
        ), 'Competitor Url')
        ->addColumn('competitor_sku', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
        ), 'Competitor Sku')
        ->addColumn('competitor_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
            'nullable'  => false,
        ), 'Competitor Price')
        ->addColumn('reason', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'nullable'  => false,
            'default' => 0
        ), 'Reason')
        ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable'  => false,
            'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
        ), 'Updated Date')
        ->setComment('Competitor Block Table');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();
