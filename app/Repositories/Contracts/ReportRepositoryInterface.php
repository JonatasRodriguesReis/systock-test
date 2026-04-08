<?php

namespace App\Repositories\Contracts;

interface ReportRepositoryInterface {
    public function generateReport(int $limit, int $offset);
}
