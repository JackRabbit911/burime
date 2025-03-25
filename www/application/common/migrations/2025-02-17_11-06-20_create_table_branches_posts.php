<?php

class _2025_02_17_11_06_20_create_table_branches_posts
{
    public function up()
    {
        return "CREATE TABLE `branches_posts` (
        `branch_id` int(10) unsigned NOT NULL,
        `post_id` int(10) unsigned NOT NULL,
        `weight` smallint(5) unsigned NOT NULL,
        UNIQUE KEY `branch_id_post_id` (`branch_id`,`post_id`),
        KEY `post_id` (`post_id`),
        KEY `weight` (`weight`),
        KEY `branch_id` (`branch_id`),
        CONSTRAINT `branches_posts_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
        CONSTRAINT `branches_posts_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin";
    }

    public function down()
    {
        return "DROP TABLE `branches_posts`";
    }
}
