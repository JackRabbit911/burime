<?php declare(strict_types=1);

namespace App\Branch\Component;

use App\Branch\Branch;
use Sys\Collection\Collection;
use Sys\Form\Form;

class BranchGenresForm extends Form
{
    public function __construct($totalGenres, Branch $branch)
    {
        $route = (isset($branch->id)) ? 'edit.save' : 'create.save';
        $branchGenres = $branch->genres ?? new Collection();

        if (is_array($branchGenres)) {
            $branchGenres = new Collection($branchGenres);
        }
        
        $this->form('branch/form/genres')
            ->action(path($route, ['action' => 'genres', 'id' => $branch->id ?? null]))
            ->id('genreform');

        foreach ($totalGenres as $key => $genre)
        {
            $attr = [
                'name' => 'genres[]',
                'id' => 'genre' . (string) $key,
                'label' => $genre->title,
                'value' => $genre->id,
                'checked' => $branchGenres->in($genre->id),
                'weight' => $genre->weight,
            ];

            $this->group($key, 'checkbox', 'genres', $attr);
        }
    }
}
