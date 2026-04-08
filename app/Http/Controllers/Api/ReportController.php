<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Services\ReportService;

class ReportController extends Controller
{

    public function __construct(protected ReportService $reportService) {}

    public function sqlReport(Request $request)
    {
        try {
            $limit = $request->query('per_page', 10);
            $offset = ($request->query('page', 1) - 1) * $limit;

            $results = $this->reportService->execute($limit, $offset);

            return response()->json([
                'status' => 'success',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Report Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao gerar relatório.'], 500);
        }
    }
}
