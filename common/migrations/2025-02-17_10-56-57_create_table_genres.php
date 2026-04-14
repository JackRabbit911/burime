<?php

class _2025_02_17_10_56_57_create_table_genres
{
    public function up()
    {
        return "CREATE TABLE `genres` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `title` varchar(64) NOT NULL,
        `weight` tinyint(3) unsigned NOT NULL,
        `description` text,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    }

    public function down()
    {
        return "DROP TABLE `genres`";
    }
}
