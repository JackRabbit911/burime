<?php declare(strict_types=1);

namespace App\Burime\Component;

use App\Burime\Service\PostPermissions;
use Common\Contract\BranchInterface;
use Sys\Template\Component;

class Timer extends Component
{
    protected ?string $view = 'burime/timer';

    public function __construct(BranchInterface $branch, PostPermissions $postPermissions)
    {
        $now = time();

        if (!isset($branch->info['time_beguin'])) {
            $branch->info['time_beguin'] = $now;
        }
        
        $tr = ($branch->info['time_limit'] * 60) - ($now - $branch->info['time_beguin']);

        if ($tr < 0) {
            $tr = 0;
        }

        $this->data['postPermissions'] = $postPermissions;
        $this->data['timeRemaining'] = $tr;
        $this->data['hour'] = (int) floor($tr / 60 / 60);
        $this->data['min'] = intdiv($tr - ($this->data['hour'] * 60 * 60), 60);
        $this->data['sec'] = $tr % 60;
    }
}
