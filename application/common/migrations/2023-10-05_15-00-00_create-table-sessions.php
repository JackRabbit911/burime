<?php

use Az\Session\Driver\Db;

class _2023_10_05_15_00_00_create_table_sessions
{
    public function up()
    {
        return Db::CREATE_TABLE_SESSIONS;
    }

    public function down()
    {
        return "DROP TABLE `sessions`";
    }
}
