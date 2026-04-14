<?php

class _2026_02_20_10_37_58_create_table_confirm_codes
{
    public static function up()
    {
        return "CREATE TABLE `confirm_codes` (
        `code` varchar(128) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
        `user` json DEFAULT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `code` (`code`),
        KEY `created` (`created`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    }

    public static function down()
    {
        return "DROP TABLE `confirm_codes`";
    }
}
