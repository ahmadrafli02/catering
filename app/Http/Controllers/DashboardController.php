<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\MenuItem;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'customers' => Customer::count(),
            'orders' => Order::count(),
            'menu_items' => MenuItem::count(),
            'upcoming_orders' => Order::whereDate('event_date', '>=', now()->toDateString())
                ->orderBy('event_date')
                ->take(5)
                ->get(),
        ];

        return view('dashboard', $stats);
    }
}
