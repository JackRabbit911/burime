<?php

class _2025_02_17_10_41_25_create_table_authors
{
    public function up()
    {
        return "CREATE TABLE `authors` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `owner` int(10) unsigned NOT NULL,
        `openclosed` tinyint(3) unsigned NOT NULL,
        `alias` varchar(128) NOT NULL,
        `info` json DEFAULT NULL,
        `created` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `alias` (`alias`),
        KEY `owner` (`owner`),
        KEY `openclosed` (`openclosed`)
        ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4";
    }

    public function down()
    {
        return "DROP TABLE `authors`";
    }
}
