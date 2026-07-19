<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Services\MemberService;
use App\Services\BarcodeService;

use App\Models\Attendance;
class MemberController extends Controller
{
    private MemberService $memberService;
    private BarcodeService $barcodeService;
    public function __construct(MemberService $memberService, BarcodeService $barcodeService)
    {
        $this->memberService = $memberService;
        $this->barcodeService = $barcodeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::latest()->paginate(10);
        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        $this->memberService->create($validated);

        return redirect()
            ->route('members.index')
            ->with('success', 'تم إضافة العضو بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member, BarcodeService $barcodeService)
    {
        $member->load([
            'subscriptions' => fn ($q) => $q->with(['plan', 'payments'])->latest('start_date'),
        ]);
    
        $today = now()->startOfDay();
    
        // الاشتراك الحالي = الأقرب لتاريخ نهاية لسه ماخلصش، وإلا آخر اشتراك موجود
        $currentSubscription = $member->subscriptions
            ->sortByDesc('end_date')
            ->first(fn ($s) => $s->end_date && $s->end_date->gte($today))
            ?? $member->subscriptions->sortByDesc('end_date')->first();
    
        // آخر 5 مدفوعات من كل اشتراكات العضو
        $recentPayments = $member->subscriptions
            ->flatMap->payments
            ->sortByDesc('payment_date')
            ->take(5)
            ->values();
        $recentAttendances = Attendance::where('member_id', $member->id)
        ->latest('date')
        ->latest('check_in')
        ->limit(5)
        ->get();
        $attendanceStats = [
        'total_visits' => Attendance::where('member_id', $member->id)->count(),
        'last_visit'   => Attendance::where('member_id', $member->id)
            ->latest('check_in')
            ->value('check_in'),
        ];
        return view('admin.members.show', [
            'member'              => $member,
            'barcode'             => $barcodeService->renderSvg($member->barcode),
            'currentSubscription' => $currentSubscription,
            'recentPayments'      => $recentPayments,
            'recentAttendances'   => $recentAttendances,
            'attendanceStats'     => $attendanceStats,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $validated = $request->validated();

        $this->memberService->update($member, $validated);

        return redirect()
            ->route('members.index')
            ->with('success', 'تم تحديث بيانات العضو بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $this->memberService->delete($member);

        return redirect()
            ->route('members.index')
            ->with('success', 'تم حذف العضو بنجاح');
    }
    public function card(Member $member, BarcodeService $barcodeService)
    {
        return view('admin.pdf.membership-card', [
            'member' => $member,
            'barcode' => $barcodeService->renderSvg($member->barcode),
        ]);
    }
}
