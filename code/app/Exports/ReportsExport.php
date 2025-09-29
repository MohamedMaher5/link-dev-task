<?php

namespace App\Exports;

use App\Services\Admin\ReportService;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportsExport implements WithMultipleSheets
{
    protected ReportService $reportService;
    protected array $filters;

    public function __construct(ReportService $reportService, array $filters = [])
    {
        $this->reportService = $reportService;
        $this->filters = $filters;
    }

    public function sheets(): array
    {
        return [
            new SingleReportSheet('Bookings Per Provider', $this->reportService->bookingsPerProvider($this->filters)),
            new SingleReportSheet('Service Status Rate', $this->reportService->serviceStatusRate($this->filters)),
            new SingleReportSheet('Peak Hours', $this->reportService->peakHours($this->filters)),
            new SingleReportSheet('Average Booking Duration', $this->reportService->averageBookingDuration($this->filters)),
        ];
    }
}
