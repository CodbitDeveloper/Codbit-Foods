@extends('layouts.dashboard', ['page' => 'categories'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/dropzone.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/component-custom-switch.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-float-label.min.css')}}" />
    <style>
        .dz-progress{
            display:none;
        }

        .dz-remove{
            display:none;
        }

        .modal-confirm {		
            color: #636363;
            width: 400px;
        }
        .modal-confirm .modal-content {
            padding: 20px;
            border-radius: 5px;
            border: none;
            text-align: center;
            font-size: 14px;
        }
        .modal-confirm .modal-header {
            border-bottom: none;   
            position: relative;
        }
        .modal-confirm h4 {
            text-align: center;
            font-size: 26px;
            margin: 30px 0 -10px;
        }
        .modal-confirm .close {
            position: absolute;
            top: -5px;
            right: -2px;
        }
        .modal-confirm .modal-body {
            color: #999;
        }
        .modal-confirm .modal-footer {
            border: none;
            text-align: center;		
            border-radius: 5px;
            font-size: 13px;
            padding: 10px 15px 25px;
        }
        .modal-confirm .modal-footer a {
            color: #999;
        }		
        .trigger-btn {
            display: inline-block;
            margin: 100px auto;
        }
    </style>
@endsection
@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Categories</h1>
                        <div class="float-sm-right">
                            @if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager')
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-backdrop="static"
                                data-target="#newCategoryModal">ADD NEW</button>
                            @endif
                        </div>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Categories</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="separator mb-5"></div>
                    <div class="row list disable-text-selection" id="category-list">
                        @foreach($categories as $category)
                        <div class="col-xs-6 col-lg-4 col-12 mb-4">
                            <div class="card bg-dark text-white">
                                <img class="card-img br-8" src="/img/categories/{{$category->image}}" alt="Card image">
                                <div class="card-img-overlay br8">
                                    <div class="position-relative mb-3">
                                        <a href="#" class="badge badge-pill badge-theme-2 cursor" onclick="editCategory({{$category}})">EDIT</a>
                                        <a href="#" class="badge badge-pill badge-theme-1 cursor" onclick="deleteCategory({{$category->id}})">DELETE</a>
                                    </div>
                                    <h1><b>{{$category->name}}</b></h1>
                                </div>
                            </div>
                        </div>
                        @endforeach()
                    </div>
                    {{$categories->links()}}
                </div>
            </div>
        </div>
        
        <div class="modal fade modal-right" id="newCategoryModal" role="dialog" aria-labelledby="newCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" enctype="multipart/formdata" id="new-category-form" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="close mb-4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div>
                            <h3 class="mb-4">New Category</h3>
                            <div class="col-12">
                                <div class="form-row">
                                    <label class="form-group has-float-label col-12">
                                        <input class="form-control" id="new-cat-name"/>
                                        <span>Name</span>
                                    </label>
                                </div>
                                <div class="form-row">
                                <div class="col-12 dropzone" id="myDropzone"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit-all"><b>Save</b></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="modal fade modal-right" id="editCategoryModal" role="dialog" aria-labelledby="editCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="put" enctype="multipart/formdata" id="edit-category-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="close mb-4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div>
                            <h3 class="mb-4">Edit Category</h3>
                            <div class="col-12">
                                <div class="form-row">
                                    <label class="form-group has-float-label col-12">
                                        <input class="form-control" id="edit-cat-name"/>
                                        <input type="hidden" name="id" id="edit-cat-id"/>
                                        <span>Name</span>
                                    </label>
                                </div>
                                <div class="form-row">
                                    <div class="col-12 dropzone" id="myDropzone2"></div>
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
                        <p>Do you really want to delete this category? This process cannot be undone.</p>
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
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('js/vendor/dropzone.min.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;

        $('#myDropzone').dropzone({
            url: '/api/categories/add',
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
                $("#submit-all").on("click", function(e) {
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();

                    if(!$('#new-cat-name').val() == ''){
                        $('#submit-all').html('<div class="lds-dual-ring-white"></div>');
                    }
                    
                    dzClosure.processQueue();
                });

                //send all the form data along with the files:
                this.on("sendingmultiple", function(data, xhr, formData) {
                    formData.append("name", $("#new-cat-name").val());
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
                        $("#new-category-form").trigger('reset');
                        $("#category-list").append('<div class="col-xs-6 col-lg-4 col-12 mb-4"><div class="card bg-dark text-white"><img class="card-img br-8" src="img/categories/'+data.data.image+'" alt="Card image"><div class="card-img-overlay br8"><div class="position-relative mb-3"><a href="#" class="badge badge-pill badge-theme-1 cursor">EDIT</a></div><h1><b>'+data.data.name+'</b></h1></div></div></div>');
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

        $('#myDropzone2').dropzone({
                method: 'put',
                url: '/api/categories/update',
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 5,
                maxFiles: 5,
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
                        dzClosure2.options.url += "/"+$('#edit-cat-id').val();
                        $('#submit-edit').html('<div class="lds-dual-ring-white"></div>');
                        if (dzClosure2.getQueuedFiles().length > 0) {         
                            dzClosure2.options.method = "POST";               
                            dzClosure2.processQueue();  
                        } else {                       
                             //ajax request to update item without image 
                             $.ajax({
                                 url : dzClosure2.options.url,
                                 method: 'put',
                                 data: 'name='+$('#edit-cat-name').val(),
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

                                        
                                        setTimeout(function(){
                                            location.reload();
                                        }, 500);
                                    }

                                    
                    
                                    $(".modal").modal('hide');
                                 },

                                 error: function(err, desc){
                                    $('#submit-edit').html('Save');
                                    $('#submit-edit').prop('disabled', false);
                                    $.notify({
                                            // options
                                            message: data.message
                                        },{
                                            // settings
                                            type: 'success'
                                        });
                    
                                        $(".modal").modal('hide');
                                 }
                             })
                        }   
                    });

                    
                    this.on("sending", function(file, xhr, frmData) {
                        frmData.append("name", $("#edit-cat-name").val());
                        alert(JSON.stringify(frmData));
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

                            setTimeout(function(){
                                location.reload();
                            }, 500);

                            dzClosure.removeAllFiles();
                            $("#edit-category-form").trigger('reset');
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

            function editCategory(category){

                $("#edit-cat-name").val(category.name);
                $("#edit-cat-id").val(category.id);

                $('#editCategoryModal').modal('show'); 

                var img = {
                    name: category.image,
                    size: 12345
                }

                Dropzone.forElement('#myDropzone2').emit("addedfile", img);
                Dropzone.forElement('#myDropzone2').emit("thumbnail", img, "/img/categories/"+img.name);
            }

            function deleteCategory(category){
                $("#delete-cat-id").val(category);

                $('#deleteModal').modal('show');
            }

            $('#btn-delete').on('click', function(e){
                e.preventDefault();
                var btn = $(this);
                btn.html('<div class="lds-dual-ring-white"></div>');

                $.ajax({
                    'method' : 'DELETE',
                    'url' : '/api/categories/delete/'+ $("#delete-cat-id").val(),
                    'success': function(data){
                        $(".modal").modal('hide');
                        btn.html('Delete');

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
                    'error' : function(err){
                        btn.html('Delete');
                        $(".modal").modal('hide');

                        $.notify({
                            message: 'Network error'
                        },{
                            type: 'error'
                        });
                    }
                })
            })
            $('#editCategoryModal').on('hidden.bs.modal', function(){
                $('.dz-preview').remove();
                $('#submit-edit').html('Save');
            });
    </script>
@endsection