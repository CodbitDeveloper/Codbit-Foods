@extends('layouts.dashboard', ['page' => 'categories'])
@section('styles')
    <link rel="stylesheet" href="css/vendor/select2.min.css" />
    <link rel="stylesheet" href="css/vendor/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="css/vendor/dropzone.min.css" />
    <link rel="stylesheet" href="css/vendor/component-custom-switch.min.css" />
    <link rel="stylesheet" href="css/vendor/bootstrap-float-label.min.css" />
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Categories</h1>
                        <div class="float-sm-right">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-backdrop="static"
                                data-target="#newCategoryModal">ADD NEW</button>
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
                                <img class="card-img br-8" src="img/categories/{{$category->image}}" alt="Card image">
                                <div class="card-img-overlay br8">
                                    <div class="position-relative mb-3">
                                        <a href="#" class="badge badge-pill badge-theme-1 cursor" onclick="editCategory({{$category}})">EDIT</a>
                                    </div>
                                    <h1><b>{{$category->name}}</b></h1>
                                </div>
                            </div>
                        </div>
                        @endforeach()
                    </div>
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
                <form method="post" enctype="multipart/formdata" id="edit-category-form" action="#">
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
                                <!--div class="col-12 dropzone" id="myDropzone2"></div-->
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
@endsection
@section('scripts')
    <script src="js/vendor/bootstrap-notify.min.js"></script>
    <script src="js/vendor/dropzone.min.js"></script>
    <script>
        Dropzone.options.myDropzone= {
            url: '/api/categories/add',
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 1,
            maxFilesize: 1,
            acceptedFiles: 'image/*',
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
                    $.notify({
                            // options
                            message: "Network error. Try again."
                        },{
                            // settings
                            type: 'danger'
                        });
                });
                
            }
        }

        /*Dropzone.options.myDropzone2= {
                url: '/api/categories/update',
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 5,
                maxFiles: 1,
                maxFilesize: 1,
                acceptedFiles: 'image/*',
                init: function() {
                    dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

                    // for Dropzone to process the queue (instead of default form behavior):
                    $("#submit-edit").on("click", function(e) {
                        // Make sure that the form isn't actually being sent.
                        e.preventDefault();
                        dzClosure.options.url += "/"+$('#edit-cat-id').val();
                        $('#submit-edit').html('<div class="lds-dual-ring-white"></div>');
                        dzClosure.processQueue();
                    });

                    this.on("success", function(response, responseText){
                        $('#submit-edit').html('Save');
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
                            $("#edit-category-form").trigger('reset');
                        }
                    });

                    this.on('error', function(err, desc){
                        $.notify({
                                // options
                                message: "Network error. Try again."
                            },{
                                // settings
                                type: 'danger'
                            });
                    });
                    
                }
            }

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

        $('#editCategoryModal').on('hidden.bs.modal', function(){
            $('.dz-preview').remove();
            $('#submit-edit').html('Save');
        });*/
    </script>
@endsection