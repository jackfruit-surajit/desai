@extends('manufacture.layouts.main')

@section('title', 'Manufacture Profile')
@section('breadcrumb-item', 'Admin')

@section('breadcrumb-item-active', 'My Account')

@section('css')
@endsection

@section('content')

      <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
          
          <div class="row">
            <div class="col-lg-5 col-xxl-3">
              <div class="card overflow-hidden">

                <div class="card-body position-relative">
                  <div class="text-center mt-3">
                    <div class="chat-avtar d-inline-flex mx-auto">
                      <img class="rounded-circle img-fluid wid-90 img-thumbnail"
                        src="{{isset($model->image) ? URL::asset('public/uploads/staff/'.$model->image ) : URL::asset('public/uploads/user/no-img.png')}}" alt="User image">
                      <i class="chat-badge bg-success me-2 mb-2"></i>
                    </div>
                    <h5 class="mb-0">{{$model->name}}</h5>
                    <p class="text-muted text-sm"><a href="mailto:{{$model->email}}" class="link-primary"> {{$model->email}}</a> </p>
                    
                  </div>
                </div>

                <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0" id="user-set-tab"
                  role="tablist" aria-orientation="vertical">
                  <a class="nav-link list-group-item list-group-item-action active" id="user-set-profile-tab"
                    data-bs-toggle="pill" href="#user-set-profile" role="tab" aria-controls="user-set-profile"
                    aria-selected="true">
                    <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Profile Overview</span>
                  </a>
                  <a class="nav-link list-group-item list-group-item-action" id="user-set-account-tab"
                    data-bs-toggle="pill" href="#user-set-account" role="tab" aria-controls="user-set-account"
                    aria-selected="false">
                    <span class="f-w-500"><i class="ph-duotone ph-notebook m-r-10"></i>Account Information</span>
                  </a>
                  <a class="nav-link list-group-item list-group-item-action" id="user-set-passwort-tab"
                    data-bs-toggle="pill" href="#user-set-passwort" role="tab" aria-controls="user-set-passwort"
                    aria-selected="false">
                    <span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Change Password</span>
                  </a>
                </div>
              </div>
              
            </div>

            <div class="col-lg-7 col-xxl-9">
              <div class="tab-content" id="user-set-tabContent">

                <div class="tab-pane fade show active" id="user-set-profile" role="tabpanel"
                  aria-labelledby="user-set-profile-tab">
                  
                  <div class="card">
                    <div class="card-header">
                      <h5>Profile Details</h5>
                    </div>
                    <div class="card-body">
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 pt-0">
                          <div class="row">
                            <div class="col-md-6">
                              <p class="mb-1 text-muted">Full Name</p>
                              <p class="mb-0">{{$model->name}}</p>
                            </div>
                            <div class="col-md-6">
                              <p class="mb-1 text-muted">Email</p>
                              <p class="mb-0">{{$model->email}}</p>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item px-0">
                          <div class="row">
                            <div class="col-md-6">
                              <p class="mb-1 text-muted">Phone</p>
                              <p class="mb-0">{{$model->phone}}</p>
                            </div>
                            <div class="col-md-6">
                              <!-- <p class="mb-1 text-muted">City</p>
                              <p class="mb-0">New York</p> -->
                            </div>
                          </div>
                        </li>
                        
                      </ul>
                    </div>
                  </div>
                  
                </div>

                <div class="tab-pane fade" id="user-set-account" role="tabpanel"
                  aria-labelledby="user-set-account-tab">

                  <form action="{{route('manufacture-profile')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="card">
                        <div class="card-header">
                        <h5>Account Information</h5>
                        </div>
                        
                        <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control"name="name" value="{{$model->name}}">
                                @if ($errors->has('name'))
                                  <span class="help-block"> {{ $errors->first('name') }} </span>
                                @endif
                            </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{$model->email}}">
                                @if ($errors->has('email'))
                                  <span class="help-block"> {{ $errors->first('email') }} </span>
                                @endif
                            </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{$model->phone}}">
                                @if ($errors->has('phone'))
                                  <span class="help-block"> {{ $errors->first('phone') }} </span>
                                @endif
                            </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" value="">
                                @if ($errors->has('image'))
                                  <span class="help-block"> {{ $errors->first('image') }} </span>
                                @endif
                            </div>
                            </div>
                            
                        </div>
                        </div>
                    </div>
                    
                    <div class="text-end btn-page">
                        <a href="{{route('admin-dashboard')}}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>

                  </form>

                </div>

                <div class="tab-pane fade" id="user-set-passwort" role="tabpanel"
                  aria-labelledby="user-set-passwort-tab">
                  
                  <form action="{{Route('manufacture-change-password')}}" method="post">
                    {{csrf_field()}}

                    <div class="card">
                      <div class="card-header">
                        <h5>Change Password</h5>
                      </div>
                      <div class="card-body">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item pt-0 px-0">
                            <div class=" row mb-0">
                              <label class="col-form-label col-md-4 col-sm-12 text-md-end">Current Password <span
                                  class="text-danger">*</span>
                              </label>
                              <div class="col-md-8 col-sm-12">
                                <input name="current_password" type="password" class="form-control" id="currentPassword">
                                @if ($errors->has('current_password'))
                                  <span class="help-block"> {{ $errors->first('current_password') }} </span>
                                @endif
                              </div>
                            </div>
                          </li>
                          <li class="list-group-item px-0">
                            <div class="row mb-0">
                              <label class="col-form-label col-md-4 col-sm-12 text-md-end">New Password <span
                                  class="text-danger">*</span></label>
                              <div class="col-md-8 col-sm-12">
                                <input name="password" type="password" class="form-control" id="newPassword">
                                @if ($errors->has('password'))
                                  <span class="help-block"> {{ $errors->first('password') }} </span>
                                @endif
                              </div>
                            </div>
                          </li>
                          <li class="list-group-item pb-0 px-0">
                            <div class="row mb-0">
                              <label class="col-form-label col-md-4 col-sm-12 text-md-end">Confirm Password <span
                                  class="text-danger">*</span></label>
                              <div class="col-md-8 col-sm-12">
                                <input name="confirm_password" type="password" class="form-control" id="renewPassword">
                                @if ($errors->has('confirm_password'))
                                  <span class="help-block"> {{ $errors->first('confirm_password') }} </span>
                                @endif
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <div class="text-end btn-page">
                      <a href="{{route('admin-dashboard')}}" class="btn btn-outline-secondary">Cancel</a>
                      <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>

                  </form>

                </div>
                
              </div>
            </div>
          </div>
        </div>
        <!-- [ sample-page ] end -->
      </div>
      <!-- [ Main Content ] end -->

@endsection
