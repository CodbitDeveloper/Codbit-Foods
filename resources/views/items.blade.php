@extends('layouts.dashboard', ['page' => 'items'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/dropzone.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/component-custom-switch.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-float-label.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-tagsinput.css')}}" />
    <style>
         .dz-progress{
            display:none;
        }

        .card-img-top{
            height: 250px;
            object-fit: cover;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Menu Items</h1>
                        @if(strtolower(Auth::user()->role) != 'attendant')
                        <div class="float-sm-right">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-backdrop="static"
                                data-target="#newItemModal">ADD NEW</button>
                        </div>
                        @endif
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Items</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="separator mb-5"></div>
                    <div class="row list disable-text-selection">
                        @foreach($items as $item)
                        <div class="col-lg-4 col-sm-6 mb-4">
                            <div class="card">
                                <div class="position-relative">
                                    <a href="/item/{{$item->id}}"><img class="card-img-top <?php if($item->active == 0){echo 'img-inactive';}?>" src="/img/foods/{{$item->image}}" alt="{{$item->name}}" id="img-{{$item->id}}"></a>
                                    <a href="#" class="badge badge-pill badge-theme-1 position-absolute badge-top-left cursor">GHS {{$item->price}}</a>
                                    <span class="badge badge-pill badge-secondary position-absolute badge-top-left-2 text-uppercase">{{$item->category->name}}</span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group row mb-1 float-right">
                                                @if(strtolower(Auth::user()->role) != 'attendant')
                                                <div class="col-12">
                                                    <div class="custom-switch custom-switch-primary mb-2" id="switch-{{$item->id}}">
                                                        <input class="custom-switch-input" id="input-{{$item->id}}" type="checkbox"  <?php if($item->active == 1){echo "checked";}?> onclick="toggleActive({{$item->id}})"/>
                                                        <label class="custom-switch-btn" for="input-{{$item->id}}"></label>
                                                    </div>
                                                    <div class="lds-dual-ring" id="loader-{{$item->id}}" style="display:none;"></div>
                                                </div>
                                                @endif
                                            </div>
                                            <a href="/item/{{$item->id}}">
                                                <p class="list-item-heading mb-4">{{$item->name}}</p>
                                            </a>
                                            <div class="hidden-text">
                                                <span class="text-muted text-small mb-0 font-weight-light">{{$item->description}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade modal-right" id="newItemModal" role="dialog" aria-labelledby="newItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="close mb-4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @if(strtolower(Auth::user()->role) != 'attendant')
                        <div>
                            <h3 class="mb-4">New Item</h3>
                            <form method="post" action="#" id="new-item-form">
                                @csrf
                                <div class="col-12">
                                    <div class="form-row">
                                        <label class="form-group has-float-label col-12">
                                            <input class="form-control" name="name" id="new-item-name"/>
                                            <span>Item Name</span>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <label class="form-group has-float-label col-12">
                                            <input class="form-control" type="number" name="price" id="new-item-price"/>
                                            <span>Item Price</span>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <label class="form-group has-float-label col-12">
                                            <select class="form-control select2-single col-12" name="category_id" id="new-item-category">
                                                <option selected disabled hidden></option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                            <span>Category</span>
                                        </label>
                                    </div>
                                    <div class="form-row mb-3">
                                        <label class="form-group has-float-label col-12">
                                            <textarea class="form-control" rows="4" name="description" id="new-item-description"></textarea>
                                            <span>Description</span>
                                        </label>
                                    </div>

                                    <div class="form-row">
                                        <label class="form-group has-float-label col-12">
                                        <input data-role="tagsinput" class="form-control" type="text" id="new-item-ingredients">
                                            <span>Ingredients</span>
                                        </label>
                                    </div>

                                    <div class="form-row">
                                    <div class="col-12 dropzone" id="itemImage"></div>
                                    </div>
                                </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit"><b>Save Item</b></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="{{asset('js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('js/vendor/dropzone.min.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('js/init-scrollbar.js')}}"></script>
    <script>
        function toggleActive(item){
            el = $('#input-'+item);
            $("#switch-"+item).css('display', 'none');
            $('#loader-'+item).css('display', 'block');

            $.ajax({
                'method': 'put',
                'url': '/api/items/activate/'+item,
                'success': function(data){
                        if(data.error){
                            $.notify({
                                // options
                                message: data.message
                            },{
                                // settings
                                type: 'danger'
                            });
                            el.prop('checked', !el.prop('checked'));
                            
                            $('#loader-'+item).css('display', 'none');
                            $("#switch-"+item).css('display', 'block');
                            $('#img-'+item).removeClass('img-inactive');
                        }else{
                            $.notify({
                                // options
                                message: data.message
                            },{
                                // settings
                                type: 'success'
                            });

                            $('#loader-'+item).css('display', 'none');
                            $("#switch-"+item).css('display', 'block');

                            $('#img-'+item).addClass('img-inactive');
                        }
                },
                'error': function(data){
                    $.notify({
                                // options
                                message: 'Network error'
                            },{
                                // settings
                                type: 'danger'
                            });
                            el.prop('checked', !el.prop('checked'));
                            $('#loader-'+item).css('display', 'none');
                            $("#switch-"+item).css('display', 'block');
                            $('#img-'+item).removeClass('img-inactive');
                }
            });
        }

        Dropzone.autoDiscover = false;

        $('#itemImage').dropzone({
            url: '/api/items/add',
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 5,
            maxFilesize: 1,
            acceptedFiles: 'image/*',
            thumbnailWidth: 160,
            previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "> <div class="p-0 w-30 position-relative"> <div class="dz-error-mark"><span><i class="simple-icon-exclamation"></i>  </span></div>      <div class="dz-success-mark"><span><i class="simple-icon-check-circle"></i></span></div>      <img data-dz-thumbnail class="img-thumbnail border-0" /> </div> <div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"> <div> <span data-dz-name /> </div> <div class="text-primary text-extra-small" data-dz-size /> </div> <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>        <div class="dz-error-message"><span data-dz-errormessage></span></div>            </div><a href="#" class="remove" data-dz-remove> <i class="simple-icon-trash"></i> </a></div>',
            init: function() {
                dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

                // for Dropzone to process the queue (instead of default form behavior):
                $("#submit").on("click", function(e) {
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();

                    if(!$('#new-cat-name').val() == ''){
                        $('#submit-all').html('<div class="lds-dual-ring-white"></div>');
                    }

                    dzClosure.processQueue();
                });

                //send all the form data along with the files:
                this.on("sendingmultiple", function(data, xhr, formData) {
                    formData.append("name", $("#new-item-name").val());
                    formData.append("category_id", $("#new-item-category").val());
                    formData.append("price", $("#new-item-price").val());
                    formData.append("description", $("#new-item-description").val());
                    formData.append("ingredients", $("#new-item-ingredients").val());
                });

                this.on("success", function(response, responseText){
                    $('#submit-all').html('Save');
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

                        dzClosure.removeAllFiles();
                        $("#new-item-form").trigger('reset');
                        setTimeout(function(){
                                location.replace('/item/'+data.id);
                            }, 500);
                    }
                });

                this.on('error', function(err, desc){
                    $('#submit-all').html('Save');
                    $(".modal").modal('hide');
                    $.notify({
                            // options
                            message: "Network error. Try again."
                        },{
                            // settings
                            type: 'danger'
                        });
                });
                
            }
        });
    </script>
@endsection