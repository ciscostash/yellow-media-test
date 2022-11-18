<?php

namespace App\Http\Controllers;

use App\Interfaces\CompanyServiceInterface;

class CompanyController extends Controller
{
    private $companyService;
    
    public function __construct(CompanyServiceInterface $companyService)
    {
        $this->companyService = $companyService;
    }

    public function getCompanies()
    {
        $user = app('auth')->guard()->user();
        return $this->companyService->getCompaniesJsonByUserId($user->id);
    }

    public function addCompany()
    {
        $user = app('auth')->guard()->user();
        return $this->companyService->addCompanyToUser($user->id, request());
    }
}