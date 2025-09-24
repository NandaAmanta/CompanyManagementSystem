<?php

namespace App\Http\Repositories\Implemetations;

use App\Http\Repositories\CompanyRepository;
use App\Models\Company;

class CompanyRepositoryImpl extends BaseRepositoryImpl implements CompanyRepository
{
    public function __construct(private Company $model)
    {
        parent::__construct($model);
    }
}