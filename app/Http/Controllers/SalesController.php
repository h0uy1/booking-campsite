<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('slot.tent')->whereIn('status', ['confirmed', 'completed'])->get();
        
        $yearlySales = [];
        $monthlySalesByYear = [];
        $tentSales = [];
        $yearlyCustomers = [];
        $yearlyBookings = [];
        $totalAllTime = 0;
        
        $currentYearValue = now()->format('Y');

        foreach ($bookings as $booking) {
            // We use created_at as the date the sale was actually realized/paid
            $date = $booking->created_at ? Carbon::parse($booking->created_at) : Carbon::parse($booking->check_in_date);
            $year = $date->format('Y');
            $month = $date->format('n'); // 1-12

            $price = (float) $booking->total_price;
            $totalAllTime += $price;

            if (!isset($yearlySales[$year])) {
                $yearlySales[$year] = 0;
                $monthlySalesByYear[$year] = array_fill(1, 12, 0); // Initialize 12 months with 0
                $yearlyCustomers[$year] = [];
                $yearlyBookings[$year] = 0;
            }

            $yearlySales[$year] += $price;
            $monthlySalesByYear[$year][$month] += $price;
            $yearlyBookings[$year]++;
            
            $customerIdentifier = $booking->user_id ?? $booking->customer_email;
            if ($customerIdentifier) {
                $yearlyCustomers[$year][$customerIdentifier] = true;
            }

            $tentName = $booking->slot->tent->name ?? 'Standard Tent';
            if (!isset($tentSales[$year])) $tentSales[$year] = [];
            if (!isset($tentSales[$year][$tentName])) $tentSales[$year][$tentName] = 0;
            $tentSales[$year][$tentName] += $price;
        }

        // Always ensure current year exists, even if 0 revenue so the UI doesn't break
        if (!isset($yearlySales[$currentYearValue])) {
            $yearlySales[$currentYearValue] = 0;
            $monthlySalesByYear[$currentYearValue] = array_fill(1, 12, 0);
        }

        // Sort years chronologically ascending for the chart
        ksort($yearlySales);

        // Calculate KPI values
        $selectedYear = (int) $request->query('year', $currentYearValue);
        
        if (!isset($yearlySales[$selectedYear])) {
             $selectedYear = (int) $currentYearValue;
        }

        $currentMonthValue = (int) now()->format('n');
        $selectedYearTotal = $yearlySales[$selectedYear] ?? 0;
        $currentMonthTotal = $monthlySalesByYear[$selectedYear][$currentMonthValue] ?? 0;

        // Comparative Metrics
        $prevYearValue = $selectedYear - 1;
        $prevYearTotal = $yearlySales[$prevYearValue] ?? 0;
        $yoYGrowth = $prevYearTotal > 0 ? (($selectedYearTotal - $prevYearTotal) / $prevYearTotal) * 100 : ($selectedYearTotal > 0 ? 100 : 0);

        $lastMonthValue = $currentMonthValue - 1;
        $lastMonthYear = $selectedYear;
        if ($lastMonthValue === 0) {
            $lastMonthValue = 12;
            $lastMonthYear = $selectedYear - 1;
        }
        
        // Ensure the last month's array exists before accessing
        $lastMonthTotal = 0;
        if (isset($monthlySalesByYear[$lastMonthYear])) {
            $lastMonthTotal = $monthlySalesByYear[$lastMonthYear][$lastMonthValue] ?? 0;
        }
        $moMGrowth = $lastMonthTotal > 0 ? (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100 : ($currentMonthTotal > 0 ? 100 : 0);

        $ytdCustomersCount = isset($yearlyCustomers[$selectedYear]) ? count($yearlyCustomers[$selectedYear]) : 0;
        $ytdBookingsCount = $yearlyBookings[$selectedYear] ?? 0;
        $avgRevenue = $ytdBookingsCount > 0 ? ($selectedYearTotal / $ytdBookingsCount) : 0;
        
        $tentDataForYear = $tentSales[$selectedYear] ?? [];

        $chartData = [
            'years' => array_keys($yearlySales),
            'yearly_totals' => array_values($yearlySales),
            'monthly_sales' => $monthlySalesByYear, // Contains multi-dimensional data mapping year -> months array
            'tent_labels' => array_keys($tentDataForYear),
            'tent_sales' => array_values($tentDataForYear)
        ];

        return view('admin.sales.index', compact(
            'totalAllTime', 
            'yearlySales', 
            'selectedYear', 
            'selectedYearTotal',
            'currentMonthTotal',
            'prevYearTotal',
            'yoYGrowth',
            'lastMonthTotal',
            'moMGrowth',
            'ytdCustomersCount',
            'ytdBookingsCount',
            'avgRevenue',
            'chartData'
        ));
    }
}
