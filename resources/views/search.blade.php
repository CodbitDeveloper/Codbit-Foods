@extends('layouts.dashboard', ['page' => ''])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/dataTables.bootstrap4.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/datatables.responsive.bootstrap4.min.css')}}" />
    <style>
        table.dataTable td{
            padding-top: 0!important;
            padding-bottom: 0!important;
            border-bottom: none!important;
            outline: none!important;
        }
    </style>
@endsection
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Search Results: {{$query}}</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">Search</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{$query}}</li>
                            </ol>
                        </nav>
                    </div>


                    <div class="separator mb-5"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <table class="data-table responsive nowrap mb-3">
                            <thead>
                                <th>{{$items->count()}} Results</th>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="mb-4">
                                            <a href="/item/{{$item->id}}">
                                                <p class="list-item-heading mb-1 color-theme-1">{{ucwords($item->name)}}</p>
                                                <p class="mb-1 text-muted text-small">Menu | {{ucwords($item->category->name)}}</p>
                                                <p class="mb-4 text-small">{{ucfirst($item->description)}}</p>
                                            </a>
                                            <div class="separator"></div>
                                        </div>
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
@endsection
@section('scripts')
<script src="{{asset('js/vendor/datatables.min.js')}}"></script>
@endsection