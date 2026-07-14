<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OfferService;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function __construct(protected OfferService $offerService)
    {
    }
 
    /**
     * عرض قائمة العروض.
     */
    public function index(): View
    {
        $offers = $this->offerService->list(
            search: request('search'),
            perPage: (int) request('per_page', 32),
        );
 
        return view('admin.offers.index', compact('offers'));
    }
 
    /**
     * عرض فورم إضافة عرض جديد.
     */
    public function create(): View
    {
        return view('admin.offers.create');
    }
 
    /**
     * حفظ عرض جديد.
     */
    public function store(StoreOfferRequest $request): RedirectResponse
    {
        $this->offerService->create($request->validated());
 
        return redirect()
            ->route('offers.index')
            ->with('success', 'تم إضافة العرض بنجاح.');
    }
 
    /**
     * عرض فورم تعديل عرض.
     */
    public function edit(Offer $offer): View
    {
        return view('admin.offers.edit', compact('offer'));
    }
 
    /**
     * تحديث بيانات عرض.
     */
    public function update(UpdateOfferRequest $request, Offer $offer): RedirectResponse
    {
        $this->offerService->update($offer, $request->validated());
 
        return redirect()
            ->route('offers.index')
            ->with('success', 'تم تحديث العرض بنجاح.');
    }
 
    /**
     * حذف عرض.
     */
    public function destroy(Offer $offer): RedirectResponse
    {
        $this->offerService->delete($offer);
 
        return redirect()
            ->route('offers.index')
            ->with('success', 'تم حذف العرض بنجاح.');
    }
}
