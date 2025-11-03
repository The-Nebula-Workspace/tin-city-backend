<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DevOpsController extends Controller
{
    public function clearCache(Request $request)
    {
        $providedToken = $request->header('X-DevOps-Token');
        $expectedToken = config('devops.token');

        if (!$expectedToken || !hash_equals((string) $expectedToken, (string) $providedToken)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        Artisan::call('optimize:clear');

        $output = Artisan::output();
        Log::info('optimize:clear executed via DevOps endpoint');

        return response()->json([
            'message' => 'Cache cleared',
            'output' => $output,
        ]);
    }
}
