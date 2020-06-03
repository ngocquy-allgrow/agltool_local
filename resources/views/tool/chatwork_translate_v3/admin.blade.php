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
                
                <!-- // add new message -->
                @foreach($members as $key => $member)
                <form action="{{route('chatwork_post_message')}}" id="{{ json_decode($member)->user_id }}" class="form-message" method="POST">
                    @csrf 
                    <table class="table">
                        <tr>
                            <th width="24%" class="text-center">Account Name</th>
                            <th class="text-center">Input Message</th>
                            <th class="text-center" width="26%">Action</th>
                        </tr>
                        <tr>
                                <td>
                                
                                    <p class="text-lang text-center m-0 text-danger">( {{ json_decode($member)->lang }} )</p>
                                    <select name="room_id" class="form-control form-control-lg upload-message" onchange="changeAccount(this)">
                                        @foreach($members as $k => $mem)
                                            <option @if($k == 0) selected @endif value="{{ json_decode($mem)->key_lang }}" name-lang="{{ json_decode($mem)->lang }}" data-id="{{ json_decode($mem)->user_id }}">{{ json_decode($mem)->username }}</option>
                                        @endforeach
                                    </select>
                                </td> 

                                <td class="text-center">
                                    <textarea class="d-inline md-textarea form-control input-mesage" name="body" rows="8"></textarea>

                                    <input type="hidden" name="token" value="{{ json_decode($member)->token }}">

                                    <input type="hidden" name="room_id" value="{{ json_decode($member)->room_id }}">

                                </td>
                                <td>
                                    <button class="btn btn-success upload" onclick="upload('{{ json_decode($member)->user_id }}')">Upload</button>

                                    <button class="btn btn-primary auto-translate buttonload" type="button" onclick="autoTranslateAdd(this,{{json_decode($member)->user_id}})">Auto Translate </button>

                                </td>

                        </tr>
                    </table>
                </form>
                @endforeach

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
                                    <input type="hidden" class="form-key-lang" value="{{ json_decode($member)->key_lang }}">
            				        <td>{{$data->account->name}}<br> <span style="color:#f92727">( {{ isset(json_decode($member)->lang) ? json_decode($member)->lang : 'NULL' }} )</span></td>
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
       
        $(document).ready(function() {
            var form_add_list = $('.form-message');
            form_add_list.each(function( index, el ) {
                if (index > 0) {
                    $(el).css('display', 'none');
                }
            });

        });

        function autoTranslateAdd(self, id_user) {
            
            var body = $('#'+id_user).find('textarea').val();
            var lang_name = $('#'+id_user).find('select').find("option:selected").val();
            var form = $("#"+ form + ' .input-mesage').val();
            if (body.trim() == '') {
                alert('Please enter a message!');
                return;
            }
            $(self).html('<i class="fa fa-spinner fa-spin"></i>');
            $('#'+id_user).find('.upload').attr('disabled', true);
           
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('chatwork_admin_translate_message') }}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'lang': lang_name,
                    'body': body
                },
                
                success: function(data) {
                    $('#'+ id_user).find('textarea').val(`${data.data}`);
                    
                },

                error: function(err) {
                    alert('Auto translation failed');
                },

                complete: function() {
                    $('#'+id_user).find('.upload').attr('disabled', false);
                    $(self).html('Auto Translate ');
                }
            });
            
        }

        function autoTranslate(id) {
            var body = $('#'+ id).find('textarea').val();
            var lang_name = $('#'+ id).parent().parent().find('.form-key-lang').val();
            $('#'+ id).parent().parent().find('button.translate').html('<i class="fa fa-spinner fa-spin"></i>');
            
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('chatwork_admin_translate_message') }}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'lang': lang_name,
                    'body': body
                },
                
                success: function(data) {
                    $('#'+ id).find('textarea').val(`${data.data}`);
                },

                error: function(err) {
                    alert('Auto translation failed');
                },

                complete: function() {
                    $('#'+ id).parent().parent().find('button.translate').html('Auto Translate');
                }
            });
            
        }

    	$("textarea").each(function(textarea) {
		    $(this).height( $(this)[0].scrollHeight );

		});

        function changeAccount(self) {
           var id_form = $(self).find("option:selected").attr('data-id');
           var k_option = $("#"+ id_form).find("option[data-id="+ id_form +"]").attr('data-id');

           var form_add_list = $('.form-message');

            form_add_list.each(function( i, el ) {
               var id_form_each = $(el).attr('id');
               
               if (id_form_each != id_form) {
                      
                    $(el).find("option").removeAttr('selected');
                    $(el).css('display', 'none'); 

               } else {
                   
                    $(el).css('display', 'block');
                    $(el).find("option[data-id="+ k_option +"]").attr('selected', true);
                    
               }
               
           }); 


            var lang_name = $("#"+ id_form).find("option:selected").attr('name-lang');
  

           if (lang_name != undefined) {
                $("#"+ id_form).find('select').prev().text(`( ${lang_name} )`)
           } else {
                $("#"+ id_form).find('select').prev().text('');
           } 
           
       }

        function upload(form){
            
            var form = $("#"+ form + ' .input-mesage').val();
            if (form.trim() == '') {
                alert('Please enter a message!');
                return;
            }

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