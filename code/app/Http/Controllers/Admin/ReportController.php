<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportsExport;
use App\Http\Controllers\Controller;
use App\Services\Admin\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function bookingsPerProvider(Request $request): JsonResponse
    {
        return response()->json(
            $this->reportService->bookingsPerProvider($request->all())
        );
    }

    public function serviceStatusRate(Request $request): JsonResponse
    {
        return response()->json(
            $this->reportService->serviceStatusRate($request->all())
        );
    }

    public function peakHours(Request $request): JsonResponse
    {
        return response()->json(
            $this->reportService->peakHours($request->all())
        );
    }

    public function averageBookingDuration(Request $request): JsonResponse
    {
        return response()->json(
            $this->reportService->averageBookingDuration($request->all())
        );
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        $fileName = 'all_reports_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        $path = 'reports/' . $fileName;

        Excel::store(new ReportsExport($this->reportService, $filters), $path, 'public');

        $url = Storage::disk('public')->url($path);
        
        return response()->json([
            'success' => true,
            'file' => $url,
        ]);
    }
}
