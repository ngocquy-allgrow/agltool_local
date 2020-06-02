@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if( Auth::user()->roles->first()->name =='block' )
                <div class="card">
                    <div class="card-header">Status</div>
                    <div class="card-body">
                            Hello {{ Auth::user()->name }}</br>
                            Your account has been temporarily locked. Please confirm with admin
                    </div>
                </div>
            @else
                @if( Auth::user()->roles->first()->name =='admin' )
                <div class="card">
                    <div class="card-header">Manager list</div>
                    <div class="card-body">
                        <ul>
                            <li class="card-body-item">
                                <a href="{{ url('manager_users') }}"><img src="https://cdn2.iconfinder.com/data/icons/user-management/512/profile_settings-512.png"></a>
                                <a href="{{ url('manager_users') }}"><p>Manage user</p></a>
                            </li>
                            <li class="card-body-item">
                                <a href="{{ url('manager_tools') }}"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQJHa59ZKMm2VfIDNWYeMA3H6ew4h-CmM6CxqEQt4VcmzTZa1n&s"></a>
                                <a href="{{ url('manager_tools') }}"><p>Manage tool</p></a>
                            </li>
                            <li class="card-body-item">
                                <a href="{{ route('manager_slack') }}"><img src="https://user-images.githubusercontent.com/819186/51553744-4130b580-1e7c-11e9-889e-486937b69475.png"></a>
                                <a href="{{ route('manager_slack') }}"><p>Manage slack translation</p></a>
                            </li>

                            <li class="card-body-item">
                                <a href="{{ route('chatwork_admin_index') }}"><img src="https://apprecs.org/gp/images/app-icons/300/da/jp.ecstudio.chatworkandroid.jpg"></a>
                                <a href="{{ route('chatwork_admin_index') }}"><p>Manage chatwork translation</p></a>
                            </li>
                        </ul>
                    </div>
                </div>
                </br>
                <div class="card">
                    <div class="card-header">Tool list</div>
                    <div class="card-body">
                        <ul>
                            @foreach($tools as $tool_key => $tool)
                            <li class="card-body-item">
                                <a href="{{ route($tool->route) }}" ><img src="{{ asset('img/tools/'.$tool->image) }}"></a>
                                <a href="{{ route($tool->route) }}" ><p>{{$tool->name}}</p></a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @else
                </br>
                <div class="card">
                    <div class="card-header">Tool list</div>
                    <div class="card-body">
                        <ul>
                            @foreach($tools as $tool_key => $tool)
                                @if($tool->status == 1)
                                <li class="card-body-item">
                                    <a href="{{ route($tool->route) }}" ><img src="{{ asset('img/tools/'.$tool->image) }}"></a>
                                    <a href="{{ route($tool->route) }}" ><p>{{$tool->name}}</p></a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>    
</div>
@endsection
