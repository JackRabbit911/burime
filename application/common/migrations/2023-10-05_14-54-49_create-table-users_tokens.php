<?php

class _2023_10_05_14_54_49_create_table_users_tokens
{
    public function up()
    {
        $array = require APPPATH . 'auth/Model/CreateTable.php';
        return $array['users_tokens'];
    }

    public function down()
    {
        return "DROP TABLE `users_tokens`";
    }
}
