<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        $activities = Activity::with('user')
            ->latest()
            ->paginate(20);

        return view('activities.index', compact('activities'));
    }
}
