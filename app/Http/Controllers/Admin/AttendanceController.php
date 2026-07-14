<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;


class AttendanceController extends Controller
{
    public function scanPage()
    {
        return view('admin.attendances.scan');
    }
 
    /**
     * استقبال الباركود من صفحة السكانر.
     * لو العضو مالوش تسجيل حضور مفتوح اليوم -> تسجيل حضور.
     * لو عنده تسجيل حضور مفتوح اليوم -> تسجيل انصراف.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'barcode' => ['required', 'string'],
        ]);
 
        $member = Member::where('barcode', $request->barcode)->first();
 
        if (! $member) {
            return response()->json([
                'ok'      => false,
                'message' => 'الكود ده مش تابع لأي عضو.',
            ], 404);
        }
 
        if (! $member->status) {
            return response()->json([
                'ok'      => false,
                'message' => 'حساب العضو غير نشط، راجع الإدارة.',
                'member'  => [
                    'name'  => $member->full_name,
                    'photo' => $member->photo_url,
                ],
            ], 403);
        }
 
        return DB::transaction(function () use ($member) {
            $attendance = Attendance::query()
                ->where('member_id', $member->id)
                ->today()
                ->stillIn()
                ->lockForUpdate()
                ->first();
 
            // مفيش تسجيل مفتوح اليوم -> يبقى ده تسجيل حضور جديد
            if (! $attendance) {
                $attendance = Attendance::create([
                    'member_id' => $member->id,
                    'date'      => today(),
                    'check_in'  => now(),
                ]);
 
                return response()->json([
                    'ok'     => true,
                    'action' => 'check_in',
                    'message' => 'تم تسجيل الحضور بنجاح.',
                    'member' => [
                        'name'     => $member->full_name,
                        'photo'    => $member->photo_url,
                        'barcode'  => $member->barcode,
                    ],
                    'time' => $attendance->check_in->format('h:i A'),
                ]);
            }
 
            // فيه تسجيل مفتوح -> يبقى ده انصراف
            $attendance->update([
                'check_out' => now(),
            ]);
 
            return response()->json([
                'ok'     => true,
                'action' => 'check_out',
                'message' => 'تم تسجيل الانصراف بنجاح.',
                'member' => [
                    'name'    => $member->full_name,
                    'photo'   => $member->photo_url,
                    'barcode' => $member->barcode,
                ],
                'time'     => $attendance->check_out->format('h:i A'),
                'duration' => $attendance->duration_label,
            ]);
        });
    }
 
    /**
     * سجل الحضور والانصراف (index) مع فلاتر بحث وتاريخ.
     */
    public function index(Request $request)
    {
        $query = Attendance::query()->with('member')->latest('date')->latest('check_in');
 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }
 
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            // افتراضيًا يوم النهاردة
            $query->whereDate('date', today());
        }
 
        if ($request->filled('status')) {
            if ($request->status === 'in') {
                $query->stillIn();
            } elseif ($request->status === 'out') {
                $query->whereNotNull('check_out');
            }
        }
 
        $attendances = $query->paginate(20)->withQueryString();
 
        $stats = [
            'total_today'   => Attendance::query()->today()->count(),
            'still_in'      => Attendance::query()->today()->stillIn()->count(),
            'checked_out'   => Attendance::query()->today()->whereNotNull('check_out')->count(),
        ];
 
        return view('admin.attendances.index', compact('attendances', 'stats'));
    }
 
    /**
     * حذف سجل حضور (لو حصل خطأ في التسجيل).
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
 
        return redirect()
            ->route('admin.attendances.index')
            ->with('success', 'تم حذف سجل الحضور بنجاح.');
    }
 
    /**
     * تسجيل انصراف يدوي من صفحة السجل (لو حد نسي يعمل سكان).
     */
    public function forceCheckout(Attendance $attendance)
    {
        if ($attendance->check_out) {
            return back()->with('error', 'تم تسجيل الانصراف بالفعل.');
        }
 
        $attendance->update(['check_out' => now()]);
 
        return back()->with('success', 'تم تسجيل الانصراف يدويًا.');
    }
}
