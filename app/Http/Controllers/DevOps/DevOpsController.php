<?php

namespace App\Http\Controllers\DevOps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DevOpsController extends Controller
{
    /**
     * Clear application caches
     *
     * Secure DevOps endpoint to run `optimize:clear`.
     *
     * @group DevOps
     *
     * @authenticated
     *
     * @header X-DevOps-Token required The DevOps token used to authorize the request.
     *
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "message": "Cache cleared",
     *   "data": {
     *     "output": "..."
     *   }
     * }
     * @response 401 scenario="Unauthorized" {
     *   "success": false,
     *   "message": "Unauthorized",
     *   "errors": null
     * }
     */
    public function clearCache(Request $request)
    {
        $providedToken = $request->header('X-DevOps-Token');
        $expectedToken = config('devops.token');

        if (! $expectedToken || ! hash_equals((string) $expectedToken, (string) $providedToken)) {
            return $this->errorResponse('Unauthorized', 401);
        }

        Artisan::call('optimize:clear');

        $output = Artisan::output();
        Log::info('optimize:clear executed via DevOps endpoint');

        return $this->successResponse(
            [
                'output' => $output,
            ],
            'Cache cleared'
        );
    }
}
