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
                            @if(strtolower(Auth::user()->role) != 'attendant')
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-backdrop="static"
                                data-target="#newBranchModal">ADD NEW</button>
                            @endif
                        </div>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Branches</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="separator mb-5"></div>
                    <div class="row list disable-text-selection">
                        <div class="col-12">
                            <div class="card h-100 scroll" style="max-height:380px">
                                <div class="card-body">
                                    <table class="data-table responsive nowrap mb-3"  data-order="[[ 0, &quot;desc&quot; ]]">
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
                                                    <span class="badge badge-pill badge-primary">Deactivate</span>
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
                            <h3 class="mb-4">New Branch</h3>
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
                                        <input class="form-control" name="location" type="tel" required/>
                                        <span>Phone</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><b>Save</b></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
@endsection
@section('scripts')
<script src="{{asset('js/vendor/datatables.min.js')}}"></script>
<script>
    $('')
</script>
@endsection