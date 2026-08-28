<?php

declare(strict_types=1);

namespace Adm\Model;

use Ifsnop\Mysqldump\Mysqldump;

class ModelDump
{
    private string $dir = STORAGE . 'backup/';

    private string $dbHost;
    private string $dbName;
    private string $dbUser;
    private string $dbPass;

    public function __construct()
    {
        $conn = config('database', 'connect.mysql');
        $this->dbHost = $conn['host'];
        $this->dbName = $conn['database'];
        $this->dbUser = $conn['username'];
        $this->dbPass = $conn['password'];

        // $this->dbName = 'test';
        // $this->dbUser = 'root';
        // $this->dbPass = 'secret';
    }

    public function export(array $data)
    {
        $dsn = "mysql:host=$this->dbHost;dbname=$this->dbName";

        $settings = [
            'include-tables' => $data['tables'],
            'compress' => Mysqldump::GZIP,
            'add-drop-table' => true,
        ];

        try {
            $dump = new Mysqldump($dsn, $this->dbUser, $this->dbPass, $settings);
            $dump->start($this->dir . $data['filename']);
            return 'Ok';
        } catch (\Exception $e) {
            return 'mysqldump-php error: ' . $e->getMessage();
        }
    }

    public function import(string $filename)
    {
        $start = microtime(true);

        $backupFile = "{$this->dir}{$filename}";
        set_time_limit(0);

        if (!file_exists($backupFile)) {
            die("Ошибка: Файл архива не найден по пути: $backupFile\n");
        }

        $cnfPath = $this->cnfWrite();

        $importCmd = sprintf(
            "gzip -d -c %s | mysql --defaults-extra-file=%s --skip-ssl --max_allowed_packet=512M %s 2>&1",
            escapeshellarg($backupFile),
            escapeshellarg($cnfPath),
            escapeshellarg($this->dbName)
        );

        exec($importCmd, $outputImport, $codeImport);

        unlink($cnfPath);

        if ($codeImport === 0) {
            $end = microtime(true);
            $exec_time = round($end - $start, 3);
            
            return [
                'success' => true,
                'result' => "База данных '$this->dbName' успешно восстановлена! ($exec_time c.)",
            ];
        } else {
            return [
                'success' => false,
                'result' => "ОШИБКА при импорте: $codeImport Вывод системы: " . implode(" ", $outputImport),
            ];
        }
    }

    private function cnfWrite()
    {
        $cnfPath = STORAGE . '/backup/my.cnf';

        $cnfContent = sprintf(
            "[client]\nhost=%s\nuser=%s\npassword=%s\n",
            $this->dbHost,
            $this->dbUser,
            $this->dbPass
        );

        file_put_contents($cnfPath, $cnfContent);
        chmod($cnfPath, 0600);

        return $cnfPath;
    }
}
