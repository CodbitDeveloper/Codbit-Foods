<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Codbit Foods</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{asset('font/iconsmind/style.css')}}" />
    <link rel="stylesheet" href="{{asset('font/simple-line-icons/css/simple-line-icons.css')}}" />

    <link rel="stylesheet" href="{{asset('css/vendor/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/dropzone.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/vendor/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/main.css')}}" />
    <link rel="stylesheet" href="{{asset('css/dore.light.red.css')}}" />
    <style>
        .round{
            border-radius: 50%;
        }

        .dn{
            display: none;
        }
    </style>
</head>

<body id="app-container" class="menu-default show-spinner">
    <nav class="navbar fixed-top">
        <div class="d-flex align-items-center navbar-left">
            <a href="#" class="menu-button d-none d-md-block">
                <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                    <rect x="0.48" y="0.5" width="7" height="1" />
                    <rect x="0.48" y="7.5" width="7" height="1" />
                    <rect x="0.48" y="15.5" width="7" height="1" />
                </svg>
                <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                    <rect x="1.56" y="0.5" width="16" height="1" />
                    <rect x="1.56" y="7.5" width="16" height="1" />
                    <rect x="1.56" y="15.5" width="16" height="1" />
                </svg>
            </a>

            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                    <rect x="0.5" y="0.5" width="25" height="1" />
                    <rect x="0.5" y="7.5" width="25" height="1" />
                    <rect x="0.5" y="15.5" width="25" height="1" />
                </svg>
            </a>

            <div class="search" data-search-path="Layouts.Search.html?q=">
                <input placeholder="Search...">
                <span class="search-icon">
                    <i class="simple-icon-magnifier"></i>
                </span>
            </div>
        </div>

        <div class="navbar-right">
            <div class="header-icons d-inline-block align-middle">
                <div class="position-relative d-inline-block">
                    <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="simple-icon-bell"></i>
                        <span class="count">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3 scroll position-absolute" id="notificationDropdown">

                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <div class="pl-3 pr-2">
                                <a href="#">
                                    <p class="font-weight-medium mb-1">General Mosquito just placed a request for to
                                        register his restaurant</p>
                                    <p class="text-muted mb-0 text-small">09.04.2019 - 12:45</p>
                                </a>
                            </div>
                        </div>

                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <div class="pl-3 pr-2">
                                <a href="#">
                                    <p class="font-weight-medium mb-1">K Mole has requested for a password reset</p>
                                    <p class="text-muted mb-0 text-small">09.04.2019 - 12:45</p>
                                </a>
                            </div>
                        </div>


                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <div class="pl-3 pr-2">
                                <a href="#">
                                    <p class="font-weight-medium mb-1">New client email received</p>
                                    <p class="text-muted mb-0 text-small">09.04.2019 - 12:45</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                    <i class="simple-icon-size-fullscreen"></i>
                    <i class="simple-icon-size-actual"></i>
                </button>

            </div>

            <div class="user d-inline-block">
                <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="name">{{ ucfirst(Auth::guard('admin')->user()->firstname) }} {{ ucfirst(Auth::guard('admin')->user()->lastname) }}</span>
                    <span>
                        <img class="round" width="40" height="40" avatar="{{ ucfirst(Auth::guard('admin')->user()->firstname) }} {{ ucfirst(Auth::guard('admin')->user()->lastname) }}" />
                    </span>
                </button>

                <div class="dropdown-menu dropdown-menu-right mt-3">
                    <a class="dropdown-item" href="#">My account &nbsp;<i class="simple-icon-user"></i></a>
                    <a class="dropdown-item" href="#">Sign out &nbsp;<i class="simple-icon-logout"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <div class="sidebar">
        <div class="main-menu">
            <div class="scroll">
                <ul class="list-unstyled">
                    <li class="active">
                        <a href="/admin">
                            <i class="iconsmind-Shop-2"></i>
                            <span>Restaurants</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="iconsmind-Pie-Chart3"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sub-menu">
            <div class="scroll">
                <ul class="list-unstyled" data-link="start">
                    <li class="active">
                        <a href="#">
                            <i class="simple-icon-list"></i> Restaurants List
                        </a>
                    </li>
                    <li class="active">
                        <a href="#">
                            <i class="simple-icon-plus"></i> New Restaurant
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1>Restaurants</h1>
                    <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item active" aria-current="page">
                                Create new
                            </li>
                        </ol>
                    </nav>
                    <div class="separator mb-5"></div>
                </div>
            </div>
            <div id="form">
                <div class="row">
                    <div class="col-md-12 col-lg-12 mb-4">
                        <div class="card h-100">

                            <div class="card-body">
                                <h5 class="card-title">Restaurant Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Restaurant Name</label>
                                        <input type="text" class="form-control" name="name" id="restaurant_name">
                                        <span class="text-small text-danger dn"  id="restaurant_name_error">Enter a name for the restaurant</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" id="restaurant_email">
                                        <span class="text-small text-danger dn" id="restaurant_email_error">Enter an email</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4">
                                        <label>Contact Number</label>
                                        <input type="tel" class="form-control" name="contact_number"  id="restaurant_contact">
                                        <span class="text-small text-danger dn" id="restaurant_contact_error">Enter a contact number for the restaurant</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Webstite</label>
                                        <input type="url" class="form-control" name="website"  id="restaurant_website">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Domain</label>
                                        <input type="text" class="form-control" name="domain"  id="restaurant_domain">
                                        <span class="text-small text-danger dn" id="restaurant_domain_error">Enter a domain for the restaurant</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label>Logo</label>
                                    <div class="col-12 dropzone" id="restaurant-image"></div>
                                    <span class="text-small text-danger dn" id="image_error">A logo is required</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="extra-details">
                    <div class="col-md-12 col-lg-8 mb-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                    <h5 class="card-title">User Details</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" name="firstname"  id="user_fname">
                                            <span class="text-small text-danger dn" id="user_fname_error">Enter the first name for the user</span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" name="lastname" id="user_lname">
                                            <span class="text-small text-danger dn" id="user_lname_error">Enter a last name for the user</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Username</label>
                                            <input type="text" class="form-control" name="username"  id="user_username">
                                            <span class="text-small text-danger dn" id="user_username_error">Enter username for the restaurant</span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Phone</label>
                                            <input type="tel" class="form-control" name="phone" id="user_phone"> 
                                            <span class="text-small text-danger dn" id="user_phone_error">Enter a contact number for the user</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Gender</label>
                                            <select class="form-control select2-single" name="gender" id="user_gender">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <span class="text-small text-danger dn" id="user_gender_error">Select a gender</span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Branch Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Branch Name</label>
                                        <input type="text" class="form-control" name="branch_name" id="branch_name">
                                        <span class="text-small text-danger dn" id="branch_name_error">Enter a branch name</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Location</label>
                                        <input type="text" class="form-control" name="branch_location" id="branch_location">
                                        <span class="text-small text-danger dn" id="branch_location_error">Enter a location</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" name="branch_phone_number"  id="branch_contact">
                                        <span class="text-small text-danger dn" id="branch_contact_error">Enter a contact number for the branch</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Database Credentials</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Database Name</label>
                                        <input type="text" class="form-control" name="DB_name"  id="DB_name">
                                        <span class="text-small text-danger dn" id="DB_name_error">Enter a database name</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Database Username</label>
                                        <input type="text" class="form-control" name="DB_username" id="DB_username">
                                        <span class="text-small text-danger dn" id="DB_username_error">Enter a database username</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Database Password</label>
                                        <input type="text" class="form-control" name="DB_password" id="DB_password">
                                        <span class="text-small text-danger dn" id="DB_password_error">Enter a database password</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-lg mb-1 mt-4 btn-block" id="submit-all">Create Restaurant</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{asset('js/vendor/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/vendor/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('js/vendor/mousetrap.min.js')}}"></script>
    <script src="{{asset('js/vendor/dropzone.min.js')}}"></script>
    <script src="{{asset('js/vendor/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('js/dore.script.js')}}"></script>
    <script src="{{asset('js/scripts.single.theme.js')}}"></script>
    <script>
        if ($().dropzone) {
            Dropzone.autoDiscover = false;
        }

        $(document).ready(initializeDZ());

        function initializeDZ(){

        $('#restaurant-image').dropzone({
            url: '/api/restaurant/add',
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
                    var error = false;

                    $('#form input').each(function(){
                        el = $(this);
                        if(el.val() == '' || el.val() == null){
                            el.closest('div').find('.text-danger').css('display', 'block');
                            error = true;
                        }else{
                            el.closest('div').find('.text-danger').css('display', 'none');
                        }
                    });

                    if(dzClosure.getQueuedFiles().length == 0){
                        error = true;
                        $('#image_error').css('display', 'block');
                    }else{
                        $('#image_error').css('display', 'none');
                    }

                    if(!error){
                        $(this).prop('disabled', true);
                        $(this).html('Saving Restaurant...');
                        dzClosure.processQueue();
                    }

                });

                //send all the form data along with the files:
                this.on("sendingmultiple", function(data, xhr, formData) {
                    formData.append("name", $("#restaurant_name").val());
                    formData.append("email", $("#restaurant_email").val());
                    formData.append("contact_number", $("#restaurant_contact").val());
                    formData.append("domain", $("#restaurant_domain").val());
                    formData.append("website", $("#restaurant_website").val());
                });

                this.on("success", function(response, responseText){
                    
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

                        $('#submit-all').html('Create Restaurant');
                        $('#submit-all').prop('disabled', false);
                    }else{
                        $('#submit-all').html('Setting Database Up...');
                        
                        var id = data.data.id;
                        var postData = 'id='+id;
                         
                        $('#extra-details input').each(function(){
                            el = $(this);
                            postData = postData + '&' + el.attr('name') + '=' + el.val();
                        });

                        postData = postData + '&gender=' + $('#user_gender').val();
                        
                        
                        $.ajax({
                            url: '/api/restaurant/add_db',
                            method: 'POST',
                            data: postData,
                            success: function(data){
                                $('#submit-all').prop('disabled', false);
                                $('#submit-all').html('Create Restaurant');
                                
                                dzClosure.removeAllFiles();
                                $('#form input').each(function(){
                                    el = $(this);
                                    el.val('')
                                });
                            },
                            error: function(err){            
                                $('#submit-all').prop('disabled', false);
                                $('#submit-all').html('Cleaning Database...');
                                alert(err);
                            }
                        })
                    }
                });

                this.on('error', function(err, desc){
                    $('#submit-all').prop('disabled', false);
                    $('#submit-all').html('Create Restaurant');
                    
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
        }
    </script>
</body>

</html>