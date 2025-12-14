<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Film;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_films' => Film::count(),
            'total_categories' => Category::count(),
            'active_subscriptions' => User::where('subscription_expires_at', '>', now())->count(),
            'recent_users' => User::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_activities' => ActivityLog::orderBy('created_at', 'desc')->limit(10)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
