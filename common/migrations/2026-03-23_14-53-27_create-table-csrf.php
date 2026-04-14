<?php

use Sys\CSRF\Driver\Db;

class _2026_03_23_14_53_27_create_table_csrf 
{
    public static function up()
    {
        return Db::CREATE_TABLE_CSRF;
    }

    public static function down()
    {
        return "DROP TABLE `csrf`";
    }
}
