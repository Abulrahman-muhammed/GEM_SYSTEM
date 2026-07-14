<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
class PlanController extends Controller
{
    public function __construct(protected PlanService $planService)
    {
    }
 
    /**
     * عرض قائمة الخطط.
     */
    public function index(): View
    {
        $plans = $this->planService->list(
            search: request('search'),
            perPage: (int) request('per_page', 32),
        );
 
        return view('admin.plans.index', compact('plans'));
    }
 
    /**
     * عرض فورم إضافة خطة جديدة.
     */
    public function create(): View
    {
        return view('admin.plans.create');
    }
 
    /**
     * حفظ خطة جديدة.
     */
    public function store(StorePlanRequest $request): RedirectResponse
    {
        $this->planService->create($request->validated());
 
        return redirect()
            ->route('plans.index')
            ->with('success', 'تم إضافة الخطة بنجاح.');
    }
 
    /**
     * عرض فورم تعديل خطة.
     */
    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }
 
    /**
     * تحديث بيانات خطة.
     */
    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $this->planService->update($plan, $request->validated());
 
        return redirect()
            ->route('plans.index')
            ->with('success', 'تم تحديث الخطة بنجاح.');
    }
 
    /**
     * حذف خطة.
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        $this->planService->delete($plan);
 
        return redirect()
            ->route('plans.index')
            ->with('success', 'تم حذف الخطة بنجاح.');
    }
}
