<?php declare(strict_types=1);

namespace App\Branch\Controller;

use App\Branch\Model\SaveCover;
use App\Branch\Branch;
use Sys\Controller\WebController;
use Az\Route\Route;

#[Route(methods: 'post')]
abstract class BranchSaveAbstract extends WebController
{
    protected Branch $branch;
    protected array $data;
    protected ?string $action;

    protected function _before()
    {
        $this->data = $this->request->getParsedBody();
        $this->action = $this->data['sbmt'] ?? null;
        unset($this->data['sbmt']);
    }

    public function rules($id)
    {
        if (!isset($this->data['info']['moderation'])) {
            $this->data['info']['moderation'] = 0;
        } else {
            $this->data['info']['moderation'] = (int) $this->data['info']['moderation'];
        }

        if (!isset($this->data['info']['comments'])) {
            $this->data['info']['comments'] = 0;
        } else {
            $this->data['info']['comments'] = (int) $this->data['info']['comments'];
        }

        if (!isset($this->data['info']['signature'])) {
            $this->data['info']['signature'] = 0;
        } else {
            $this->data['info']['signature'] = (int) $this->data['info']['signature'];
        }

        if (isset($this->data['info']['time_limit'])) {
            $this->data['info']['time_limit'] = (int) $this->data['info']['time_limit'];
        }

        if (isset($this->data['info']['post_size'])) {
            $this->data['info']['post_size'] = (int) $this->data['info']['post_size'];
        }
    }

    public function cover(SaveCover $saveCover, $id)
    {
        if ($this->action === 'reset') {
            $this->data['info'] = [
                'bg_color' => null,
                'text_color' => null,
            ];

            if (isset($this->branch->cover) && is_file(Branch::COVERPATH . $this->branch->cover)) {
                unlink(Branch::COVERPATH . $this->branch->cover);
            }

            $data['cover'] = $saveCover->save($this->request);
            $this->action = __FUNCTION__;
        }
    }
}
