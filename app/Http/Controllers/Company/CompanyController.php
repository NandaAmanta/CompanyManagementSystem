<?php

namespace App\Http\Controllers\Company;


use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyCreationRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{

    public function __construct(private CompanyService $service)
    {
        
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->get('per_page', 10);
        $companies = $this->service->paginate(request()->all())->withQueryString();
        return Inertia::render('company/Index', [
            'companies' => $companies,
            'filters' => [
                'search' => $request->search,
                'sort_field' => $sortField,
                'sort_order' => $sortOrder,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function store(CompanyCreationRequest $request)
    {
        $validated = $request->validated();
        $this->service->create($validated);
        return redirect()->back()->with('success', 'Company created successfully.');
    }

    public function show($id)
    {
        return $this->service->detail($id);
    }

    public function update(CompanyUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $this->service->update($id, $validated);
        return redirect()->back()->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->back()->with('success', 'Company deleted successfully.');
    }
    
}