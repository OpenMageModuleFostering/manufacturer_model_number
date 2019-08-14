<?php
//core_resource table
$installer = $this;

$installer->startSetup();

$installer->run("
 ALTER TABLE `{$this->getTable('mmnadmin/mmn')}`
CHANGE `entity_id` `sku` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
    ");

$installer->endSetup(); 