<?php

namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeCreationRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Services\EmployeeService;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{

    public function __construct(private EmployeeService $service)
    {
        
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->get('per_page', 10);
        $employees = $this->service->paginate(request()->all())->withQueryString();
        return Inertia::render('employee/Index',[
            'companies' => Company::all(['id','name']),
            'employees' => $employees,
            'filters' => [
                'search' => $request->search,
                'sort_field' => $sortField,
                'sort_order' => $sortOrder,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function store(EmployeeCreationRequest $request)
    {
        $validated = $request->validated();
        $this->service->create($validated);
        return redirect()->back()->with('success', 'Employee created successfully.');
    }

    public function show($id)
    {
        return $this->service->detail($id);
    }

    public function update(EmployeeUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $this->service->update($id, $validated);
        return redirect()->back()->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->back()->with('success', 'Employee deleted successfully.');
    }
    
}