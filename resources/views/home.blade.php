@extends('layouts.dashboard', ['page' => 'dashboard'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/dataTables.bootstrap4.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/datatables.responsive.bootstrap4.min.css')}}" />
@endsection
@section('content')
<div id="app" class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Dashboard</h1>
            <div class="separator mb-5"></div>
        </div>
        <div class="col-lg-12 col-xl-12">
            <div class="icon-cards-row col-lg-8">
                <div class="owl-container">
                            <div class="owl-carousel dashboard-numbers">
                                <a href="#" class="card">
                                    <div class="card-body text-center">
                                        <i class="simple-icon-clock"></i>
                                        <p class="card-text mb-0">Pending Orders</p>
                                        <p class="lead text-center" id="pending">{{$pending_orders->count()}}</p>
                                    </div>
                                </a>
                                <a href="#" class="card">
                                    <div class="card-body text-center">
                                        <i class="simple-icon-refresh"></i>
                                        <p class="card-text mb-0">In Progress</p>
                                        <p class="lead text-center" id="in-progress" style="display:none;">11</p>
                                        <div class="lds-dual-ring mr-auto ml-auto"></div>
                                    </div>
                                </a>

                                <a href="#" class="card">
                                    <div class="card-body text-center">
                                        <i class="simple-icon-check"></i>
                                        <p class="card-text mb-0">Completed Today</p>
                                        <p class="lead text-center" id="completed" style="display:none;">11</p>
                                        <div class="lds-dual-ring mr-auto ml-auto"></div>
                                    </div>
                                </a>

                                <a href="#" class="card">
                                    <div class="card-body text-center">
                                        <i class="simple-icon-wallet"></i>
                                        <p class="card-text mb-0">Daily Sales</p>
                                        <p class="lead text-center" id="sales" style="display:none;">11</p>
                                        <div class="lds-dual-ring mr-auto ml-auto"></div>
                                    </div>
                                </a>

                                <a href="#" class="card">
                                    <div class="card-body text-center">
                                        <i class="simple-icon-bubbles"></i>
                                        <p class="card-text mb-0">New Comments</p>
                                        <p class="lead text-center" id="comments" style="display:none;">11</p>
                                        <div class="lds-dual-ring mr-auto ml-auto"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>




                    <div class="row">
                        <div class="col-xl-6 col-lg-6 mb-4">
                            <div class="card">
                                <div class="position-absolute card-top-buttons">
                                    <button class="btn btn-header-light icon-button">
                                        <i class="simple-icon-check"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Pending Orders</h5>
                                    <div class="scroll dashboard-list-with-thumbs">
                                        @foreach($pending_orders as $order)
                                        <div class="d-flex flex-row mb-3">
                                            <a class="d-block position-relative" href="#">
                                                <img  src="/img/foods/{{$order->items[0]->image ? $order->items[0]->image : 'food-default.png' }}" alt="{{$order->items[0]->name}}" class="dashboard-pending list-thumbnail border-0" />
                                                <span class="badge badge-pill badge-theme-2 position-absolute badge-top-right">GH&cent; {{$order->total_price}}</span>
                                            </a>
                                            <div class="pl-3 pt-2 pr-2 pb-2">
                                                <a href="#">
                                                    <p class="list-item-heading">{{$order->items[0]->name}} @if($order->items->count() > 1)<span class="text-muted mb-1 text-small">and
                                                            {{$order->items->count() - 1}} other @if($order->items->count() > 2)<span>items</span> @else<span>item</span> @endif</span>@endif</p>
                                                    <div class="pr-4 d-none d-sm-block">
                                                        <p class="text-muted mb-1 text-small">{{$order->customer->firstname.' '.$order->customer->lastname.' - '.$order->address}}</p>
                                                    </div>
                                                    <div class="text-primary text-small font-weight-medium d-none d-sm-block">{{Carbon\Carbon::parse($order->created_at)->diffForHumans()}}</div>
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($pending_orders->count() <  1)
                                        <h6 class="text-muted">
                                            No pending orders currently. <a href="/orders">View previous orders</a>
                                        </h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="position-absolute card-top-buttons">

                                    <button class="btn btn-header-light icon-button" type="button" aria-expanded="false">
                                        <i class="simple-icon-refresh"></i>
                                    </button>
                                </div>
                                @if(strtolower(Auth::user()->role) != 'attendant')
                                <div class="card-body">
                                    <h5 class="card-title">This Week's Sales</h5>
                                    <div class="dashboard-line-chart">
                                        <canvas id="salesChart"></canvas>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(strtolower(Auth::user()->role) != 'attendant')
            <div class="row">
                <div class="col-sm-12 col-lg-4 mb-4">
                    <div class="card h-100 scroll" style="max-height:380px">
                        <div class="card-body">
                            <h5 class="card-title">Item Categories</h5>
                            <table class="data-table responsive nowrap mb-3"  data-order="[[ 1, &quot;desc&quot; ]]">
                                <thead>
                                    <th>Category Name</th>
                                    <th>Item Count</th>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            {{$category->name}}
                                        </td>
                                        <td>
                                            {{$category->items->count()}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                @if(strtolower(Auth::user()->role) != 'attendant')
                <div class="col-sm-12 col-lg-4 mb-4">
                    <div class="card dashboard-link-list">
                        <div class="card-body">
                            <h5 class="card-title">Most active customers this month</h5>
                            <div class="d-flex flex-row">
                                <div class="w-50">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1">
                                            <a href="#">Marble Cake</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Fruitcake</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Chocolate Cake</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Fat Rascal</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Financier</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Genoise</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Gingerbread</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Goose Breast</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Parkin</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Petit Gâteau</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Salzburger Nockerl</a>
                                        </li>
                                        <li>
                                            <a href="#">Soufflé</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="w-50">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1">
                                            <a href="#">Streuselkuchen</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Tea Loaf</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Napoleonshat</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Merveilleux</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Magdalena</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Cremeschnitte</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Cheesecake</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Bebinca</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Fruitcake</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Chocolate Cake</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Fat Rascal</a>
                                        </li>
                                        <li class="mb-1">
                                            <a href="#">Financier</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
            </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/vendor/Chart.bundle.min.js')}}" ></script>
<script src="{{asset('js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('js/vendor/owl.carousel.min.js')}}" ></script>
<script>
function initCarousel(){
if ($(".owl-carousel.dashboard-numbers").length > 0) {
                $(".owl-carousel.dashboard-numbers")
                    .owlCarousel({
                    margin: 15,
                    loop: true,
                    autoplay: true,
                    stagePadding: 5,
                    responsive: {
                        0: {
                        items: 1
                        },
                        320: {
                        items: 2
                        },
                        576: {
                        items: 3
                        },
                        1200: {
                        items: 3
                        },
                        1440: {
                        items: 3
                        },
                        1800: {
                        items: 4
                        }
                    }
                    })
                    .data("owl.carousel")
                    .onResize();
            }
        }

        initCarousel();
</script>
@endsection

@section('customjs')
<script>
        $(window).on('load', function(){
            $.ajax({
                    method: 'GET',
                    url: '/api/init-dashboard-cards',
                    success: function(data){
                        $('.lds-dual-ring').css('display', 'none');
                        
                        $('#in-progress').css('display', 'block');
                        $('#completed').css('display', 'block');
                        $('#sales').css('display', 'block');
                        $('#comments').css('display', 'block');

                        $('.owl-carousel').trigger('destroy.owl.carousel');
                        callback(data.in_progress, data.completed, data.total_price, data.comments);
                        initCarousel();
                    },
                    error: function(err){
                        console.log(err);
                        $('.lds-dual-ring').css('display', 'none');
                        $('#in-progress').css('display', 'block');
                        $('#completed').css('display', 'block');
                        $('#sales').css('display', 'block');
                        $('#comments').css('display', 'block');

                        $('#in-progress').html('N/A');
                        $('#completed').html('N/A');
                        $('#sales').html('N/A');
                        $('#comments').html('N/A');
                    }
                });

                $.ajax({
                    method: 'GET',
                    url : '/api/orders/get-weekly-orders',
                    success: function(data, status, xhr){
                        var week_orders = data.orders;
                        var temp = [];

                        for(i = 1; i<= 7; i++){
                            let found = false;
                            for(k = 0; k < week_orders.length; k++){
                                if(week_orders[k].day == i){
                                    temp.push(week_orders[k].sales);
                                    found = true;
                                }
                            }

                            if(!found){
                                temp.push(0);
                            }
                        }

                        var rootStyle = getComputedStyle(document.body);
                        
                        var themeColor1 = rootStyle.getPropertyValue("--theme-color-1").trim();
                        var foregroundColor = rootStyle
                        .getPropertyValue("--foreground-color")
                        .trim();

                        var primaryColor = rootStyle.getPropertyValue("--primary-color").trim();
                        var separatorColor = rootStyle.getPropertyValue("--separator-color").trim();
                        
                        Chart.defaults.LineWithShadow = Chart.defaults.line;

                        Chart.controllers.LineWithShadow = Chart.controllers.line.extend({
                            draw: function(ease) {
                            Chart.controllers.line.prototype.draw.call(this, ease);
                            var ctx = this.chart.ctx;
                            ctx.save();
                            ctx.shadowColor = "rgba(0,0,0,0.15)";
                            ctx.shadowBlur = 10;
                            ctx.shadowOffsetX = 0;
                            ctx.shadowOffsetY = 10;
                            ctx.responsive = true;
                            ctx.stroke();
                            Chart.controllers.line.prototype.draw.apply(this, arguments);
                            ctx.restore();
                            }
                        });

                        var chartTooltip = {
                                backgroundColor: foregroundColor,
                                titleFontColor: primaryColor,
                                borderColor: separatorColor,
                                borderWidth: 0.5,
                                bodyFontColor: primaryColor,
                                bodySpacing: 10,
                                xPadding: 15,
                                yPadding: 15,
                                cornerRadius: 0.15,
                                displayColors: false
                            };


                        if (document.getElementById("salesChart")) {
                            var salesChart = document.getElementById("salesChart").getContext("2d");
                            var myChart = new Chart(salesChart, {
                            type: "LineWithShadow",
                            options: {
                                plugins: {
                                datalabels: {
                                    display: false
                                }
                                },
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                yAxes: [
                                    {
                                    gridLines: {
                                        display: true,
                                        lineWidth: 1,
                                        color: "rgba(0,0,0,0.1)",
                                        drawBorder: false
                                    },
                                    ticks: {
                                        beginAtZero: true,
                                        stepSize: 50,
                                        min: 0,
                                        padding: 20
                                    }
                                    }
                                ],
                                xAxes: [
                                    {
                                    gridLines: {
                                        display: false
                                    }
                                    }
                                ]
                                },
                                legend: {
                                display: false
                                },
                                tooltips: chartTooltip
                            },
                            data: {
                                labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                                datasets: [
                                {
                                    label: "",
                                    data: temp,
                                    borderColor: themeColor1,
                                    pointBackgroundColor: foregroundColor,
                                    pointBorderColor: themeColor1,
                                    pointHoverBackgroundColor: themeColor1,
                                    pointHoverBorderColor: foregroundColor,
                                    pointRadius: 6,
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 8,
                                    fill: false
                                }
                                ]
                            }
                            });
                        }
                    },
                    error: function(err, desc){
                        console.log(err)
                    }
                });

                function callback(pr, cm, tp, co){                    
                        $('#completed').html(cm);
                        $('#in-progress').html(pr);
                        $('#sales').html('GH&cent; '+tp);
                        $('#comments').html(co);

                }
        })
</script>
@endsection
