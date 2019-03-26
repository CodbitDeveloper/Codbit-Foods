@extends('layouts.dashboard', ['page'=>'orders'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-float-label.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/dataTables.bootstrap4.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/datatables.responsive.bootstrap4.min.css')}}" />
    <style>
        .dn{
            display: none;
        }


/** SPINNER CREATION **/

.loader {
  position: relative;
  text-align: center;
  margin: 15px auto 35px auto;
  z-index: 9999;
  display: block;
  width: 80px;
  height: 80px;
  border: 10px solid rgba(0, 0, 0, .3);
  border-radius: 50%;
  border-top-color: #000;
  animation: spin 1s ease-in-out infinite;
  -webkit-animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}

@-webkit-keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}


/** MODAL STYLING **/

#loading-modal.modal-content {
  border-radius: 0px;
  box-shadow: 0 0 20px 8px rgba(0, 0, 0, 0.7);
}

#loading-modal.modal-backdrop.show {
  opacity: 0.75;
}

#loading-modal.loader-txt {
    p {
    font-size: 13px;
    color: #666;
    small {
      font-size: 11.5px;
      color: #999;
    }
  }
}
    </style>
@endsection
@section('content')
    <div class="container-fluid disable-text-selection">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Orders</h1>
                        <div class="float-sm-right">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-backdrop="static"
                                data-target="#newOrderModal">ADD NEW</button>
                        </div>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Orders</li>
                            </ol>
                        </nav>

                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab"
                        aria-controls="first" aria-selected="true">PENDING</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " id="inprogress-tab" data-toggle="tab" href="#inprogress" role="tab"
                        aria-controls="second" aria-selected="false">IN PROGRESS</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " id="completed-tab" data-toggle="tab" href="#completed" role="tab"
                        aria-controls="third" aria-selected="false">COMPLETED</a>
                </li>

                <li class="nav-item">
                <a class="nav-link " id="rejected-tab" data-toggle="tab" href="#rejected" role="tab"
                        aria-controls="fourth" aria-selected="false">REJECTED</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="row">
                        <div class="col-12 list" data-check-all="checkAll">
                            @if(isset($orders["Pending"]))
                                @foreach($orders["Pending"] as $pending)
                                <div class="card d-flex flex-row mb-3">
                                    <a class="d-flex" href="/order/{{$pending->id}}">
                                        <img src="/img/foods/{{$pending->items[0]->image}}" alt="{{$pending->items[0]->name}}" class="list-thumbnail responsive border-0" />
                                    </a>
                                    <div class="pl-2 d-flex flex-grow-1 min-width-zero">
                                        <div class="card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center">
                                            <a href="/order/{{$pending->id}}" class="w-40 w-sm-100">
                                                <p class="list-item-heading mb-1 truncate">{{$pending->items[0]->name}} @if($pending->items->count() > 1) <span class="text-muted mb-1 text-small">and
                                                        {{$pending->items->count() - 1}} other @if($pending->items->count() > 2)items @else item @endif</span>@endif</p>
                                            </a>
                                            <p class="mb-1 text-small w-20 w-sm-100"><a href="#">{{$pending->customer->firstname.' '.$pending->customer->lastname}}</a></p>
                                            <p class="mb-1 mr-1 text-muted text-small w-20 w-sm-100 truncate">{{$pending->address}}</p>
                                            <p class="mb-1 text-muted text-small text-right w-15 w-sm-100">{{Carbon\Carbon::parse($pending->created_at)->diffForHumans()}}</p>
                                            <div class="w-15 w-sm-100 text-right">
                                                <span class="badge badge-pill badge-primary">PENDING</span>
                                            </div>
                                            
                                            <div class="w-15 w-sm-100 text-right">
                                                <div class="btn-group float-right mr-1 mb-1">
                                                    <button class="btn btn-light btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" onclick="updateItem('In-Progress', {{$pending->id}})">Mark as <b>In Progress</b></a>
                                                        <a class="dropdown-item" href="#" onclick="updateItem('Rejected', {{$pending->id}})">Mark as <b>Rejected</b></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" pl-1 align-self-center pr-4">

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <span class="text-small text-muted">No pending orders at this time.</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="inprogress" role="tabpanel" aria-labelledby="inprogress-tab">
                    <div class="row">
                        <div class="col-12 list">

                        @if(isset($orders["In-Progress"]))
                                @foreach($orders["In-Progress"] as $progress)
                                <div class="card d-flex flex-row mb-3">
                                    <a class="d-flex" href="/orders/{{$progress->id}}">
                                        <img src="/img/foods/{{$progress->items[0]->image}}" alt="{{$progress->items[0]->name}}" class="list-thumbnail responsive border-0" />
                                    </a>
                                    <div class="pl-2 d-flex flex-grow-1 min-width-zero">
                                        <div class="card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center">
                                            <a href="/order/{{$progress->id}}" class="w-40 w-sm-100">
                                                <p class="list-item-heading mb-1 truncate">{{$progress->items[0]->name}} @if($progress->items->count() > 1) <span class="text-muted mb-1 text-small">and
                                                        {{$progress->items->count() - 1}} other @if($progress->items->count() > 2)items @else item @endif</span>@endif</p>
                                            </a>
                                            <p class="mb-1 text-small w-20 w-sm-100"><a href="#">{{$progress->customer->firstname.' '.$progress->customer->lastname}}</a></p>
                                            <p class="mb-1 mr-1 text-muted text-small w-20 w-sm-100 truncate">{{$progress->address}}</p>
                                            <p class="mb-1 text-muted text-small text-right w-15 w-sm-100">{{Carbon\Carbon::parse($progress->created_at)->diffForHumans()}}</p>
                                            <div class="w-15 w-sm-100 text-right">
                                                <span class="badge badge-pill badge-warning">IN PROGRESS</span>
                                            </div>
                                            
                                            <div class="w-15 w-sm-100 text-right">
                                                <div class="btn-group float-right mr-1 mb-1">
                                                    <button class="btn btn-light btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" onclick="updateItem('Completed', {{$progress->id}})">Mark as <b>Completed</b></a>
                                                        <a class="dropdown-item" href="#" onclick="updateItem('Rejected', {{$progress->id}})">Mark as <b>Rejected</b></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" pl-1 align-self-center pr-4">

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <span class="text-small text-muted">No orders in progress at this time.</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="row">
                        <div class="col-12 list">
                        @if(isset($orders["Completed"]))
                                @foreach($orders["Completed"] as $completed)
                                <div class="card d-flex flex-row mb-3">
                                    <a class="d-flex" href="/orders/{{$completed->id}}">
                                        <img src="/img/foods/{{$completed->items[0]->image}}" alt="{{$completed->items[0]->name}}" class="list-thumbnail responsive border-0" />
                                    </a>
                                    <div class="pl-2 d-flex flex-grow-1 min-width-zero">
                                        <div class="card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center">
                                            <a href="/order/{{$completed->id}}" class="w-40 w-sm-100">
                                                <p class="list-item-heading mb-1 truncate">{{$completed->items[0]->name}} @if($completed->items->count() > 1) <span class="text-muted mb-1 text-small">and
                                                        {{$completed->items->count() - 1}} other @if($completed->items->count() > 2)items @else item @endif</span>@endif</p>
                                            </a>
                                            <p class="mb-1 text-small w-20 w-sm-100"><a href="#">{{$completed->customer->firstname.' '.$completed->customer->lastname}}</a></p>
                                            <p class="mb-1 mr-1 text-muted text-small w-20 w-sm-100 truncate">{{$completed->address}}</p>
                                            <p class="mb-1 text-muted text-small text-right w-15 w-sm-100">{{Carbon\Carbon::parse($completed->created_at)->diffForHumans()}}</p>
                                            <div class="w-15 w-sm-100 text-right">
                                                <span class="badge badge-pill badge-success">COMPLETED</span>
                                            </div>
                                            
                                            <div class="w-15 w-sm-100 text-right">
                                                <div class="btn-group float-right mr-1 mb-1">
                                                    <button class="btn btn-light btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" onclick="updateItem('In-Progress', {{$completed->id}})">Mark as <b>In Progress</b></a>
                                                        <a class="dropdown-item" href="#" onclick="updateItem('Rejected', {{$completed->id}})">Mark as <b>Rejected</b></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" pl-1 align-self-center pr-4">

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <span class="text-small text-muted">No completed orders at this time.</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <div class="row">
                        <div class="col-12 list">
                            @if(isset($orders["Rejected"]))
                                @foreach($orders["Rejected"] as $completed)
                                <div class="card d-flex flex-row mb-3">
                                    <a class="d-flex" href="/orders/{{$completed->id}}">
                                        <img src="/img/foods/{{$completed->items[0]->image}}" alt="{{$completed->items[0]->name}}" class="list-thumbnail responsive border-0" />
                                    </a>
                                    <div class="pl-2 d-flex flex-grow-1 min-width-zero">
                                        <div class="card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center">
                                            <a href="/order/{{$completed->id}}" class="w-40 w-sm-100">
                                                <p class="list-item-heading mb-1 truncate">{{$completed->items[0]->name}} @if($completed->items->count() > 1) <span class="text-muted mb-1 text-small">and
                                                        {{$completed->items->count() - 1}} other @if($completed->items->count() > 2)items @else item @endif</span>@endif</p>
                                            </a>
                                            <p class="mb-1 text-small w-20 w-sm-100"><a href="#">{{$completed->customer->firstname.' '.$completed->customer->lastname}}</a></p>
                                            <p class="mb-1 mr-1 text-muted text-small w-20 w-sm-100 truncate">{{$completed->address}}</p>
                                            <p class="mb-1 text-muted text-small text-right w-15 w-sm-100">{{Carbon\Carbon::parse($completed->created_at)->diffForHumans()}}</p>
                                            <div class="w-15 w-sm-100 text-right">
                                                <span class="badge badge-pill badge-dark">REJECTED</span>
                                            </div>
                                            
                                            <div class="w-15 w-sm-100 text-right">
                                                <div class="btn-group float-right mr-1 mb-1">
                                                    <button class="btn btn-light btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" onclick="updateItem('In-Progress', {{$completed->id}})">Mark as <b>In Progress</b></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" pl-1 align-self-center pr-4">

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <span class="text-small text-muted">No rejected orders at this time.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-right" id="newOrderModal" role="dialog" aria-labelledby="newOrderModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="close mb-4">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div>
                                <h3>New Order</h3>
                                <div class="text-right">
                                    <h6><b>Total</b><br />
                                        <span class="text-small text-muted mr-3" id="new-order-total">0 GHS</span>
                                    </h6>
                                </div>
                                <form method="post" action="#">
                                    <div class="customer-details">
                                        <h6>Customer Details</h6>
                                        <div class="tab-content mb-3">
                                            <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="newcustomer-tab" data-toggle="tab"
                                                        href="#newcustomer" role="tab" aria-controls="first"
                                                        aria-selected="true">New</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link " id="existingcustomer-tab" data-toggle="tab"
                                                        href="#existingcustomer" role="tab" aria-controls="second"
                                                        aria-selected="false">Existing</a>
                                                </li>
                                            </ul>
                                            <div class="tab-pane fade show active" id="newcustomer" role="tabpanel"
                                                aria-labelledby="newcustomer-tab">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-row">
                                                            <label class="form-group has-float-label col-md-6">
                                                                <input class="form-control" id="fname"/>
                                                                <span class="text-small text-danger dn" id="fname-error">First name is required</span>
                                                                <span>First Name</span>
                                                            </label>
                                                            <label class="form-group has-float-label col-md-6">
                                                                <input class="form-control" id="lname"/>
                                                                <span class="text-small text-danger dn" id="lname-error">Last name is required</span>
                                                                <span>Last Name</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-row">
                                                            <label class="form-group has-float-label col-md-6">
                                                                <input class="form-control" type="email" id="email"/>
                                                                <span class="text-small text-danger dn" id="email-error">Email is required</span>
                                                                <span>Email</span>
                                                            </label>
                                                            <label class="form-group has-float-label col-md-6">
                                                                <input class="form-control" type="tel" id="phone"/>
                                                                <span class="text-small text-danger dn" id="phone-error">Phone number is required</span>
                                                                <span>Phone Number</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade mb-4" id="existingcustomer" role="tabpanel"
                                                aria-labelledby="existingcustomer-tab">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <select class="form-control select2-single" id="customer_id">
                                                            <option selected disabled hidden>Search for customer...</option>
                                                            @foreach($customers as $customer)
                                                            <option value="{{$customer->id}}">{{$customer->firstname.' '.$customer->lastname}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-small text-danger dn" id="customer-error">Select a customer</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h6>Order Details</h6>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <select class="form-control select2-single mb-1" id="cart-item">
                                                        <option selected disabled hidden>Search for menu item...</option>
                                                        @foreach($categories as $category)
                                                        <optgroup label="{{$category->name}}">
                                                            @foreach($category->items as $item)
                                                            <option value="{{$item->id}}" data-price="{{$item->price}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-small text-danger dn" id="cart-error">Select an item</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row mb-4">
                                            <label class="form-group has-float-label col-6">
                                                <input class="form-control" type="number" min="1" value="1" id="cart-quantity" />
                                                <span>Quantity</span>
                                            </label>
                                            <div class="col-6">
                                                <button type="button" class="btn btn-primary btn-block" id="add-to-cart"><b>ADD</b></button>
                                            </div>
                                        </div>
                                        <table class="data-table responsive nowrap mb-3" id="cart-table" data-order="[[ 1, &quot;desc&quot; ]]">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <div class="col-12">
                                            <div class="form-row">
                                                <label class="form-group has-float-label col-12">
                                                    <textarea class="form-control" rows="3"></textarea>
                                                    <span>Extra Notes</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="confirm"><b>Confirm Order</b></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="loading-modal" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="loader"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="{{asset('js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('js/init-scrollbar.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script>
        let cart = [];
        let total = 0;

        $(document).ready(function () {


            $('#add-to-cart').on('click', function () {
                if ($('#cart-item').val() == null) {
                    $('#cart-error').css('display', 'block')
                } else {
                    var isInCart = false;

                    $('#cart-error').css('display', 'none');
                    
                    let temp = {
                        'item_id': $('#cart-item').val(),
                        'quantity': $('#cart-quantity').val()
                    };

                    for(i = 0; i < cart.length; i++){
                        if(cart[i].item_id == temp.item_id){
                            isInCart = true;
                            cart[i].quantity = Number(cart[i].quantity) + Number(temp.quantity);
                        
                            var table = $('#cart-table').DataTable();
                            var row = table.row($('#item-'+temp.item_id).parents('tr'))
                            var rowData = row.data();
                            rowData[2] = "GHS " + (cart[i].quantity * $('#cart-item').find('option:selected').data('price'));
                            rowData[1] = cart[i].quantity;

                            row.data(rowData);
                            
                            total = 0
                            for(i = 0; i < cart.length; i++){
                                total+= cart[i].quantity * $('#cart-item').find('[value="'+cart[i].item_id+'"]').data('price');
                            }
                            $('#new-order-total').html("GHS "+total);
                        }
                    }

                    if(!isInCart){
                        total = 0;
                        cart.push(temp);
                        var table = $('#cart-table').DataTable();
                        
                        for(i = 0; i < cart.length; i++){
                            total+= cart[i].quantity  * $('#cart-item').find('[value="'+cart[i].item_id+'"]').data('price');
                        }
                        $('#new-order-total').html("GHS "+total);

                        table
                            .row.add([$('#cart-item').find('option:selected').text(), $('#cart-quantity').val(), "GHS " + ($('#cart-quantity').val() * $('#cart-item').find('option:selected').data('price')), '<a href="#" onclick="removeFromCart(this)" class="text-small text-danger" data-item = "'+temp.item_id+'" id="item-'+temp.item_id+'">Remove</a>']).draw(); 
                    }
                           
                    


                    $('#cart-quantity').val(1);
                    $("#cart-item").select2("val", "");
                    $('#items').val(cart);
                }
            });


            $('#confirm').on('click', function(e){
                btn = $(this);
                e.preventDefault();
                var selected_tab = $('.modal').find('.tab-content .active').attr('id');
                if(selected_tab == 'newcustomer-tab'){
                    if($('#fname').val() == ''){
                        $('#fname-error').css('display', 'block');
                        return false;
                    }else{
                        $('#fname-error').css('display', 'none');
                    }

                    if($('#lname').val() == ''){
                        $('#lname-error').css('display', 'block');
                        return false;
                    }else{
                        $('#lname-error').css('display', 'none');
                    }

                    if($('#email').val() == ''){
                        $('#email-error').css('display', 'block');
                        return false;
                    }else{
                        $('#email-error').css('display', 'none');
                    }

                    if($('#phone').val() == ''){
                        $('#phone-error').css('display', 'block');
                        return false;
                    }else{
                        $('#phone-error').css('display', 'none');
                    }
                    
                    btn.html('<div class="lds-dual-ring-white"></div>');



                    var data = {
                        'firstname' : $('#fname').val(),
                        'lastname' : $('#lname').val(),
                        'email' : $('#email').val(),
                        'phone' : $('#phone').val(),
                        'items' : JSON.stringify(cart),
                        'total' : total,
                        'branch_id' : {{Auth::user()->branch_id}}
                    };

                }else{
                    if($('#customer_id').val() == null){
                        $('#customer-error').css('display', 'block');
                        return false;
                    }else{
                        btn.html('<div class="lds-dual-ring-white"></div>');
                        $('#customer-error').css('display', 'none');

                        var data = {
                            'customer_id' : $('#customer_id').val(),
                            'items' : JSON.stringify(cart),
                            'total' : total,
                            'branch_id' : {{Auth::user()->branch_id}}
                        }
                        
                    }
                }

                
                $.ajax({
                        url : '/api/orders/add',
                        method: 'POST',
                        data: data,
                        success: function(data){
                            $(".modal").modal('hide');
                            if(data.error){
                                btn.html('Confirm Order');
                                $.notify({
                                    // options
                                    message: data.message
                                },{
                                    // settings
                                    type: 'danger'
                                });
                            }else{
                                btn.html('Confirm Order');
                                $.notify({
                                    // options
                                    message: data.message
                                },{
                                    // settings
                                    type: 'success'
                                });

                                setTimeout(function(){
                                    location.replace('/order/'+data.data.id);
                                }, 500);
                            }
                        },
                        error: function(err){
                            $(".modal").modal('hide');
                            btn.html('Confirm Order');
                            $.notify({
                                // options
                                message: 'There was a network error'
                            },{
                                // settings
                                type: 'danger'
                            });
                        }
                    })
            });

            
        });

        
        function removeFromCart(el){
                element  = $(el)
                var item = element.data('item');

                for(i = 0; i< cart.length; i++){
                    if(cart[i].item_id == item){
                        cart.splice(i, 1);
                        var table = $('#cart-table').DataTable();
                        table.
                            row(element.parents('tr'))
                            .remove()
                            .draw();
                        break;
                    }
                }

                total = 0
                for(i = 0; i < cart.length; i++){
                    total+= cart[i].quantity  * $('#cart-item').find('[value="'+cart[i].item_id+'"]').data('price');
                }
                $('#new-order-total').html("GHS "+total);
            }

            function updateItem(status, order){
                $("#loading-modal").modal('show');

                let data = {
                    'order_id' : order,
                    'status' : status
                };

                $.ajax({
                    url: '/api/order/update-status',
                    method: 'put',
                    data: data,
                    success: function(data){
                            $("#loading-modal").modal('hide');
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
                            $(".modal").modal('hide');
                            btn.html('Confirm Order');
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
    </script>
@endsection