@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(Request::get('error'))
                <div class="alert alert-danger">
                  <strong>Error :</strong> {{ Request::get('error') }}
                </div>
            @endif

            @if(Request::get('notification'))
                <div class="alert alert-success">
                  <strong>Success!</strong> {{ Request::get('notification') }}
                </div>
            @endif

            @if(Request::get('warning'))
                <div class="alert alert-warning">
                  <strong>Warning :</strong> {{ Request::get('warning') }}
                </div>
            @endif
            
            <div class="card">                
                <div class="card-header" style="text-align: center;">
                    <a class="btn btn-secondary float-left" href="{{ url('/') }}">BACK</a>
                    <a style="font-size: 1.2rem;">SLACK TEAM</a>
                    <a class="btn btn-primary float-right" href="{{ env('SLACK_SHARABLE_URL') }}" target="" >&nbsp;Add new team</a>                    
                </div>
                <div class="card-body" style="padding: 0">
                    <table class="table table-chatwork_translate_v2">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #ccc">Team ID</th>
                                <th style="border: 1px solid #ccc">Team Avatar</th>
                                <th style="border: 1px solid #ccc">Team Name</th>
                                <th style="border: 1px solid #ccc">Setting language</th>
                                <th style="border: 1px solid #ccc"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            <tr id="{{ $data['team']->id }}" class="team">
                                <td id="room_id" style="border: 1px solid #ccc">{{ $data['team']->id }}</td>
                                <td class="room_icon" style="border: 1px solid #ccc; text-align: center;"><img width="48" height="48" src="{{ $data['team']->icon->image_34 }}"></td>
                                <td class="room_name" style="border: 1px solid #ccc"><a target="_blank" href="https://{{ $data['team']->domain }}.slack.com">{{ $data['team']->name }}</a></td>
                                <td id="lang" class="lang" style="padding: 0; border: 1px solid #ccc">
                                    <ul style="padding: 0; margin: 0">
                                        @foreach($data['groups'] as $group_id => $value)
                                        <li style="border-bottom: 1px solid #ccc; list-style: none; padding: 3%;">
                                            <a target="_blank" href="https://app.slack.com/client/{{ $data['team']->id }}/{{ $group_id }}/details/apps">
                                                {{$data['conversations_info'][$group_id]->name}}
                                            </a>
                                            <form method="GET" action="{{route('slack_translate_v1_edit_lang')}}">
                                                <input type="checkbox" name="checkbox1" id="{{ $data['team']->id}}_{{ $group_id }}_en" value="en" @if (in_array('en',$value)) checked="checked" @endif> 
                                                <label for="{{ $data['team']->id}}_{{ $group_id }}_en" style="font-weight: normal;">English</label> 
                                                <input type="checkbox" name="checkbox2" id="{{ $data['team']->id}}_{{ $group_id }}_ja" value="ja" @if (in_array('ja',$value)) checked="checked" @endif> 
                                                <label for="{{ $data['team']->id}}_{{ $group_id }}_ja" style="font-weight: normal;">Japanese </label> 
                                                <input type="checkbox" name="checkbox3" id="{{ $data['team']->id}}_{{ $group_id }}_vi" value="vi" @if (in_array('vi',$value)) checked="checked" @endif> 
                                                <label for="{{ $data['team']->id}}_{{ $group_id }}_vi" style="font-weight: normal;">Viet Nam </label> 
                                                <input type="hidden" name="team_id" value="{{ $data['team']->id }}">
                                                <input type="hidden" name="channel_id" value="{{ $group_id }}">
                                                <button class="btn btn-outline-primary btn-sm float-right" type="submit" onclick="edit_click(this)">
                                                  <span class="spinner-border spinner-border-sm" style="display: none"></span>
                                                  <span>Edit</span>
                                                </button>
                                            </form>
                                        </li>
                                        @endforeach     
                                    </ul>
                                </td>
                                <td style="border: 1px solid #ccc; text-align: center;">
                                    <form method="GET" action="{{route('slack_translate_v1_delete')}}">
                                        <input type="hidden" name="team_id" value="{{ $data['team']->id }}">
                                        <button class="btn btn-outline-danger btn-sm" type="submit" onclick="edit_click(this)" style="color:red">
                                          <span class="spinner-border spinner-border-sm" style="display: none"></span>
                                          <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
</div>
<script type="text/javascript">
    function edit_click(el){
        var child = el.children;
        child[0].style.display = "inline-block";
        child[1].style.display = "none";
    }
</script>

@endsection