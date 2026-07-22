<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Attendance;
use App\Models\Payment;

class MemberService
{
    public function __construct(
        protected SearchService $searchService,
        private BarcodeService $barcodeService,
    ) {}

    /**
     * Get members list.
     */
    public function list(Request $request): LengthAwarePaginator
    {
        $query = Member::query();

        $this->searchService->apply(
            $query,
            $request->input('search'),
            [
                'full_name',
                'phone',
                'barcode',
            ]
        );

        return $query
            ->latest()
            ->paginate(
                $request->integer('per_page', 10)
            )
            ->withQueryString();
    }

    /**
     * Create new member.
     */
    public function create(array $data): Member
    {
        $this->storePhoto($data);

        return Member::create($data);
    }

    /**
     * Update member.
     */
    public function update(Member $member, array $data): Member
    {
        $this->storePhoto($data, $member);

        $member->update($data);

        return $member->refresh();
    }

    /**
     * Show member.
     */
        public function profile(Member $member): array
        {
            $member->load([
                'subscriptions' => fn ($query) => $query
                    ->with(['plan', 'payments'])
                    ->latest('start_date'),
            ]);

            return [
                'member' => $member,
                'barcode' => $this->barcodeService->renderSvg($member->barcode),
                'currentSubscription' => $this->currentSubscription($member),
                'recentPayments' => $this->recentPayments($member),
                'recentAttendances' => $this->recentAttendances($member),
                'attendanceStats' => $this->attendanceStats($member),
            ];
        }
        private function currentSubscription(Member $member)
        {
            $today = now()->startOfDay();

            return $member->subscriptions
                ->sortByDesc('end_date')
                ->first(fn ($subscription) =>
                    $subscription->end_date &&
                    $subscription->end_date->gte($today)
                )
                ?? $member->subscriptions
                    ->sortByDesc('end_date')
                    ->first();
        }

        private function recentPayments(Member $member)
        {
            return Payment::query()
                ->whereHas('subscription', function ($query) use ($member) {
                    $query->where('member_id', $member->id);
                })
                ->latest('payment_date')
                ->take(5)
                ->get();
        }

        private function recentAttendances(Member $member)
        {
            return $member->attendances()
                ->latest('date')
                ->latest('check_in')
                ->limit(5)
                ->get();
        }

        private function attendanceStats(Member $member): array
        {
            $query = $member->attendances();

            return [
                'total_visits' => (clone $query)->count(),
                'last_visit' => (clone $query)
                    ->latest('check_in')
                    ->value('check_in'),
            ];
        }
    /**
     * Delete member.
     */
    public function delete(Member $member): void
    {
        $this->deletePhoto($member);

        $member->delete();
    }

    /**
     * Upload photo.
     */
    private function storePhoto(array &$data, ?Member $member = null): void
    {
        if (! isset($data['photo'])) {
            return;
        }

        if ($member?->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $data['photo'] = $data['photo']
            ->store('members', 'public');
    }

    /**
     * Delete photo.
     */
    private function deletePhoto(Member $member): void
    {
        if (! $member->photo) {
            return;
        }

        Storage::disk('public')->delete($member->photo);
    }
}