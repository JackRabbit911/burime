<?php

class _2025_02_17_11_00_26_create_table_messages
{
    public function up()
    {
        return "CREATE TABLE `messages` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `from` int(10) unsigned NOT NULL,
        `handler` varchar(128) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
        `subject` varchar(255) NOT NULL,
        `data` json DEFAULT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4";
    }

    public function down()
    {
        return "DROP TABLE `messages`";
    }
}
