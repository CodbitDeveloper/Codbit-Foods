@extends('layouts.dashboard', ['page' => 'reports'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-datepicker3.min.css')}}" />
    <style>
        .dn{
            display: none;
        }
    </style>
@endsection
@section('content')
<div class="container-fluid disable-text-selection">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Reports</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="/home">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Reports</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="sales-tab" data-toggle="tab" href="#sales" role="tab"
                                        aria-controls="first" aria-selected="true">SALES</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="category-tab" data-toggle="tab" href="#category" role="tab"
                                        aria-controls="first" aria-selected="true">CATEGORY</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="branch-tab" data-toggle="tab" href="#branch" role="tab"
                                        aria-controls="first" aria-selected="true">BRANCH</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="customers-tab" data-toggle="tab" href="#customers" role="tab"
                                        aria-controls="first" aria-selected="true">CUSTOMERS</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                                    <div class="row form group  mb-4">
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary dropdown-toggle mb-1" type="button" id="dropdownMenuButton"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <b>Date range</b>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" onclick="changeView('weekly')">Weekly</a>
                                                <a class="dropdown-item" href="#" onclick="changeView('monthly')">Monthly</a>
                                                <a class="dropdown-item" href="#" onclick="changeView('yearly')">Yearly</a>
                                                <a class="dropdown-item" href="#" onclick="changeView('custom')">Custom</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="weekly-sales" class="dn">
                                        <p class="mb-4">Select a day within the week you want to query</p>
                                        <div class="form-group">
                                            <div class="dpkr date-inline">
                                            </div>
                                        </div>
                                        <p class="text-small text-danger dn">Make a selection</p>
                                        <button class="btn btn-dark float-right" id="go-weekly"><b>Go</b></button>
                                    </div>
                                    <div id="monthly-sales" class="dn">
                                        <p class="mb-4">Select month you want to query</p>
                                        <div class="form-group">
                                            <div class="dpkr date-inline-months">
                                            </div>
                                        </div>
                                        <p class="text-small text-danger dn">Select a month</p>
                                        <button class="btn btn-dark float-right" id="go-monthly"><b>Go</b></button>
                                    </div>
                                    <div id="yearly-sales" class="dn">
                                        <p class="mb-4">Select month you want to query</p>
                                        <div class="form-group">
                                            <div class="dpkr date-inline-years">
                                            </div>
                                        </div>
                                        <p class="text-small text-danger dn">Select a year</p>
                                        <button class="btn btn-dark float-right"><b>Go</b></button>
                                    </div>
                                    <div id="custom" class="dn">
                                        <p class="mb-4">Select month you want to query</p>
                                        <div class="form-group">
                                            <div class="dpkr date-inline-years">
                                            </div>
                                        </div>
                                        <p class="text-small text-danger dn">Make a selection</p>
                                        <button class="btn btn-dark float-right"><b>Go</b></button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="category" role="tabpanel" aria-labelledby="category-tab">
                                </div>
                                <div class="tab-pane fade" id="branch" role="tabpanel" aria-labelledby="branch-tab">
                                </div>
                                <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Report Card</h5>
                            <div class="dashboard-line-chart">
                                <canvas id="salesChart" class="mb-4"></canvas>
                                <div class="lds-dual-ring mr-auto ml-auto dn"></div>
                                <p id="chart-error" class="text-small text-danger text-center dn">There was an error 
                                    retrieving the report. Try again.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="{{asset('js/vendor/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('js/vendor/moment.min.js')}}"></script>
    <script src="{{asset('js/vendor/Chart.bundle.min.js')}}" ></script>
    <script>
        var selected_month = null;
        $(".date-inline").datepicker({
            autoclose: true,
            templates: {
            leftArrow: '<i class="simple-icon-arrow-left"></i>',
            rightArrow: '<i class="simple-icon-arrow-right"></i>'
            }
        });

        $(".date-inline-months").datepicker({
            autoclose: true,
            templates: {
            leftArrow: '<i class="simple-icon-arrow-left"></i>',
            rightArrow: '<i class="simple-icon-arrow-right"></i>'
            },
            format: 'mm/yyyy',
            viewMode: 1, 
            minViewMode: "months"
        }).on('changeMonth', function(e){
            selected_month = e.date;
        });

        $(".date-inline-years").datepicker({
            autoclose: true,
            templates: {
            leftArrow: '<i class="simple-icon-arrow-left"></i>',
            rightArrow: '<i class="simple-icon-arrow-right"></i>'
            },
            viewMode: "years", 
            minViewMode: "years"
        });

        function changeView(range){
            selected_month = null;

            $('.dn').css('display', 'none');

            $('.dpkr').each(function(){
                $(this).datepicker('update', '');
            });
            
            if(range == 'weekly'){
                el = $('#weekly-sales');
            }else if(range == 'monthly'){
                el = $('#monthly-sales');
            }else if(range == 'yearly'){
                el = $('#yearly-sales');
            }else if(range == 'custom'){
                el = $('#custom');
            }else{
                return false;
            }

            el.css('display', 'block');
        }

        $('#go-weekly').on('click', function(){
            $('canvas').html('');
            var date = $('.date-inline').datepicker('getUTCDate');

            console.log(date);
            $('.lds-dual-ring').css('display', 'block');
            $('#chart-error').css('display', 'none');

            $.ajax({
                url : '/api/report/order/weekly',
                data : 'date='+date,
                method : 'post',
                success : function(data){
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

                        $('.lds-dual-ring').css('display', 'none');
                },
                error: function(data){                   
                    $('.lds-dual-ring').css('display', 'none');
                    $('#chart-error').css('display', 'block');
                }
            })
        });

        $('#go-monthly').on('click', function(){
            $('#salesChart').css('display', 'none');
            $('#monthly-sales .text-danger').css('display', 'none');
            $('.lds-dual-ring').css('display', 'block');
            
            $('#chart-error').css('display', 'none');
            
            var date = selected_month;
            console.log(date);

            if(date == '' || date == null){
                $('#monthly-sales .text-danger').css('display', 'block');
                return false;
            }else{

                $.ajax({
                    url: '/api/report/order/monthly',
                    data: 'date='+date,
                    method: 'POST',
                    success: function(data){
                        var week_orders = data.orders;
                        var temp = [];

                        for(i = 1; i<= 31; i++){
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
                                    labels: ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th", "11th", "12th", "13th", "14th", "15th", "16th", "17th", "18th", "19th", "20th", "21st", "22nd", "23rd", "24th", "25th", "26th", "27th", "28th", "29th", "30th", "31st"],
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
                                
                                $('#salesChart').css('display', 'block');
                            }

                        $('.lds-dual-ring').css('display', 'none');
                        
                    },
                    error: {

                    }
                });
            }

        });
    </script>
@endsection