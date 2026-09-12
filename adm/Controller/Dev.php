<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Model\ModelDev;
use Adm\Model\ModelDump;
use App\Api\Common\Controller\ApiContractController;
use Az\Route\Route;
use HttpSoft\Response\JsonResponse;
use Sys\Console\Command\Do\Down;
use Sys\Console\Command\Do\Up;

class Dev extends ApiContractController
{
    public function tables(ModelDev $model)
    {
        return [
            'tables' => $model->tables(),
            'exclude' => ['migrations', 'genres'],
        ];
    }

    #[Route(methods: 'post')]
    public function export(ModelDump $model)
    {
        return $model->export($this->data);
    }

    #[Route(methods: 'post')]
    public function import(ModelDump $model)
    {
        $response = $model->import($this->data['selectedFile']);
        return new JsonResponse($response);
    }

    public function filename()
    {
        return 'dump_' . date('Y-m-d_H-i') . '.sql.gz';
    }

    #[Route(methods: 'post')]
    public function truncate(ModelDev $model)
    {
        return $this->data;
        $count = $model->truncate($this->data['tables']);
        return ['truncated' => $count];
    }

    #[Route(methods: 'delete')]
    public function drop()
    {
        return $this->data;
    }

    public function dumps()
    {
        $dir = STORAGE . 'backup/';
        $res = [];

        foreach (glob($dir . '*.sql.gz', GLOB_NOSORT) as $key => $path) {
            $res[$key]['filename'] = basename($path);
            $res[$key]['time'] = filemtime($path);
            $res[$key]['size'] = filesize($path);
        }

        usort($res, function ($a, $b) {
            if ($a['time'] === $b['time']) {
                return 0;
            }

            return ($a['time'] < $b['time']) ? -1 : 1;
        });

        array_walk($res, function (&$v) {
            $v['time'] = date('Y-m-d H:m:i', $v['time']);
        });

        return $res;
    }

    #[Route(methods: ['get', 'post'])]
    public function maintenance()
    {
        if ($this->request->getMethod() === 'GET') {
            return ['off' => is_file(STORAGE . 'maintenance')];
        }

        $wait = $this->data['wait'];
        ($wait > 0) ? Down::down($wait) : Up::up();

        return ['off' => ($wait > 0)];
    }
}
