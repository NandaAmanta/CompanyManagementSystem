<?php

namespace App\Repositories\Implemetations;

use App\Repositories\CompanyRepository;
use App\Models\Company;

class CompanyRepositoryImpl extends BaseRepositoryImpl implements CompanyRepository
{
    public function __construct(private Company $model)
    {
        parent::__construct($model);
    }
}