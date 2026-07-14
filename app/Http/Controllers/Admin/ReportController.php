<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MembersExport;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService)
    {
    }

    /**
     * صفحة التقارير — بالـ Tabs الأربعة.
     */
    public function index(Request $request): View
    {
        $membersReport     = $this->reportService->membersReport($request->input('members_search'));
        $attendanceReport  = $this->reportService->attendanceReport($request->input('attendance_from'), $request->input('attendance_to'));
        $offersReport      = $this->reportService->offersReport();
        $revenueReport     = $this->reportService->revenueReport(
            $request->input('revenue_period', 'today'),
            $request->input('revenue_from'),
            $request->input('revenue_to'),
        );

        return view('admin.reports.index', compact(
            'membersReport',
            'attendanceReport',
            'offersReport',
            'revenueReport',
        ));
    }

    /**
     * تصدير تقرير الأعضاء Excel.
     */
    // public function exportMembersExcel(Request $request): BinaryFileResponse
    // {
    //     return Excel::download(
    //         new MembersExport($request->input('members_search')),
    //         'members-report-' . now()->format('Y-m-d') . '.xlsx'
    //     );
    // }

    /**
     * تصدير تقرير الأعضاء PDF.
     */
    public function exportMembersPdf(Request $request)
    {
        $members = $this->reportService->membersForExport($request->input('members_search'));

        $pdf = Pdf::loadView('admin.reports.members', compact('members'));

        return $pdf->download('members-report-' . now()->format('Y-m-d') . '.pdf');
    }
}