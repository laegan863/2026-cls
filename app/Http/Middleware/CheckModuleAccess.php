<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Module;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $moduleSlug  The slug of the module to check
     * @param  string  $permission  The permission to check (view, create, edit, delete)
     */
    public function handle(Request $request, Closure $next, string $moduleSlug, string $permission = 'view'): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('auth.login')
                ->with('error', 'Please login to access this page.');
        }

        // Admin bypass - they have full access
        if ($user->role && $user->role->slug === 'admin') {
            return $next($request);
        }

        // Find the module by slug
        $module = Module::where('slug', $moduleSlug)->first();

        if (!$module) {
            abort(404, 'Module not found.');
        }

        // Check if module is coming soon
        if ($module->is_coming_soon) {
            return redirect()->back()
                ->with('error', 'This module is coming soon.');
        }

        // Check if module is active
        if (!$module->is_active) {
            return redirect()->back()
                ->with('error', 'This module is currently inactive.');
        }

        // Check if user has access to the module
        if (!Module::userHasAccess($user, $moduleSlug, 'can_' . $permission)) {
            abort(403, 'You do not have permission to ' . $permission . ' in this module.');
        }

        return $next($request);
    }
}
