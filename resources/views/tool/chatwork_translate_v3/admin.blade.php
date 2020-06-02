@extends('layouts.app')



@section('content')



<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12 p-0">

            <div class="card">

                <div class="card-header" style="text-align: center;">

                    <a class="btn btn-secondary float-left" href="{{ url('/') }}">BACK</a>

                    <a href="{{ route('chatwork_admin_index') }}" style="font-size: 1.2rem;">CHATWORK ADMIN</a>

                    <br>

                    <br>

                	<select name="room_id" class="form-control form-control-lg" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">

                    	@foreach ($chatworkRooms as $chatworkRoom)

                    		<option @if($room_id == $chatworkRoom->room_id) selected @endif value="{{route('chatwork_admin_detail',['room_id' => $chatworkRoom->room_id])}}">{{ $chatworkRoom->room_id }} : {{ $chatworkRoom->name }}</option>

                    	@endforeach

                    </select>     

                </div>
                <div classc="card-header">
                <table class="table">
                    <tr>
                        <th width="24%" class="text-center">Account Name</th>
                        <th class="text-center">Input Message</th>
                        <th class="text-center" width="26%">Action</th>
                    </tr>
                    <tr>
                        <td>
                            <select name="room_id" class="form-control form-control-lg" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">

                                @foreach ($chatworkRooms as $chatworkRoom)

                                    <option @if($room_id == $chatworkRoom->room_id) selected @endif value="{{route('chatwork_admin_detail',['room_id' => $chatworkRoom->room_id])}}">{{ $chatworkRoom->room_id }} : {{ $chatworkRoom->name }}</option>

                                @endforeach

                            </select>
                        </td>
                        <td class="text-center">
                            <textarea class="d-inline md-textarea form-control" name="input_message" rows="8"></textarea>
                        </td>
                        <td>
                            <button class="btn btn-success edit" onclick="edit()">Upload</button>

                            <button class="btn btn-primary translate" onclick="autoTranslate()">Auto Translate</button>

                        </td>
                    </tr>
                </table>

                </div>
                <div class="card-body">

                	<table class="table">

                		<tr>

                			<th width="23%">Account Name</th>

                			<th>Body</th>

                            <th width="25%"></th>

                		</tr>

                		@foreach ($datas as $data)  

                		@if(isset($data->token))              		

                		<tr> 
                            @foreach($members as $member)
                                @if (json_decode($member)->username == $data->account->name)
                                    <input type="hidden" value="{{ json_decode($member)->key_lang }}">
            				        <td>{{$data->account->name}}<br> <span style="color:#f92727">( {{ json_decode($member)->lang }} )</span></td>
                                @endif
                            @endforeach
                			<td>

                				<form id="form-{{$data->message_id}}" action="" method="POST">

                				@csrf                				 

                				<textarea class="d-inline md-textarea form-control input-text" name="body"

                                @if(strpos($data->body, '[hr]') !== false)  @else style="border-color: red" @endif

                                >{{$data->body}}</textarea>

                				<input type="hidden" name="token" value="{{$data->token}}">

                				<input type="hidden" name="room_id" value="{{$room_id}}">

                				<input type="hidden" name="message_id" value="{{$data->message_id}}">                				

                				</form>                                                  				

                			</td>

                            <td>

                                <button class="btn btn-success edit" onclick="edit('form-{{$data->message_id}}')">Edit</button>

                                <button class="btn btn-danger delete" onclick="del('form-{{$data->message_id}}')">Delete</button>

                                <button class="btn btn-primary translate" onclick="autoTranslate('form-{{$data->message_id}}')">Auto Translate</button>

                            </td>

                		</tr> 

                		@endif               		

                		@endforeach

                	</table>                    

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">

    	$("textarea").each(function(textarea) {

		    $(this).height( $(this)[0].scrollHeight );

		});

        function autoTranslate(form) {
            var lang = $('#'+form).parent().parent().children().val();
            var text = $('#'+form +' textarea').val();
            var that = this;

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('chatwork_admin_translate_message') }}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'lang': lang,
                    'body': text
                },
                
                success: function(data) {
                    $('#'+form +' textarea').val(`${data.data}`);
                    setTimeout(() => {
                        that.edit(form);
                    }, 300);
                },

                error: function(err) {
                    alert('Auto translation failed');
                }
            });
        }


        function edit(form){

            var form = $("#"+form);
            
            form.attr('action', "{{route('chatwork_admin_edit_message')}}");

            form.submit();

        }



        function del(form){

            var r = confirm("Do you want to delete this message");

            if (r == true) {

                var form = $("#"+form);

                form.attr('action', "{{route('chatwork_admin_del_message')}}");

                form.submit();

            }

        }

    </script>    

</div>


@endsection