@extends('layouts.dashboard', ['page'=>'dispatch'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-float-label.min.css')}}" />
@endsection
@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Dispatch Riders</h1>
                        <div class="float-sm-right">
                            @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-backdrop="static"
                                data-target="#newUserModal">ADD NEW</button>
                            @endif
                        </div>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Dispatch Riders</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="separator mb-5"></div>
                    @if($dispatches->count() < 1)
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="jumbotron">
                                            <h2 class="display-4">No dispatch riders</h2>
                                            <p class="lead">Manage your restaurant's courier services</p>
                                            <hr class="my-4">
                                            <p>
                                                Add all your dispatch riders here. You can then assign a dispatch rider to your various orders. 
                                            </p>
                                            <p class="lead  mb-0">
                                                <a class="btn btn-primary btn-lg" href="#" data-toggle="modal" data-target="#newUserModal" role="button">Create New</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                    <div class="row list disable-text-selection" id="dispatch-list">
                        @foreach($dispatches as $dispatch)
                        <div class="col-lg-4 col-sm-12 mb-4">
                            <div class="card d-flex flex-row">
                                <a class="d-flex" href="#">
                                    <div class="rounded-circle m-4 align-self-center list-thumbnail-letters text-uppercase">
                                        {{ucfirst(substr($dispatch->firstname, 0, 1)).ucfirst(substr($dispatch->lastname, 0, 1))}}
                                    </div>
                                </a>
                                <div class=" d-flex flex-grow-1 min-width-zero">
                                    <div class="card-body pl-0 align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero">
                                        <div class="min-width-zero">
                                            <a href="#">
                                                <p class="list-item-heading mb-1 truncate">{{ucfirst($dispatch->firstname).' '.ucfirst($dispatch->lastname)}}</p>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-outline-primary " onclick="toggleEdit({{$dispatch}})" >Edit</button>
                                            <button type="button" class="btn btn-xs btn-danger " onclick="deactivate({{$dispatch->id}}, this)">{{$dispatch->active == 1 ? "Deactivate" : "Activate"}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach()
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal fade modal-right" id="newUserModal" role="dialog" aria-labelledby="newUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" id="new-dispatch-form" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="close mb-4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div>
                            <h3 class="mb-4">New <b class="text-primary">Dispatch</b></h3>
                            <div class="col-12">
                                <div class="form-row">
                                    <label class="form-group has-float-label col-lg-6 col-sm-12">
                                        <input class="form-control" name="firstname" required/>
                                        <span>First Name</span>
                                    </label>
                                    <label class="form-group has-float-label col-lg-6 col-sm-12">
                                        <input class="form-control" name="lastname" required/>
                                        <span>Last Name</span>
                                    </label>
                                </div>
                                <div class="form-row">
                                    <label class="form-group has-float-label col-lg-6 col-sm-12">
                                        <input class="form-control" name="phone" required/>
                                        <span>Phone</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><b>Cancel</b></button>
                        <button type="submit" class="btn btn-primary" id="submit-all"><b>Save</b></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="modal fade modal-right" id="editUserModal" role="dialog" aria-labelledby="editUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="put" id="edit-dispatch-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="close mb-4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div>
                            <h3 class="mb-4">Edit Dispatch Rider</h3>
                            <div class="col-12">
                                <div class="form-row">
                                    <label class="form-group has-float-label col-lg-6 col-sm-12">
                                        <input class="form-control" name="firstname" required/>
                                        <span>First Name</span>
                                    </label>
                                    <label class="form-group has-float-label col-lg-6 col-sm-12">
                                        <input class="form-control" name="lastname" required/>
                                        <span>Last Name</span>
                                    </label>
                                    <input type="hidden" name="id"/>
                                </div>
                                <div class="form-row">
                                    <label class="form-group has-float-label col-lg-6 col-sm-12">
                                        <input class="form-control" name="phone" required/>
                                        <span>Phone</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit-edit"><b>Save</b></button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <div id="deleteModal" class="modal fade">
            <div class="modal-dialog modal-confirm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure?</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Do you really want to deactivate this user account?</p>
                        <input type="hidden" id="delete-cat-id"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btn-delete">Delete</button>
                    </div>
                </div>
            </div>
        </div> 
@endsection
@section('scripts')
    <script src="{{asset('js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script>
        $('#new-dispatch-form').on('submit', function(e){
            e.preventDefault();
            data = $(this).serialize();
            btn = $(this).find('[type="submit"]');

                $.ajax({
                    url: '/api/dispatch/add',
                    method: 'POST',
                    data: data,
                    success: function(data){
                        $('.modal').modal('hide');
                        btn.prop('disabled', false);
                        btn.html('Save');
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

                            $("#new-dispatch-form").trigger('reset');
                            $('#dispatch-list').append('<div class="col-lg-4 col-sm-12"><div class="card d-flex flex-row mb-4"><a class="d-flex" href="#"><div class="rounded-circle m-4 align-self-center list-thumbnail-letters text-uppercase">'+data.data.firstname.charAt(0)+data.data.lastname.charAt(0)+'</div></a><div class=" d-flex flex-grow-1 min-width-zero"><div class="card-body pl-0 align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero"><div class="min-width-zero"><a href="#"><p class="list-item-heading mb-1 truncate">'+data.data.firstname+' '+data.data.lastname+'</p></a><button type="button" class="btn btn-xs btn-outline-primary ">Edit</button> <button type="button" class="btn btn-xs btn-danger ">Deactivate</button></div></div></div></div></div>');
                        }
                    }, 
                    error: function(err){
                        btn.prop('disabled', false);
                        btn.html('Save');
                        $('.modal').modal('hide');
                        $.notify({
                                // options
                                message: 'Network error'
                            },{
                                // settings
                                type: 'danger'
                        });
                    }
                });
        });

        function toggleEdit(dispatch){
            $('#edit-dispatch-form').find('[name="firstname"]').val(dispatch.firstname);
            $('#edit-dispatch-form').find('[name="lastname"]').val(dispatch.lastname);
            $('#edit-dispatch-form').find('[name="phone"]').val(dispatch.phone);
            $('#edit-dispatch-form').find('[name="id"]').val(dispatch.id);
            $('#editUserModal').modal('show');
        }

        $('#edit-dispatch-form').on('submit', function(e){
            e.preventDefault();
            data = $(this).serialize();
            btn = $(this).find('[type="submit"]');

            btn.html('<div class="lds-dual-ring-white"></div>');
                
                btn.prop('disabled', true);
                
                $.ajax({
                    url: '/api/dispatch/edit',
                    method: 'PUT',
                    data: data,
                    success: function(data){
                        $('.modal').modal('hide');
                        btn.prop('disabled', false);
                        btn.html('Save');
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

                            $("#new-dispatch-form").trigger('reset');
                            
                            setTimeout(function(){
                                location.reload();
                            }, 500)
                        }
                    }, 
                    error: function(err){
                        btn.prop('disabled', false);
                        btn.html('Save');
                        $('.modal').modal('hide');
                        $.notify({
                                // options
                                message: 'Network error'
                            },{
                                // settings
                                type: 'danger'
                        });
                    }
                });
        });

        function deactivate(dispatch, element)
        {
            el = $(element);

            el.prop('disabled', true);

            var active;
            if(el.html() == 'Deactivate'){
                active = 0;
            }else if(el.html() == 'Activate'){
                active = 1;
            }
            el.html('Please wait...');

            $.ajax({
                url: 'api/dispatch/activate',
                method: 'post',
                data: 'dispatch_id='+dispatch+'&active='+active,
                success: function(data){
                    el.prop('disabled', false);

                    if(active == 0){
                        el.html('Activate');
                        $.notify({
                            //options
                            message: 'Dispatch rider deactivated'
                        },{
                            //settings
                            type: 'success'
                        });
                    }else if(active == 1){
                        el.html('Deactivate');
                        $.notify({
                            //options
                            message: 'Dispatch rider activated'
                        },{
                            //settings
                            type: 'success'
                        });
                    }
                },
                error: function(err){
                    el.prop('disabled', false);

                    if(active == 0){
                        el.html("Deactivate");
                    }else if(active == 1){
                        el.html("Activate");
                    }

                    $.notify({
                        //options
                        message: 'Network error'
                    },{
                        type: 'danger'
                    });
                }
            });
        }
    </script>
@endsection