<?php

class _2025_02_17_10_58_39_create_table_posts
{
    public function up()
    {
        return "CREATE TABLE `posts` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `author_id` int(10) unsigned NOT NULL,
        `body` text NOT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `author_id` (`author_id`),
        CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    }

    public function down()
    {
        return "DROP TABLE `posts`";
    }
}
