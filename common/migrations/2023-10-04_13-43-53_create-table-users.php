<?php

use App\Model\ModelTables;

class _2023_10_04_13_43_53_create_table_users
{
    public function up()
    {
        $array = require APPPATH . 'auth/Model/CreateTable.php';
        return $array['users'];
    }

    public function down()
    {
        return "DROP TABLE `users`";
    }
}
