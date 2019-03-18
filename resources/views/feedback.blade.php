@extends('layouts.dashboard', ['page' => 'feedback'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-stars.css')}}" />
    <style>
        .dn{
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
            <div class="row app-row">
                <div class="col-12 chat-app dn">
                    <div class="d-flex flex-row justify-content-between mb-3">
                        <div class="d-flex flex-row chat-heading">
                            <div class=" d-flex min-width-zero">
                                <div class="card-body pl-0 align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero">
                                    <div class="min-width-zero">
                                        <a href="#">
                                            <h1 class="mb-1 truncate " id="name"></h1>
                                        </a>
                                        <p class="mb-0 text-muted text-small" id="last_feedback"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="separator mb-5"></div>

                    <div class="scroll">


                        <div id="feedbacks">
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="app-menu">
            <ul class="nav nav-tabs card-header-tabs ml-0 mr-0 mb-1" role="tablist">
                <li class="nav-item w-50 text-center">
                    <a class="nav-link active" id="first-tab" data-toggle="tab" href="#firstFull" role="tab"
                        aria-selected="true">Feedback</a>
                </li>
            </ul>

            <div class="p-4">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="firstFull" role="tabpanel" aria-labelledby="first-tab">

                        <div class="scroll">
                            @foreach($feedbacks as $feedback)
                            <?php $user = $feedback[0]->customer ?>
                            <div class="d-flex flex-row mb-1 border-bottom pb-3 mb-3">
                                <a class="d-flex mr-3" href="#">
                                    <img class="round" height="30" style="width: 30px" avatar="{{$user->firstname}} {{$user->lastname}}" />
                                </a>
                                <div class="d-flex flex-grow-1 min-width-zero">
                                    <div class="pl-0 align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero">
                                        <div class="min-width-zero">
                                            <a href="#" onclick = "populateChat({{$feedback}})">
                                                <p class=" mb-0 truncate">{{$user->firstname.' '.$user->lastname}}</p>
                                            </a>
                                            <p class="mb-1 text-muted text-small">{{Carbon\Carbon::parse($feedback[0]->created_at)->diffForHumans()}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if($feedbacks->count() < 1)
                              <span class="text-small text-muted">No feedback from customers yet</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <a class="app-menu-button d-inline-block d-xl-none" href="#">
                <i class="simple-icon-refresh"></i>
            </a>
        </div>

        <div class="chat-input-container d-flex justify-content-between align-items-center">
            <input class="form-control flex-grow-1" type="text" placeholder="Say something back">
            <div>
                <button type="button" class="btn btn-primary icon-button large">
                    <i class="simple-icon-paper-plane"></i>
                </button>

            </div>
        </div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('js/timeago.js')}}" ></script>
<script>
    function populateChat(feedback){
        $('.dn').css('display','none');
        $('#name').html(feedback[0].customer.firstname+' '+feedback[0].customer.lastname);
        $('#last_feedback').html('Last feedback ' + '<span class="timeago" title="' + feedback[0].created_at + '">' + feedback[0].created_at + '</span>');
        $('#feedbacks').html('');
        for(var i = 0; i<feedback.length; i++){
            var feed = feedback[i];
            $('#feedbacks').append('<div class="card d-inline-block mb-3 float-left">'+
            '<div class="position-absolute pt-1 pr-2 r-0">'+
            '<span class="timeago text-extra-small text-muted" title="'+feed.created_at+'"></span>'+
            '</div>'+'<div class="card-body">'+'<div class="d-flex flex-row pb-2">'+
            '<select class="rating" data-current-rating="'+feed.ratings+'" data-readonly="true">'+
            '<option value="1">1</option>'+'<option value="2">2</option>'+
            '<option value="3">3</option>'+'<option value="4">4</option>'+
            '<option value="5">5</option>'+'</select>'+' </div>' + '<div>' +
            '<p class="mb-0 text-semi-muted">'+feed.suggestion+'</p></div></div></div>'+
            '<div class="clearfix"></div>');     
        }

        if ($().barrating) {
            $(".rating").each(function() {
                var current = $(this).data("currentRating");
                var readonly = $(this).data("readonly");
                $(this).barrating({
                theme: "bootstrap-stars",
                initialRating: current,
                readonly: readonly
                });
            });
        }

        timeAgo();
        
        $('.dn').css('display','block');
    }
</script>
@endsection