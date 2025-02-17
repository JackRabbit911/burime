<?php

class _2025_02_17_11_08_01_create_table_posts_ratings
{
    public function up()
    {
        return "CREATE TABLE `posts_ratings` (
        `user_id` int(10) unsigned NOT NULL,
        `post_id` int(10) unsigned NOT NULL,
        `rating` tinyint(3) unsigned NOT NULL,
        UNIQUE KEY `user_id_post_id` (`user_id`,`post_id`),
        KEY `post_id` (`post_id`),
        CONSTRAINT `posts_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `posts_ratings_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin";
    }

    public function down()
    {
        return "DROP TABLE `posts_ratings`";
    }
}
