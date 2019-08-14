<?php
//core_resource table
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('mmnadmin/mmn')};
CREATE TABLE {$this->getTable('mmnadmin/mmn')} (
`mmn_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`entity_id` INT NOT NULL ,
`manufacturer` VARCHAR( 100 ) NOT NULL ,
`model` VARCHAR( 100 ) NOT NULL ,
`number` VARCHAR( 100 ) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 