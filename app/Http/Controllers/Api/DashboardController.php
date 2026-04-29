<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    #[OA\Get(
        path: "/dashboard/statistic",
        summary: "Get aggregation metrics",
        security: [["sanctum" => []]],
        tags: ["Dashboard"],
        responses: [
            new OA\Response(response: "200", description: "Success data response")
        ]
    )]
    public function getStatistic(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Assignment::query();

        if ($user->role) {
            $roleCode = $user->role->code;
            if ($roleCode === 'TECH') {
                $query->where('technician_id', $user->id);
            }
        }

        $totalAssignments = clone $query;
        $total = $totalAssignments->count();

        $completedAssignments = clone $query;
        $completed = $completedAssignments->whereNotNull('completed_at')->count();

        $pendingAssignments = clone $query;
        $pending = $pendingAssignments->whereNull('completed_at')->count();

        $averageRatingQuery = clone $query;
        $averageRating = $averageRatingQuery->avg('rating');

        // Can also group by status_id if more precise status breakdowns are needed
        $statusBreakdownQuery = clone $query;
        $statusBreakdown = $statusBreakdownQuery->selectRaw('status_id, count(*) as count')
            ->groupBy('status_id')
            ->pluck('count', 'status_id');

        return response()->json([
            'message' => 'success',
            'data' => [
                'total_assignments' => $total,
                'completed_assignments' => $completed,
                'pending_assignments' => $pending,
                'average_rating' => round($averageRating ?? 0, 1),
                'status_breakdown' => $statusBreakdown,
            ],
        ]);
    }
}
