<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DevOpsController extends Controller
{
    /**
     * Clear application caches
     *
     * Secure DevOps endpoint to run `optimize:clear`.
     *
     * @group DevOps
     * @authenticated
     * @header X-DevOps-Token required The DevOps token used to authorize the request.
     *
     * @response 200 {"message":"Cache cleared","output":"...artisan output..."}
     * @response 401 {"message":"Unauthorized"}
     */
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
