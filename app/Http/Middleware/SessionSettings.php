<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionSettings
{
    /**
     * Handle an incoming request.
     * \App\Http\Middleware\SessionSettings::class
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $setRoutesOrder = request('setRoutesOrder');
        if ($setRoutesOrder) {
            if ($setRoutesOrder === 'date_desc') {
                session(['routesOrder' => 'created_at DESC']);
            }elseif ($setRoutesOrder === 'date_asc') {
                session(['routesOrder' => 'created_at ASC']);
            }elseif ($setRoutesOrder === 'title_asc') {
                session(['routesOrder' => 'name ASC']);
            }
        }

        $setRoutesPerPage = request('setRoutesPerPage');
        if ($setRoutesPerPage) {
            if ($setRoutesPerPage === '10') {
                session(['routesPerPage' => 10]);
            }elseif ($setRoutesPerPage === '20') {
                session(['routesPerPage' => 20]);
            }elseif ($setRoutesPerPage === '50') {
                session(['routesPerPage' => 50]);
            }elseif ($setRoutesPerPage === '100') {
                session(['routesPerPage' => 100]);
            }
        }

        return $next($request);
    }
}
