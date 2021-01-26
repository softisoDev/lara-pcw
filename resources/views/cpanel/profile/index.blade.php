@extends('cpanel.layouts.main')

@section('content')
    <div class="row">

        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="m-t-30" style="text-align: center;">
                        <img src="{{asset('cpanel-asset/images/users/9.png')}}" class="img-circle" width="150"/>
                        <h4 class="card-title m-t-10">{{$user->name}}</h4>
                        <h6 class="card-subtitle">Admin</h6>
                    </div>
                </div>
                <div>
                    <hr>
                </div>
                <div class="card-body"><small class="text-muted">Email address </small>
                    <h6>{{$user->email}}</h6>
                    <br/>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#profile"
                                            role="tab">Personal Details</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#changePassword"
                                            role="tab">Change Password</a></li>
                </ul>

                <div class="tab-content pt-3">

                    <div class="tab-pane active" id="profile" role="tabpanel">
                        <div class="card-body">

                            {!! Form::open(array('id'=>'personal-detail','method' =>'POST', 'url' => addSlash2Url(route('admin.profile.update', $user->id)), 'class'=>'floating-labels')) !!}

                            @method('PUT')

                            <div class="form-group">
                                {!! Form::label('full_name', 'Full name') !!}
                                {!! Form::text('full_name', $user->name, array('class'=>'form-control')) !!}
                                @error('full_name')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            <div class="form-group">
                                {!! Form::label('email','Email') !!}
                                {!! Form::email('email', $user->email, array('class'=>'form-control')) !!}
                                @error('email')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            <div class="form-group pull-right">
                                {!! Form::submit('Update', array('id'=>'detail_submit', 'class'=>'btn btn-primary')) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="tab-pane" id="changePassword" role="tabpanel">
                        <div class="card-body">
                            {!! Form::open(array('id'=>'password-form','method' =>'POST', 'url' => addSlash2Url(route('admin.profile.password.update', $user->id)), 'class'=>'floating-labels')) !!}

                            @method('PUT')

                            <div class="form-group">
                                {!! Form::label('current_password', 'Current password') !!}
                                {!! Form::password('current_password', array('class'=>'form-control')) !!}
                                @error('current_password')<small
                                    class="text-red">{{$message}}</small>@enderror
                            </div>

                            <div class="form-group">
                                {!! Form::label('new_password', 'New password')!!}
                                {!! Form::password('new_password', array('class'=>'form-control')) !!}
                                @error('new_password')<small
                                    class="text-red">{{$message}}</small>@enderror
                            </div>

                            <div class="form-group">
                                {!! Form::label('confirm_new_password', 'New password repeat') !!}
                                {!! Form::password('confirm_new_password', array('class'=>'form-control')) !!}
                                @error('confirm_new_password')<small
                                    class="text-red">{{$message}}</small>@enderror
                            </div>

                            <div class="form-group pull-right">
                                {!! Form::submit('Update', array('id'=>'password_submit', 'class'=>'btn btn-primary')) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>


                </div>

            </div>
        </div>

    </div>
@endsection
