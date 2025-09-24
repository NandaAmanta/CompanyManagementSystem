<?php

namespace App\Http\Controllers\Management;


use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyCreationRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Requests\Employee\EmployeeCreationRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Services\EmployeeService;

class EmployeeController extends Controller
{

    public function __construct(private EmployeeService $service)
    {
        
    }

    public function index()
    {
        if(request()->wantsJson()) {
            return $this->service->paginate(request()->all());
        }
        return view('management.employee.index');
    }

    public function store(EmployeeCreationRequest $request)
    {
        $validated = $request->validated();
        return $this->service->create($validated);
    }

    public function show($id)
    {
        return $this->service->detail($id);
    }

    public function update(EmployeeUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        return $this->service->update($id, $validated);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
    
}