<?php

class _2025_02_17_10_55_10_create_table_branches
{
    public function up()
    {
        return "CREATE TABLE `branches` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `parent_id` int(10) unsigned DEFAULT NULL,
        `owner` int(10) unsigned NOT NULL,
        `title` varchar(255) NOT NULL,
        `role` tinyint(3) unsigned NOT NULL,
        `age_limit` tinyint(3) unsigned DEFAULT NULL,
        `cover` varchar(128) DEFAULT NULL,
        `info` json DEFAULT NULL,
        `status` tinyint(3) unsigned DEFAULT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `owner` (`owner`),
        KEY `parent_id` (`parent_id`),
        KEY `openclosed` (`role`),
        KEY `age_limit` (`age_limit`),
        KEY `status` (`status`),
        KEY `created` (`created`),
        KEY `cover` (`cover`),
        CONSTRAINT `branches_ibfk_3` FOREIGN KEY (`owner`) REFERENCES `users` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    }

    public function down()
    {
        return "DROP TABLE `branches`";
    }
}
