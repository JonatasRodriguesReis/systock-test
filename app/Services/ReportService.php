<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ReportService {
    public function __construct(
        protected ReportRepositoryInterface $reportRepository
    ) {}

    //limit and offset
    public function execute( int $limit, int $offset) {
        return $this->reportRepository->generateReport($limit, $offset);
    }
}
