<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ReportService {
    public function __construct(
        protected ReportRepositoryInterface $reportRepository
    ) {}

    //limit and offset
    public function executeSQLReport( int $limit, int $offset) {
        return $this->reportRepository->generateReport($limit, $offset);
    }

    public function executeRankingReport() {
        return $this->reportRepository->generateRankingReport();
    }

    public function executePriceRangeReport() {
        return $this->reportRepository->generatePriceRangeReport();
    }
}
