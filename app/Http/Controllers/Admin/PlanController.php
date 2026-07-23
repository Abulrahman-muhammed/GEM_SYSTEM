<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use App\Services\SearchService;
class PlanController extends Controller
{
    public function __construct( protected SearchService $searchService)
    {
    }
 
    /**
     *  list plans
     */
    public function index(Request $request): View
    {
        $plans = $this->searchService->apply(
            Plan::query(),
            $request->search,
            [
                'name',
            ]
        )->paginate($request->integer('per_page', 10));
        return view('admin.plans.index', compact('plans'));
    }
 
    /**
     * show create plan form.
     */
    public function create(): View
    {
        return view('admin.plans.create');
    }
 
    /**
     * store new plan.
     */
    public function store(StorePlanRequest $request): RedirectResponse
    {
        Plan::create($request->validated());
        return redirect()
            ->route('plans.index')
            ->with('success', 'تم إضافة الخطة بنجاح.');
    }
 
    /**
     * show edit plan form
     */
    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }
 
    /**
     * update plan
     */
    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());
        return redirect()
            ->route('plans.index')
            ->with('success', 'تم تحديث الخطة بنجاح.');
    }
 
    /**
     * delete plan
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();
        return redirect()
            ->route('plans.index')
            ->with('success', 'تم حذف الخطة بنجاح.');
    }
}
