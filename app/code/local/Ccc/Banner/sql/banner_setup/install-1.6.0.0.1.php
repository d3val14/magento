<?php

$installer = $this;

$installer->startSetup();

/**
 * Create table 'ccc/banner'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_banner'))
    ->addColumn('banner_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Banner ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Banner Name')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Banner Image')
    ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Banner Content')
    ->addColumn('show_on', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Banner Show On')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Banner Status')
    ->setComment('CCC Banner Table');
$installer->getConnection()->createTable($table);
$installer->endSetup();


