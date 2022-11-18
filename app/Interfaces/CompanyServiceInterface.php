<?php

namespace App\Interfaces;

interface CompanyServiceInterface
{
    public function getCompaniesJsonByUserId($userId);

    public function addCompanyToUser($userId, $request);
}