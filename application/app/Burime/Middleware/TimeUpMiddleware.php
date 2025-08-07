<?php declare(strict_types=1);

namespace App\Burime\Middleware;

use Attribute;
use App\Burime\Repository\BranchRepo;
use Common\Enum\BranchStatus;
use Az\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Attribute]
class TimeUpMiddleware implements MiddlewareInterface
{
    private BranchRepo $repo;

    public function __construct(BranchRepo $repo)
    {
        $this->repo = $repo;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $branch_id = $request->getAttribute(Route::class)->getParameters()['branch_id'];
        $branch = $this->repo->find($branch_id);

        if (is_string($branch->info)) {
            $branch->info = trim(str_replace(['\\'], '', $branch->info), '"');
            $branch->info = json_decode($branch->info, true);
        }

        $branch->info['time_up'] = (isset($branch->info['time_beguin'])) 
            ? time() - $branch->info['time_beguin'] > $branch->info['time_limit'] * 60
            : false;

        if ($branch->info['time_up']) {
            if (BranchStatus::isBlocked($branch->status)) {
                $branch->status = BranchStatus::Ready->value;
            }

            $this->repo->setPostStatusPublish($branch_id);
        }


        return $handler->handle($request->withAttribute('branch', $branch));
    }
}
