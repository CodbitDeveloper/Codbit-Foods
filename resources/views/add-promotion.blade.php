@extends('layouts.dashboard', ['page' => 'deals'])
@section('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/dropzone.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap-datepicker3.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/component-custom-switch.min.css')}}" />
    <style>
    .dn{
            display: none;
        }

    .text-italic{
        font-style: italic;
    }
    </style>
@endsection
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1>Add Promotion</h1>
                    <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="/home">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/deals">Deals and Promotions</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Add Promo
                            </li>
                        </ol>
                    </nav>
                    <div class="separator mb-5"></div>
                </div>
            </div>
            <form id="new-promo" method="post">
                <div class="row">
                    <div class="col-md-12 col-lg-12 mb-4">
                        <div class="card h-100">
        
                            <div class="card-body">
                                <h5 class="card-title">Promo Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label>Promo Name</label>
                                        <input type="text" class="form-control" name="name" id="promo_name" required>
                                        <span class="text-small text-danger dn"  id="promo_name_error">Enter a name for the promo</span>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label>Promo Code</label>
                                        <input type="text" class="form-control" name="code" id="promo_code" required>
                                        <span class="text-small text-danger dn"  id="promo_name_error">Enter a code for the promo</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-12">
                                            <label>Promo Duration</label>
                                            <div class="input-daterange input-group" id="datepicker">
                                                <input type="text" class="input-sm form-control" name="starts_at" placeholder="Starts at" required />
                                                <span class="input-group-addon"></span>
                                                <input type="text" class="input-sm form-control" name="expires_at" placeholder="Expires at" required />
                                            </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label>Promo Type</label>
                                        <select class="form-control select2-single no-search" name="is_fixed" required>
                                            <option value="0">Percentage reduction</option>
                                            <option value="1">Fixed amount reduction</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label>Promo Amount</label>
                                        <input type="number" name="promo_amount" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="4" required></textarea>
                                        <span class="text-small text-danger dn" id="promo_domain_error">Enter a description for the promo</span>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label>Image</label>
                                        <div class="col-12 dropzone" id="promo-image"></div>
                                        <span class="text-small text-danger dn" id="image_error">An image is required</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label>Maximum uses</label>
                                        <input type="number" class="form-control" name="max_uses" required/>
                                        <span class="text-small text-muted hint"><i>Enter -1 if it has an unlimited number of usages</i></span>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label>Maximum uses per user</label>
                                        <input type="number" class="form-control" name="max_uses_user" required/>
                                        <span class="text-small text-muted hint"><i>Enter -1 if a user can use it unlimited times till it expires</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="extra-details">
                    <div class="col-sm-12 col-lg-6 mb-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                    <h5 class="card-title">Customers</h5>
                                    <p class="text-small text-muted">Select the customers the promotion applies to</p>
                                    <div class="form-row">
                                        <div class="form-group row mb-1">
                                            <div class="col-12">
                                                <div class="custom-switch custom-switch-primary mb-2">
                                                    <input class="custom-switch-input" id="switch" type="checkbox">
                                                    <label class="custom-switch-btn" for="switch"></label>
                                                    <label style="vertical-align: 0.5em; font-size: 14px; font-weight: bold; margin-left: 8px;">This promo applies to all customers.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row" id="customer-search">
                                        <div class="col-12">

                                            <label>Search customers</label>
                                            <select class="form-control select2-multiple" multiple="multiple" name="customers">
                                                <option label="&nbsp;">&nbsp;</option>

                                                <optgroup label="Customers">
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">{{ucwords($customer->firstname).' '.ucwords($customer->lastname)}}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>

                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-6 mb-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                    <h5 class="card-title">Items</h5>
                                    <p class="text-small text-muted">Select the items the promotion applies to</p>
                                    <div class="form-row">
                                        <div class="form-group row mb-1">
                                            <div class="col-12">
                                                <div class="custom-switch custom-switch-primary mb-2">
                                                    <input class="custom-switch-input" id="switch2" type="checkbox">
                                                    <label class="custom-switch-btn" for="switch2"></label>
                                                    <label style="vertical-align: 0.5em; font-size: 14px; font-weight: bold; margin-left: 8px;">This promo applies to all items.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row" id="item-search">
                                        <div class="col-12">

                                            <label>Search food items</label>
                                            <select class="form-control select2-multiple" multiple="multiple" name="items">
                                                <option label="&nbsp;">&nbsp;</option>
                                                @foreach($categories as $category)
                                                <optgroup label="{{$category->name}}">
                                                    @foreach($category->items as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-right">
                    <button type="submit" class="btn btn-primary btn-block col-4 ml-auto mr-auto" id="submit-deal"><b>Done</b></button>
                </div>
            </form>
        </div>
@endsection
@section('scripts')
    <script src="{{asset('js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('js/vendor/dropzone.min.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-datepicker.js')}}"></script>
    <script>
        $(".input-daterange").datepicker({
                autoclose: true,
                templates: {
                leftArrow: '<i class="simple-icon-arrow-left"></i>',
                rightArrow: '<i class="simple-icon-arrow-right"></i>'
                },
                format: 'yyyy-mm-dd'
            });

        var drp =   new Dropzone('#promo-image', {
                url: '/api/promo/add',
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

                    //send all the form data along with the files:
                    this.on("sendingmultiple", function(data, xhr, formData) {
                        $('#new-promo').find('input, textarea, select').each(function(){
                            formData.append($(this).attr('name'), $(this).val())
                        });

                        if($('#customer-search').prop('disabled') == true){
                            formData.append('all_customers', true);
                        }

                        if( $('#item-search').prop('disabled') == true){
                            formData.append('all_items', true);
                        }
                        
                    });

                    this.on("success", function(response, responseText){
                        $('#submit-deal').html('Save');
                        $('#submit-deal').prop('disabled', false);

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
                            $("#new-promo").trigger('reset');
                        }
                    });

                    this.on('error', function(file, desc){
                        $('#submit-deal').html('Save');
                        $('#submit-deal').prop('disabled', false);
                        
                        if(desc.message != null || desc.message != undefined){
                            $.notify({
                                    // options
                                    message: desc.message
                                },{
                                    // settings
                                    type: 'danger'
                            });
                        }else{
                            if(desc.indexOf("File is too big") > -1){
                                $('#image-error').css('display', 'block');
                                $('#image-error').html(desc);
                            }
                        }

                        $.each(dzClosure.files, function(i, file){
                            file.status = Dropzone.QUEUED;
                        });
                        
                    });
                    
                }
            });

            $('#switch').on('change', function(){
                el = $(this);

                if(el.prop('checked')){
                    $('#customer-search').css('display', 'none');
                    $('#customer-search').prop('disabled', true);
                }else{
                    $('#customer-search').css('display', 'block');
                    $('#customer-search').prop('disabled', false);
                }
            });

            $('#switch2').on('change', function(){
                el = $(this);

                if(el.prop('checked')){
                    $('#item-search').css('display', 'none');
                    $('#item-search').prop('disabled', true);
                }else{
                    $('#item-search').css('display', 'block');
                    $('#item-search').prop('disabled', false);
                }
            });

            $("#new-promo").on("submit", function(e) {
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();

                    var error = false;
                    $(this).find('input, textarea, select').each(function(){
                        if($(this).val() == '' || $(this).val() == null){
                            error = true;
                        }else{
                            error = false;
                        }
                    });

                    if(drp.getQueuedFiles().length < 1){
                        $('#image-error').css('display', 'block');
                        error = true;
                    }else{
                        $('#image-error').css('display', 'none');
                        error = false;
                    }
                    
                    if(!error){
                        $('#submit-deal').prop('disabled', true);
                        $('#submit-deal').html('<div class="lds-dual-ring-white"></div>');
                            
                        drp.processQueue();
                    }
                        
                });

    </script>
@endsection