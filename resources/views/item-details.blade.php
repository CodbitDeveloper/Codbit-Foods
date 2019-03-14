@extends('layouts.dashboard', ['page'=>'items'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/dropzone.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/component-custom-switch.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-float-label.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-stars.css')}}" />
    <style>
    .dz-progress{
        display:none;
    }

    .dz-remove{
        display:none;
    }
    </style>
@endsection
@section('content')
    <div class="container-fluid disable-text-selection">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>{{$item->name}}</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">Item</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{$item->name}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"
                        aria-controls="first" aria-selected="true">DETAILS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="first"
                        aria-selected="true">ACTIVITY</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-4">
                            <div class="card mb-4">
                                <div class="position-absolute card-top-buttons">
                                    <button class="btn btn-outline-white icon-button" data-toggle="modal" data-target="#editItemModal">
                                        <i class="simple-icon-pencil"></i>
                                    </button>
                                </div>
                                <img src="/img/foods/{{$item->image}}" alt="{{$item->name}}" class="card-img-top" />

                                <div class="card-body">
                                    <p class="text-muted text-small mb-2">Description</p>
                                    <p class="mb-3">
                                        {{$item->description}}</p>
                                    <p class="text-muted text-small mb-2">Average Rating</p>
                                    <div class="form-group mb-3">
                                        <select class="rating" data-current-rating="{{$item->comments->count() > 0 ? $item->comments->avg('ratings') : 0}}" data-readonly="true">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>

                                    <p class="text-muted text-small mb-2">Price</p>
                                    <p class="mb-3">GH&cent; {{$item->price}}</p>

                                    <p class="text-muted text-small mb-2">Ingredients</p>
                                    <p class="mb-3">
                                        @foreach($item->ingredients as $ingredient)
                                        <a href="#">
                                            <span class="badge badge-pill badge-outline-theme-2 mb-1">{{$ingredient->name}}</span>
                                        </a>
                                        @endforeach
                                        @if($item->ingredients->count() < 1)
                                        <span class="text-small">No ingredients specified</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-12">
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            @if($item->orders->count() > 0)
                                            <p class="lead color-theme-1 mb-1 value">{{Carbon\Carbon::parse($item->orders[$item->orders->count()-1]->created_at)->diffForHumans()}}</p>
                                            @else
                                            <p class="lead color-theme-1 mb-1 value">No order</p>
                                            @endif
                                            <p class="mb-0 label text-small">Last Order</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="lead color-theme-1 mb-1 value">{{$item->orders->count()}} @if($item->orders->count()!=1) orders @else order @endif</p>
                                            <p class="mb-0 label text-small">Total All Time</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Comments</h5>
                                    <div class="scroll dashboard-list-with-thumbs">
                                        @foreach($item->comments as $comment)
                                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                                            <a href="#">
                                                <img class="round" height="40" style="width: 40px" avatar="{{$comment->customer->firstname}} {{$comment->customer->lastname}}" />
                                            </a>
                                            <div class="pl-3 pr-2">
                                                <a href="#">
                                                    <p class="font-weight-medium mb-0">{{$comment->comment}}
                                                    </p>
                                                    <p class="text-muted mb-1 text-small">{{$comment->customer->firstname}} {{$comment->customer->lastname}} |
                                                        {{Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}</p>
                                                </a>
                                                <div class="form-group mb-0">
                                                    <select class="rating" data-current-rating="{{$comment->ratings}}" data-readonly="true">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($item->comments->count() < 1)
                                        <span class="text-small text-muted">No comments for this item.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                    <div class="row">
                        Coming Soon
                    </div>
                </div>
            </div>
            <div class="modal fade modal-right" id="editItemModal" role="dialog" aria-labelledby="editItemModalLabel"
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
                                <h3 class="mb-4">Edit Item</h3>
                                <form method="post" id="edit-item" action="#">
                                <div class="col-12">
                                    <div class="form-row">
                                        <input name="id" type="hidden" value="{{$item->id}}"/>
                                        <label class="form-group has-float-label col-12">
                                            <input class="form-control" name="name" value="{{$item->name}}"/>
                                            <span>Item Name</span>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <label class="form-group has-float-label col-12">
                                            <input class="form-control" type="number" name="price" value="{{$item->price}}"/>
                                            <span>Item Price</span>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <label class="form-group has-float-label col-12">
                                            <select class="form-control select2-single col-12" name="category_id">
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" <?php if($category->id == $item->category_id){echo 'selected';} ?>>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                            <span>Category</span>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <label class="form-group has-float-label col-12">
                                            <textarea class="form-control" rows="4" name = "description">{{$item->description}}</textarea>
                                            <span>Description</span>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-12 dropzone" id="item-image"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submit-edit"><b>Save Item</b></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="{{asset('js/vendor/jquery.barrating.min.js')}}"></script>
    <script src="{{asset('js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('js/vendor/dropzone.min.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;

        $('#item-image').dropzone({
                method: 'POST',
                url: '/api/items/update/'+{{$item->id}},
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 1,
                maxFiles: 1,
                maxFilesize: 1,
                acceptedFiles: 'image/*',
                thumbnailWidth: 160,
                previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "> <div class="p-0 w-30 position-relative"> <div class="dz-error-mark"><span><i class="simple-icon-exclamation"></i>  </span></div>      <div class="dz-success-mark"><span><i class="simple-icon-check-circle"></i></span></div>      <img data-dz-thumbnail class="img-thumbnail border-0" /> </div> <div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"> <div> <span data-dz-name /> </div> <div class="text-primary text-extra-small" data-dz-size /> </div> <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>        <div class="dz-error-message"><span data-dz-errormessage></span></div>            </div><a href="#" class="remove" data-dz-remove> <i class="simple-icon-trash"></i> </a></div>',

                init: function() {
                    dzClosure2 = this; // Makes sure that 'this' is understood inside the functions below.

                    // for Dropzone to process the queue (instead of default form behavior):
                    $("#submit-edit").on("click", function(e) {
                        // Make sure that the form isn't actually being sent.
                        e.preventDefault();
                        $('#submit-edit').html('<div class="lds-dual-ring-white"></div>');
                        if (dzClosure2.getQueuedFiles().length > 0) {                    
                            dzClosure2.processQueue();  
                        } else {                       
                             //ajax request to update item without image
                             var postData = 'manner=edit';
                             $('#edit-item input').each(function(){
                                el = $(this);

                                postData = postData + '&' + el.attr('name') + '=' + el.val();
                             });

                             postData = postData + '&description=' + $('#edit-item textarea').val();
                             postData = postData + '&category_id=' + $('#edit-item select').val();

                             $.ajax({
                                 url : '/api/items/update/'+{{$item->id}},
                                 method: 'post',
                                 data: postData,
                                 success: function(data, status, xhr){
                                    $('#submit-edit').html('Save');
                                    $('#submit-edit').prop('disabled', false);
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

                                    }

                                    
                    
                                    $(".modal").modal('hide');
                                 },

                                 error: function(err, desc){
                                    $('#submit-edit').html('Save');
                                    $('#submit-edit').prop('disabled', false);
                                    $.notify({
                                            // options
                                            message: 'Network error'
                                        },{
                                            // settings
                                            type: 'danger'
                                        });
                    
                                        $(".modal").modal('hide');
                                 }
                             })
                        }   
                    });

                    
                    this.on("sending", function(file, xhr, formData) {
                        $('#edit-item input').each(function(){
                            el = $(this);
                            formData.append(el.attr('name'), el.val())
                         });

                       formData.append('description', $('#edit-item textarea').val());
                       formData.append('category_id', $('#edit-item select').val());
                    });


                    this.on("success", function(response, responseText){
                        $('#submit-edit').html('Save');
                        $('#submit-edit').prop('disabled', false);
                        $(".modal").modal('hide');
                        
                        var data = responseText;
                        console.log(data);
                        
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
                            $('.card-img-top').attr('src', '/img/foods/'+data.data.image);
                        }
                    });


                    this.on('error', function(err, desc){
                        $('#submit-edit').val('Save');
                        $('#submit-edit').prop('disabled', false);
                        $.notify({
                                // options
                                message: "Network error. Try again."
                            },{
                                // settings
                                type: 'danger'
                            });
                    
                        $(".modal").modal('hide');

                    });
                    
                }
            });

            $(document).ready(function(){
                var img = {
                    name: "{{$item->image}}",
                    size: 12345
                }

                Dropzone.forElement('#item-image').emit("addedfile", img);
                Dropzone.forElement('#item-image').emit("thumbnail", img, "/img/foods/"+img.name);
            })
    </script>
@endsection