<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Services\MemberService;

class MemberController extends Controller
{
    private MemberService $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
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
    // public function show(string $id)
    // {
    //     $member = $this->memberService->find($id);

    //     return view('admin.members.show', compact('member'));
    // }

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
}
