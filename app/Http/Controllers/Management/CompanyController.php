<?php

namespace App\Http\Controllers\Management;


use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyCreationRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Services\CompanyService;

class CompanyController extends Controller
{

    public function __construct(private CompanyService $service)
    {
        
    }

    public function index()
    {
        if(request()->wantsJson()) {
            return $this->service->paginate(request()->all());
        }
        return view('management.companies.index');
    }

    public function store(CompanyCreationRequest $request)
    {
        $validated = $request->validated();
        return $this->service->create($validated);
    }

    public function show($id)
    {
        return $this->service->detail($id);
    }

    public function update(CompanyUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        return $this->service->update($id, $validated);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
    
}