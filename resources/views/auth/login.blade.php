@extends('layouts.app')
@section('content')

<body id="LoginForm">
<div class="container">
 <div class="login-form">
      <div class="main-div">
            <div class="panel">
                <center><img style="width:30%" src="https://renewhub.controlz.world/assets/img/loog.jpg"></center>
               <h2>Admin Login</h2>
               <p>Please enter your email and password</p>
           </div>
           <form method="POST" action="{{ route('login') }}" id="login">
                @csrf
                <div class="form-group">
                 
                    <input id="inputEmail" type="text" class="form-control @error('email') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter Username">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="form-group">
                     <input id="inputPassword" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                                            {{ __('Login') }}
                </button>

            </form>
      </div>
  </div>
</div>
</body>
@endsection
