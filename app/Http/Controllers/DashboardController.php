<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimetrackContact;
use App\Models\BidtrackContact;


class DashboardController extends Controller
{
    public function index()
    {
        $totalBidtrackUsers = BidtrackContact::count();
        $totalTimetrackUsers = TimetrackContact::count();

        return view('home', compact('totalBidtrackUsers', 'totalTimetrackUsers'));
    }
}
