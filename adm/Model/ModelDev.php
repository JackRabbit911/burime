<?php

declare(strict_types=1);

namespace Adm\Model;

use Sys\Model\MysqlModel;
use PDO;
use Pecee\Pixie\QueryBuilder\Transaction;

class ModelDev extends MysqlModel
{
   public function tables()
   {
        return $this->qb->query('SHOW TABLES')
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
   }

   public function truncate(array $tables)
   {
      $i = 0;
         $this->qb->query('SET FOREIGN_KEY_CHECKS = 0;');

         foreach ($tables as $table) {
            $this->qb->query("TRUNCATE TABLE $table;");
            $i++;
         }

         $this->qb->query('SET FOREIGN_KEY_CHECKS = 1;');

      return $i;
   }
}
