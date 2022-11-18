<?php
namespace App\Repositories;

use App\Interfaces\CompanyRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Company;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    protected $model;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    public function getByUserId($userId): ?array
    {
        return $this->model->whereUserId($userId)->get()->toArray();
    }

    public function create($userId, array $companyData): Company
    {
        $companyData = array_merge(['user_id' => $userId], $companyData);

        return $this->model->create($companyData);
    }

}