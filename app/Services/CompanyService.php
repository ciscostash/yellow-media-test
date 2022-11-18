<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

use App\Interfaces\CompanyRepositoryInterface;
use App\Interfaces\CompanyServiceInterface;

class CompanyService implements CompanyServiceInterface
{
    private $companyRepository;
    
    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function getCompaniesJsonByUserId($userId)
    {
        $companies = $this->companyRepository->getByUserId($userId);

        return response()->json($companies);
    }

    public function addCompanyToUser($userId, $request)
    {
        $validator = Validator::make($request->toArray(), [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string|max:255',
            'phone'         => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'validation failed',
                'details'   => $validator->messages()
            ]);
        }

        $company = $this->companyRepository->create($userId, [
            'title'         => request()->input('title'),
            'description'   => request()->input('description'),
            'phone'         => request()->input('phone'),
        ]);

        return response()->json([
            'status' => 'success',
        ], 201);
    }
}