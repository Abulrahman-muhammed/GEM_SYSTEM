<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PlanService
{
    /**
     * جلب قائمة الخطط مع البحث والـ pagination.
     */
    public function list(?string $search = null, int $perPage = 32): LengthAwarePaginator
    {
        return Plan::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * إنشاء خطة جديدة.
     */
    public function create(array $data): Plan
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? false;

            return Plan::create($data);
        });
    }

    /**
     * تحديث خطة موجودة.
     */
    public function update(Plan $plan, array $data): Plan
    {
        return DB::transaction(function () use ($plan, $data) {
            $data['status'] = $data['status'] ?? false;

            $plan->update($data);

            return $plan->fresh();
        });
    }

    /**
     * حذف خطة.
     */
    public function delete(Plan $plan): bool
    {
        return (bool) $plan->delete();
    }
}