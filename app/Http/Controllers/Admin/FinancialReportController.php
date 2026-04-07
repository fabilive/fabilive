<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Services\CommissionReportingService;
use Illuminate\Http\Request;

class FinancialReportController extends AdminBaseController
{
    protected $reportingService;

    public function __construct(CommissionReportingService $reportingService)
    {
        parent::__construct();
        $this->reportingService = $reportingService;
    }

    /**
     * Display the financial summary and reconciliation report.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $summary = $this->reportingService->getCommissionSummary($startDate, $endDate);
        $reconciliationData = $this->reportingService->getReconciliationData($startDate, $endDate);

        return view('admin.reports.financial', compact('summary', 'reconciliationData', 'startDate', 'endDate'));
    }

    /**
     * Export reconciliation data to CSV.
     */
    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = $this->reportingService->getReconciliationData($startDate, $endDate);

        $filename = 'reconciliation_report_'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order Number', 'Gross Amount', 'Admin Commission', 'Rider Fee', 'Status', 'Date']);

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row['order_number'],
                    $row['gross_amount'],
                    $row['admin_commission'],
                    $row['rider_fee'],
                    $row['delivery_job_status'],
                    $row['date'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
