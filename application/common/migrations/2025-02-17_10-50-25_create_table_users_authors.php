<?php

class _2025_02_17_10_50_25_create_table_users_authors
{
    public function up()
    {
        return "CREATE TABLE `users_authors` (
        `user_id` int(10) unsigned NOT NULL,
        `author_id` int(10) unsigned NOT NULL,
        `role` tinyint(3) unsigned NOT NULL,
        `status` tinyint(3) unsigned DEFAULT NULL,
        UNIQUE KEY `user_id_author_id_role` (`user_id`,`author_id`,`role`),
        KEY `author_id` (`author_id`),
        KEY `user_id` (`user_id`),
        CONSTRAINT `users_authors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
        CONSTRAINT `users_authors_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin";
    }

    public function down()
    {
        return "DROP TABLE `users_authors`";
    }
}
