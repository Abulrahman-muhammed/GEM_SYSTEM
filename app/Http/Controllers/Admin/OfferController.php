<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function __construct(protected SearchService $searchService )  
    {
    }
 
    /**
     *  Display offers.
     */
    public function index(Request $request): View
    {
        $offers = $this->searchService->apply(
            Offer::query(),
            $request->search,
            [
                'name',
            ]
        )->paginate(request('per_page', 10));
 
        return view('admin.offers.index', compact('offers'));
    }
 
    /**
     * Display offers create page.
     */
    public function create(): View
    {
        return view('admin.offers.create');
    }
 
    /**
     *  Store offer.
     */
    public function store(StoreOfferRequest $request): RedirectResponse
    {
        Offer::create($request->validated());
        return redirect()
            ->route('offers.index')
            ->with('success', 'تم إضافة العرض بنجاح.');
    }
 
    /**
     * Display offers edit page.
     */
    public function edit(Offer $offer): View
    {
        return view('admin.offers.edit', compact('offer'));
    }
 
    /**
     * Update offer.
     */
    public function update(UpdateOfferRequest $request, Offer $offer): RedirectResponse
    {
        $offer->update($request->validated());
 
        return redirect()
            ->route('offers.index')
            ->with('success', 'تم تحديث العرض بنجاح.');
    }
 
    /**
     * Delete offer.
     */
    public function destroy(Offer $offer): RedirectResponse
    {
        $offer->delete();
        return redirect()
            ->route('offers.index')
            ->with('success', 'تم حذف العرض بنجاح.');
    }
}
