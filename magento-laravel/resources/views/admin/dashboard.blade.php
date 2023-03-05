@extends('layouts.admin')

@section('content')
<div class="dashboard-content">
    <h1>Dashboard</h1>
    <a href="{{ route('admin.logout') }}">Logout</a>

    <div class="report">
        <section class="stats">
            <h2 class="h2-title">Sales Report</h2>
            <section class="stats level-2 border-bottom">
                @if (empty($salesToday))
                <div class="font-bold">No sales report for today.</div>
                @else
                
                <div class="row w-full py-1">
                    <div class="col-header" style="border: 1px solid #000;">
                        Total Sales Today
                    </div>
                    <div class="col-data" style="border: 1px solid #000;">
                        {{ $salesToday->order_amount_total }}
                    </div>
                </div>
                <div class="row w-full py-1">
                    <div class="col-header">
                        Number of Orders Today
                    </div>
                    <div class="col-data">
                        {{ $salesToday->orders_total }}
                    </div>
                </div>
                @endif

            </section>

            <section class="stats level-2">
                <div class="font-bold w-full">Recent report prior to today</div>
                <div class="row w-full py-1">
                    <div class="col-header">Date</div>
                    <div class="col-data">
                        {{ $salesPriorToToday->date_today }}
                    </div>
                </div>
                <div class="row w-full py-1">
                    <div class="col-header">
                        Total Sales Amount
                    </div>
                    <div class="col-data">
                        {{ $salesPriorToToday->order_amount_total }}
                    </div>
                </div>
                <div class="row w-full py-1">
                    <div class="col-header">
                        Number of Orders
                    </div>
                    <div class="col-data">
                        {{ $salesPriorToToday->orders_total }}
                    </div>
                </div>
            </section>

        </section>

        <section class="stats">
            <section class="stats level-2">
                <h2 class="h2-title">Best Sellers</h2>
                <div class="row py-1 w-full">
                    <div class="col-header half text-left">
                        Name
                    </div>
                    <div class="col-header half text-center">Total Ordered</div>
                </div>
                @if (!empty($bestsellers))
                    @foreach ($bestsellers as $bestseller)
                    <div class="row py-1 w-full">
                        <div class="col-data half text-left">
                            {{ $bestseller->name }}
                        </div>
                        <div class="col-data half text-center">
                            {{ $bestseller->sum_ordered }}
                        </div>
                    </div>
                    @endforeach
                @endif
            </section>
        </section>

        <section class="stats">
            @if (!empty($topCustomers))
            
            <section class="stats level-2">
                <h2 class="h2-title">Top Customers</h2>

                <div class="row py-1 full">
                    <div class="col-header half text-left">Email</div>
                    <div class="col-header half text-left">Number of Orders</div>
                </div>

                @foreach ($topCustomers as $topCustomer)
                <div class="row py-1 full">
                    <div class="col-data half text-left">
                        {{ $topCustomer->customer_email }}
                    </div>
                    <div class="col-data half text-center">
                        {{ $topCustomer->total_orders }}
                    </div>
                </div>
                @endforeach
            </section>

            @endif
        </section>
    </div>
</div>

@endsection