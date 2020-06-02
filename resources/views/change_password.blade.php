@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">            
            <div class="card card-outline-secondary">
                    <div class="card-header">
                        <h3 class="mb-0">Change Password</h3>
                    </div>
                    <div class="card-body">
                        <form class="form" role="form" autocomplete="off" action="{{route('changePasswordFunc')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="inputPasswordNew">New Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="inputPasswordNewVerify">Confirm new password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            <div class="form-group">
                                <a class="btn btn-secondary btn-lg float-left" href="{{ url('/') }}">Back</a>
                                <button type="submit" class="btn btn-success btn-lg float-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>    
</div>
@endsection
