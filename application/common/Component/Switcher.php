<?php declare(strict_types=1);

namespace Common\Component;

use Psr\Http\Message\ServerRequestInterface;
use Sys\Template\Component;

class Switcher extends Component
{
    private array $queryParams;
    private string $path;
    private string $showKey = 'show';
    private string $filterKey = 'filter';
    private string $pageKey = 'page';
    private string $view;

    public function __construct(ServerRequestInterface $request, string $view)
    {
        $this->queryParams = $request->getQueryParams();
        $this->path = rtrim($request->getUri()->getPath(), '/');
        $this->view = $view;
    }

    public function render()
    {
        return view($this->view, ['s' => $this]);
    }

    public function cards()
    {
        $queryParams = $this->queryParams;
        unset($queryParams[$this->pageKey]);
        unset($queryParams[$this->showKey]);
        return $this->link($queryParams);
    }

    public function table()
    {
        $queryParams = $this->queryParams;
        unset($queryParams[$this->pageKey]);
        $queryParams[$this->showKey] = 'table';
        return $this->link($queryParams);
    }

    public function list()
    {
        $queryParams = $this->queryParams;
        unset($queryParams[$this->pageKey]);
        $queryParams[$this->showKey] = 'list';
        return $this->link($queryParams);
    }

    public function filter($enable = false)
    {
        $queryParams = $this->queryParams;

        if ($enable) {
            $queryParams[$this->filterKey] = 'groups';
        } else {
            unset($queryParams[$this->filterKey]);
        }

        return $this->link($queryParams);
    }

    private function link($queryParams)
    {
        return (!empty($queryParams)) 
            ? $this->path . '?' . http_build_query($queryParams) : $this->path;
    }
}
