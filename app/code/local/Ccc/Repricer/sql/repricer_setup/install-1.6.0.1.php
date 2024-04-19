<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_repricer_competitor');
if ($installer->getConnection()->isTableExists($tableName) !== true) {
    $table = $installer->getConnection()
        ->newTable($tableName)
        ->addColumn('competitor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Competitor ID')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'Competitor Name')
        ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable' => false,
        ), 'Competitor URL')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, 255, array(
            'nullable' => false
        ), 'Status')
        ->addColumn('filename', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => true,
        ), 'Filename')
        ->addColumn('created_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable'  => false,
        ), 'Created Date')
        ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable'  => false,
        ), 'Updated Date')
        ->setComment('Competitor Block Table');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();
