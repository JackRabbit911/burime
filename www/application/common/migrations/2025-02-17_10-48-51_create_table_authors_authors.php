<?php

class _2025_02_17_10_48_51_create_table_authors_authors
{
    public function up()
    {
        return "CREATE TABLE `authors_authors` (
        `parent_id` int(10) unsigned NOT NULL,
        `child_id` int(10) unsigned NOT NULL,
        `role` tinyint(3) unsigned NOT NULL,
        `status` tinyint(3) unsigned DEFAULT NULL,
        UNIQUE KEY `parent_id_child_id` (`parent_id`,`child_id`),
        KEY `child_id` (`child_id`),
        KEY `parent_id` (`parent_id`),
        CONSTRAINT `authors_authors_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `authors_authors_ibfk_4` FOREIGN KEY (`child_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin";
    }

    public function down()
    {
        return "DROP TABLE `authors_authors`";
    }
}
