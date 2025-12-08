<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\FirstLastDTO;
use App\Branch\Api\Model\ModelAuthors;
use App\Branch\Api\Model\ModelBranch;
use App\Burime\Model\ModelPost;
use Common\Enum\MemberRole;

class BranchRepo
{
    private string $prefix = './img/branch/';

    public function __construct(
        private ModelBranch $modelBranch,
        private ModelAuthors $modelAuthors
    ) {}

    public function findBranch(?int $branch_id)
    {
        $params = $branch_id ? $this->modelBranch->find($branch_id) : [];

        return is_null($params) ? null : new BranchDTO($params);
    }

    public function getBranchGenres(?int $branch_id)
    {
        return $branch_id ? $this->modelBranch->getBranchGenres($branch_id) : [];
    }

    public function getBranchAuthors(?int $branch_id)
    {
        return $branch_id ? $this->modelBranch->getBranchAuthors($branch_id) : [];
    }

    public function getAuthors(int $user_id, array $query_params = [])
    {
        $own_authors = $this->modelAuthors->getByUser($user_id);
        $except = array_map(fn($author) => $author->id, $own_authors);

        $filter = $query_params['filter'] ?? null;
        $search = $query_params['search'] ?? null;
        $page = $query_params['page'] ?? 1;
        $limit = $query_params['limit'] ?? 25;
        $offset = ((int) $page - 1) * (int) $limit;

        $authors = $this->modelAuthors->getByFilter(
            (int) $limit,
            $offset,
            $filter,
            $search,
            $except
        );

        return $authors;
    }

    public function getOwnAuthors($user_id)
    {
        return $this->modelAuthors->getByUser($user_id);
    }

    public function getTotalGenres()
    {
        $genres = $this->modelBranch->getTotalGenres();

        return array_map(function ($v) {
            $array = json_decode($v);
            usort($array, function ($a, $b) {
                return $a->id < $b->id ? -1 : 1;
            });
            return $array;
        }, $genres);
    }

    public function getAuthorsFilters()
    {
        return MemberRole::getFilters();
    }

    public function getGenres()
    {
        return $this->modelBranch->getGenres();
    }

    public function getFirstLastPosts(?int $branch_id)
    {
        $params = $branch_id ? [
            'first' => $this->modelBranch->findPostByWeight($branch_id, 1),
            'last' => $this->modelBranch->findPostByWeight($branch_id, ModelPost::MAX_WEIGHT),
        ] : [];

        return new FirstLastDTO($params);
    }

    public function getCoverFiles(?int $branch_id)
    {
        $data['cover'] = $this->fileEncode($branch_id, 'cover');
        $data['bg_img'] = $this->fileEncode($branch_id, 'background');

        return $data;
    }

    public function getBase64Coverfiles(?int $branch_id)
    {
        $data['cover'] = $this->fileTypeEncode($branch_id, 'cover');
        $data['bg_img'] = $this->fileTypeEncode($branch_id, 'background');

        return $data;
    }

    private function fileEncode(?int $branch_id, string $filename)
    {
        if (!$branch_id) {
            return null;
        }

        $pattern = $this->prefix . $branch_id . '/' . $filename . '.{jp*g,png}';
        $file = glob($pattern, GLOB_BRACE)[0] ?? null;

        if (!$file) {
            return null;
        }

        $data['filename'] = pathinfo($file, PATHINFO_BASENAME);
        $data['mime'] = mime_content_type($file);
        $data['base64'] = base64_encode(file_get_contents($file));

        return $data;
    }

    private function fileTypeEncode(?int $branch_id, string $filename)
    {
        if (!$branch_id) {
            return null;
        }

        $pattern = $this->prefix . $branch_id . '/' . $filename . '.{jp*g,png}';
        $file = glob($pattern, GLOB_BRACE)[0] ?? null;

        if (!$file) {
            return null;
        }

        $type = mime_content_type($file);
        $data = file_get_contents($file);
        return 'data:' . $type . ';base64,' . base64_encode($data);
    }
}
