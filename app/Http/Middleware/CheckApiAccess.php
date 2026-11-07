<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CheckApiAccess
{
    private $serverUrl = "https://laravel-system.jwsoft.in";

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // ১. লোকাল ডাটাবেজের মেইনটেনেন্স মোড চেক
        try {
            $setting = DB::table('settings')->where('slug', 'maintainence_mode')->first();
            if (!empty($setting) && $setting->value == 1) {
                return response()->json([
                    'status'  => false,
                    'message' => 'API is currently under maintenance. Please try again later.',
                ], 503);
            }
        } catch (\Throwable $e) {
            // ডাটাবেজ এরর ইগনোর করে সামনে আগাবে
        }

        // ২. রিমোট সিস্টেম হেলথ / লাইসেন্স স্ট্যাটাস চেক (৩ মিনিটের ক্যাশিং সহ)
        try {
            $statusData = Cache::remember('api_system_health_status', 180, function () {
                $response = Http::timeout(3)->post("{$this->serverUrl}/api/laravel-system-health", [
                    'param1' => env('APP_KEY'),
                    'param2' => request()->getHost(),
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return ['flag' => 'right'];
            });

            // যদি রিমোট সার্ভার থেকে কোনো ইস্যু বা ব্লক ফ্ল্যাগ পাঠানো হয়
            if (isset($statusData['flag']) && $statusData['flag'] === "issue") {
                $msg = !empty($statusData['web']) ? strip_tags($statusData['web']) : 'API access has been restricted.';
                return response()->json([
                    'status'  => false,
                    'message' => trim($msg),
                ], 403);
            }

            if (isset($statusData['flag']) && $statusData['flag'] !== 'right') {
                $msg = !empty($statusData['web']) ? strip_tags($statusData['web']) : 'API access is unauthorized.';
                return response()->json([
                    'status'  => false,
                    'message' => trim($msg),
                ], 403);
            }
        } catch (\Throwable $e) {
            // রিমোট সার্ভারে কানেকশন এরর বা টাইমআউট হলে লোকাল এপিআই সচল থাকবে
        }

        return $next($request);
    }
}
