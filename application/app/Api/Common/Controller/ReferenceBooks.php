<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use App\Api\Common\Controller\ApiContractController;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;
use Common\Enum\MemberRole;

class ReferenceBooks extends ApiContractController
{
    public function branch()
    {
        $data['authorsFilters'] = MemberRole::getFilters();
        $data['authorsPermissions'] = BranchAuthorPermissions::getArray();
        $data['authorsStatuses'] = BranchAuthorStatus::getArray();

        return $data;
    }

    public function group()
    {
        $data['authorsFilters'] = MemberRole::getFilters();
        $data['authorsPermissions'] = BranchAuthorPermissions::getArray();
        $data['authorsStatuses'] = BranchAuthorStatus::getArray();
        
        return $data;
    }
}
