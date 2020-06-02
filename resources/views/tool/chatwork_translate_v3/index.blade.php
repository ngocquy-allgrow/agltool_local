@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="text-align: center;">
                    <a class="btn btn-secondary float-left" href="{{ url('/') }}">BACK</a>
                    <a style="font-size: 1.2rem;">CHATWORK REGISTER</a>                    
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('chatwork_translate_v3_register')}}">
                    	@csrf
						<div class="form-group">
							<label for="token">Token:</label>
							<input type="token" class="form-control" id="token" name="token">
							@if ($errors->has('token'))
		                    	<span class="text-danger">{{ $errors->first('token') }}</span>
		                    @endif
						</div>
						<div>
							<label for="href">Get token : </label>
							<a target="_blank" href="https://www.chatwork.com/service/packages/chatwork/subpackages/api/token.php" id="href">Click here to get token</a>	
						</div>										  
						<button type="submit" class="btn btn-primary">Register</button>
					</form>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection