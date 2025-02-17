<?php

class _2025_02_17_11_10_11_create_table_messages_authors
{
    public function up()
    {
        return "CREATE TABLE `messages_authors` (
        `message_id` int(10) unsigned NOT NULL,
        `author_id` int(10) unsigned NOT NULL,
        `user_id` int(10) unsigned NOT NULL,
        `status` tinyint(3) unsigned NOT NULL,
        KEY `message_id` (`message_id`),
        KEY `author_id` (`author_id`),
        KEY `user_id` (`user_id`),
        CONSTRAINT `messages_authors_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`),
        CONSTRAINT `messages_authors_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
        CONSTRAINT `messages_authors_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin";
    }

    public function down()
    {
        return "DROP TABLE `messages_authors`";
    }
}
