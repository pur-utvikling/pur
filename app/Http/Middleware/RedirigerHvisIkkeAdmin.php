<?php namespace Pur\Http\Middleware;

use Closure;

class RedirigerHvisIkkeAdmin {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if( ! $request->user()->erAdmin())
        {
            return redirect('/home'); // TODO: Rediriger til modulens hjemmeside + Lag 'flash' med 'Ingen tilgang e.l.
        }

        return $next($request);
	}

}
