<?php

class _2025_02_17_11_05_07_create_table_branches_genres
{
    public function up()
    {
        return "CREATE TABLE `branches_genres` (
        `branch_id` int(10) unsigned NOT NULL,
        `genre_id` int(10) unsigned NOT NULL,
        UNIQUE KEY `branch_id_genre_id` (`branch_id`,`genre_id`),
        CONSTRAINT `branches_genres_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin";
    }

    public function down()
    {
        return "DROP TABLE `branches_genres`";
    }
}
