@extends('layouts.dashboard', ['page' => 'branches'])
@section('styles')
<link rel="stylesheet" href="{{asset('css/vendor/dataTables.bootstrap4.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/vendor/datatables.responsive.bootstrap4.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/vendor/bootstrap-float-label.min.css')}}" />
@endsection
@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    
                    <div class="mb-2">
                        <h1>Branches</h1>
                        <div class="float-sm-right">
                            @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-backdrop="static"
                                data-target="#newBranchModal">ADD NEW</button>
                            @endif
                        </div>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="/home">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Branches</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="separator mb-5"></div>
                    
                    <div class="row list disable-text-selection">
                        <div class="col-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    <table class="data-table responsive nowrap mb-3"  data-order="[[ 0, &quot;asc&quot; ]]">
                                        <thead>
                                            <th>Branch Name</th>
                                            <th>Branch Location</th>
                                            <th>Contact Number</th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody>
                                        @foreach($branches as $branch)
                                            <tr>
                                                <td>
                                                    {{$branch->name}}
                                                </td>
                                                <td>
                                                    {{$branch->location}}
                                                </td>
                                                <td>
                                                    {{$branch->phone_number}}
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-primary" style="cursor: pointer;" onclick="deactivate({{$branch->id}}, this)">{{$branch->active == 1 ? 'Deactivate' : 'Activate'}}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade modal-right" id="newBranchModal" role="dialog" aria-labelledby="newBranchModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" id="new-branch-form" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="close mb-4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div>
                            <h3 class="mb-4">New <b class="text-primary">Branch</b></h3>
                            <div class="col-12">
                                <div class="form-row">
                                    <label class="form-group has-float-label col-12">
                                        <input class="form-control" name="name" required/>
                                        <span>Name</span>
                                    </label>
                                </div>
                                <div class="form-row">
                                    <label class="form-group has-float-label col-12">
                                        <input class="form-control" name="location" required/>
                                        <span>Location</span>
                                    </label>
                                </div>
                                <div class="form-row">
                                    <label class="form-group has-float-label col-12">
                                        <input class="form-control" name="phone_number" type="tel" required/>
                                        <span>Phone</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><b>Cancel</b></button>
                        <button type="submit" class="btn btn-primary"><b>Save</b></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
@endsection
@section('scripts')
<script src="{{asset('js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
<script>
    $('#new-branch-form').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serialize();

        var btn = $(this).find('[type="submit"]');

        btn.html('<div class="lds-dual-ring-white"></div>');

        $.ajax({
            url: '/api/branches/add',
            method: 'post',
            data: data,
            success: function(data){
                $('.modal').modal('hide');
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

                    $('.data-table').DataTable().row.add([
                        data.data.name,
                        data.data.location,
                        data.data.phone_number,
                        '<span class="badge badge-pill badge-primary" onclick="toggleActivate('+data.data.id+')">Deactivate</span>'
                    ]).draw(false);
                }
            },
            error: function(err){
                btn.html('Save');
                $.notify({
                    // options
                    message: 'Network error. Try again!'
                },{
                   // settings
                    type: 'danger'
                });
            }
        })
    });

    function deactivate(branch, element){
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
            url: '/api/branch/activate',
            method: 'post',
            data : 'branch_id='+branch+'&active='+active,
            success: function(data){
                el.prop('disabled', false);
                
                if(active == 0){
                    el.html('Activate');
                    $.notify({
                        // options
                        message: 'Branch deactivated'
                    },{
                       // settings
                        type: 'success'
                    });
                }else if(active == 1){
                    el.html('Deactivate');
                    $.notify({
                        // options
                        message: 'Branch activated'
                    },{
                       // settings
                        type: 'success'
                    });
                }         
            }, 
            error: function(err){
                el.prop('disabled', false);
                
                if(active == 0){
                    el.html('Deactivate');
                }else if(active == 1){
                    el.html('Activate');
                } 

                $.notify({
                        // options
                        message: 'Network error'
                    },{
                       // settings
                        type: 'danger'
                    });  
            }
        })
    }
</script>
@endsection