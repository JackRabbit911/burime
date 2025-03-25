<?php

class _2025_02_17_11_03_16_create_table_branches_authors
{
    public function up()
    {
        return "CREATE TABLE `branches_authors` (
        `branch_id` int(10) unsigned NOT NULL,
        `author_id` int(10) unsigned NOT NULL,
        `user_id` int(10) unsigned DEFAULT NULL,
        `role` tinyint(3) unsigned NOT NULL,
        `status` tinyint(3) unsigned DEFAULT NULL,
        UNIQUE KEY `branch_id_author_id_user_id` (`branch_id`,`author_id`,`user_id`),
        KEY `branch_id` (`branch_id`),
        KEY `author_id` (`author_id`),
        KEY `user_id` (`user_id`),
        KEY `role` (`role`),
        CONSTRAINT `branches_authors_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `branches_authors_ibfk_4` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin";
    }

    public function down()
    {
        return "DROP TABLE `branches_authors`";
    }
}
