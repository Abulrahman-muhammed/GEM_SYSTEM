<?php

namespace App\Services;

use App\Models\Offer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OfferService
{
    /**
     * جلب قائمة العروض مع البحث والـ pagination.
     */
    public function list(?string $search = null, int $perPage = 32): LengthAwarePaginator
    {
        return Offer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * إنشاء عرض جديد.
     */
    public function create(array $data): Offer
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? false;

            return Offer::create($data);
        });
    }

    /**
     * تحديث عرض موجود.
     */
    public function update(Offer $offer, array $data): Offer
    {
        return DB::transaction(function () use ($offer, $data) {
            $data['status'] = $data['status'] ?? false;

            $offer->update($data);

            return $offer->fresh();
        });
    }

    /**
     * حذف عرض.
     */
    public function delete(Offer $offer): bool
    {
        return (bool) $offer->delete();
    }
}