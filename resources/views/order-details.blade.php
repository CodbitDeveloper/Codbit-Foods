@extends('layouts.dashboard', ['page'=>'orders'])
@section('styles')
<link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/vendor/bootstrap-float-label.min.css')}}" />
@endsection
@section('content')
<div class="container-fluid disable-text-selection">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Order Details</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="/home">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="/orders">Orders</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
                            </ol>
                        </nav>

                        <div class="float-sm-right">
                            <button type="button" class="btn btn-lg btn-outline-primary dropdown-toggle dropdown-toggle-split top-right-button top-right-button-single"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ACTIONS
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @if(strtolower($order->status) != 'pending')
                                    <a class="dropdown-item" href="#" onclick="updateItem('Pending', {{$order->id}}, this)">Mark as <b>pending</b></a>
                                @endif
                                @if(strtolower($order->status) != 'in-progress')
                                    <a class="dropdown-item" href="#" onclick="updateItem('In-Progress', {{$order->id}}, this)">Mark as in <b>progress</b></a>
                                @endif
                                @if(strtolower($order->status) != 'rejected')
                                    <a class="dropdown-item" href="#" onclick="updateItem('Rejected', {{$order->id}}, this)">Mark as <b>rejected</b></a>
                                @endif
                                @if(strtolower($order->status) != 'completed')
                                    <a class="dropdown-item" href="#" onclick="updateItem('Completed', {{$order->id}}, this)">Mark as <b>completed</b></a>
                                @endif
                                @if(strtolower($order->status) == 'completed')
                                    <a class="dropdown-item" href="#" onclick="markAsPicked({{$order->id}}, this)">Mark as <b>picked up</b></a>
                                @endif
                                @if($order->to_be_delivered == 1 && strtolower($order->status) == 'completed')
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="deliverOrder({{$order->id}})">Assign to <b>Dispatch</b></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"
                        aria-controls="first" aria-selected="true">DETAILS</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="details-tab">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-4">
                            <div class="card mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Order Status</h6>
                                    <span class="badge badge-pill badge-warning position-relative text-uppercase">{{$order->status}}</span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="list-item-heading mb-4">Order Details</p>
                                    <div class="text-center">
                                        <img class="round mb-1" height="56px" style="width: 56px" avatar="{{$order->customer->firstname.' '.$order->customer->lastname}}" />
                                        <h6>{{$order->customer->firstname.' '.$order->customer->lastname}}</h6>
                                    </div>
                                    <p class="text-muted text-small mb-2">Address</p>
                                    <p class="mb-3">
                                        {{$order->address}}
                                    </p>

                                    <p class="text-muted text-small mb-2">Date</p>
                                    <p class="mb-3">
                                        {{Carbon\Carbon::parse($order->created_at)->diffForHumans()}}
                                    </p>
                                    <p class="text-muted text-small mb-2">Total Price</p>
                                    <p class="mb-3">
                                        GHS {{$order->total_price}}
                                    </p>
                                    <p class="text-muted text-small mb-2">Delivery</p>
                                    <p class="mb-3">
                                        {{$order->to_be_delivered == 1 ? 'Yes' : 'No'}}
                                    </p>
                                    <p class="text-muted text-small mb-2">Extra Notes</p>
                                    <p>
                                        {{$order->extra_note == '' || $order->extra_note == null ? 'N/A' : $order->extra_note}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-12">
                            <div class="col-sm-12 mb-4 text-right">
                                <a href="/invoice/{{$order->id}}">View Receipt</a>
                            </div>
                            @foreach($order->items as $item)
                            <div class="card d-flex flex-row mb-3">
                                <a class="d-flex" href="#">
                                    <img src="/img/foods/{{$item->image}}" alt="{{$item->name}}" class="list-thumbnail responsive border-0" />
                                </a>
                                <div class="pl-2 d-flex flex-grow-1 min-width-zero">
                                    <div class="card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center">
                                        <a href="Layouts.Details.html" class="w-40 w-sm-100">
                                            <p class="list-item-heading mb-1 truncate">{{$item->name}}</p>
                                        </a>
                                        <p class="mb-1 text-muted text-small w-15 w-sm-100">{{$item->pivot->quantity}} pcs</p>
                                        <p class="mb-1 text-muted text-small w-15 w-sm-100">GHS {{$item->price * $item->pivot->quantity}}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="clearfix mb-4"></div>
                            <div class="text-right">
                                <h3><b>Total</b></h3>
                                <span class="text-muted text-small">GHS {{$order->total_price}}</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div id="assignModal" class="modal fade">
            <div class="modal-dialog modal-confirm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Assign a dispatch rider to an order</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <form id="assign_form">
                    <div class="modal-body form-row">
                        <label class="form-group has-float-label col-lg-12 col-sm-12">
                            <select class="form-control select2-single" name="dispatch_id" required>
                                @foreach($dispatches as $dispatch)
                                    <option value="{{$dispatch->id}}">{{$dispatch->firstname}} {{$dispatch->lastname}}</option>
                                @endforeach
                            </select>
                            <span>Dispatch</span>
                        </label>
                        <input type="hidden" name="order_id" id="order_id"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-assign">Assign</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('js/vendor/select2.full.js')}}"></script>
    <script>
        function updateItem(status, order, element){
            el = $(element);
            badge = el.closest('.card-body').find('.badge');
            init = badge.html();
            badge.html('LOADING...');

            let data = {
                'order_id' : order,
                'status' : status
            };

            $.ajax({
                url: '/api/order/update-status',
                method: 'put',
                data: data,
                success: function(data){
                    badge.html(init);
                    if(data.error){
                        $.notify({
                            // options
                            message: data.message
                        },{
                            // settings
                            type: 'danger'
                        });
                    }else{
                        $.notify({
                            // options
                            message: data.message
                        },{
                            // settings
                            type: 'success'
                        });

                        setTimeout(function(){
                            location.reload();
                        }, 500);
                    }
                },
                error: function(err){
                    badge.html(init);
                    $.notify({
                        // options
                        message: 'There was a network error'
                    },{
                        // settings
                        type: 'danger'
                    });
                }
            })
        }

        function markAsPicked(order, element){
                el = $(element);
                badge = el.closest('.card-body').find('.badge');
                init = badge.html();
                badge.html('LOADING...');

                let data = {
                    'order_id' : order,
                };

                $.ajax({
                    url: '/api/order/pickup',
                    method: 'post',
                    data: data,
                    success: function(data){
                        badge.html(init);
                        if(data.error){
                            $.notify({
                                // options
                                message: data.message
                            },{
                                // settings
                                type: 'danger'
                            });
                        }else{
                            $.notify({
                                // options
                                message: data.message
                            },{
                                // settings
                                type: 'success'
                            });

                            setTimeout(function(){
                                location.reload();
                            }, 500);
                        }
                    },
                    error: function(err){
                        badge.html(init);
                        $.notify({
                            // options
                            message: 'There was a network error'
                        },{
                            // settings
                            type: 'danger'
                        });
                    }
                });
        }

        function deliverOrder(deliver)
        {
            $('#order_id').val(deliver);

            $('#assignModal').modal('show');
        }
        
        $('#assign_form').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');
            btn.html('<div class="lds-dual-ring-white"></div>');
            data = $(this).serialize();

            $.ajax({
                url: '/api/delivery/add',
                method: 'post',
                data: data,
                success: function(data){
                    $('.modal').modal('hide');
                    btn.html('Assign');

                    if(data.error){
                        $.notify({
                            //options
                            message: data.message
                        },{
                            //setting
                            type: 'danger'
                        });
                    }else{
                        $.notify({
                            //options
                            message: data.message
                        },{
                            //settings
                            type: 'success'
                        });

                        setTimeout(function(){
                            location.reload();
                        }, 500);
                    }
                },
                error: function(err){
                    btn.html('Assign');
                    $(".modal").modal('hide');

                    $.notify({
                        //options
                        message: 'Network error'
                    },{
                        //settings
                        type: 'danger'
                    });
                }
            });

        });

    </script>
@endsection