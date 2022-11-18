<?php

namespace App\Interfaces;

use App\Models\Company;
use App\Interfaces\BaseRepositoryInterface;

interface CompanyRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUserId($userId): ?array;

    public function create($userId, array $companyData): Company;
}