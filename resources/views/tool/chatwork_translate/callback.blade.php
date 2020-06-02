@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    CHATWORK LIST GROUP
                    <a class="button preregister float-right" href="{{ route('chatwork_translate_logout') }}" target="" >&nbsp;Log out</a>
                    <span class="float-right" style="font-size: 0.7rem; line-height: 1.5rem;">{{ $chatWork_name }} </span>                    
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Room ID</th>
                                <th>Room avatar</th>
                                <th>Name</th>
                                <th>Language</th>
                                <th>Enable</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            <tr>
                                <td id="room_id">{{ $data['room_id'] }}</td>
                                <td class="room_icon"><img width="48" height="48" src="{{ $data['icon_path']}}"></td>
                                <td class="room_name"><a target="_blank" href="https://www.chatwork.com/#!rid{{ $data['room_id']}}">{{ $data['name'] }}</a></td>
                                <td id="lang" class="lang">
                                    <select class="form-control">
                                        <option value="ja-en-vi">Japanese -> English -> Viet nam</option>
                                        <option value="vi-en-ja">Viet nam -> English -> Japanese</option>
                                        <option value="vi-ja">Viet nam -> Japanese</option>
                                        <option value="vi-en">Viet nam -> English</option>
                                        <option value="en-ja">English -> Japanese</option>
                                        <option value="en-vi">English -> Viet Nam</option>
                                        <option value="ja-en">Japanese -> English</option>
                                        <option value="ja-vi">Japanese -> Viet nam</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="checkbox" name="radiobutton" class="activeButton" value="{{ $data['room_id'] }}" onclick="onClickSelect(this)">
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
    // reset
    $('.activeButton').prop('checked', false);
    $('.lang').removeAttr('disabled');
    $('tr').css('background', 'white');
    $('tr').css('color', '#000');

    var room_id;
    var lang;
    var bool;

    function delTrans() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '{{ url("/tool/chatwork_translate/deleteTrans") }}',
            data: {                    
            },
            success: function(data) {
               
            },
            error: function(data) {
                
            },
        });
    }


    var room_id_array = [];
    var lang_array = [];
    var active = false;

    function onClickSelect(elementSelect){
        var room_id = $(elementSelect).parent().parent().children("#room_id").text();
        var lang = $(elementSelect).parent().parent().children("#lang").children("select").val();

        if ($(elementSelect).prop("checked") == true) {    
            //delTrans();
            room_id_array.push(room_id);
            lang_array.push(lang);

            //style 
            $(elementSelect).parent().parent().children("#lang").children("select").attr('disabled', 'disabled');
            $(elementSelect).parent().parent().children("#lang").children("select").css('border', 'none');
            $(elementSelect).parent().parent().children("#lang").children("select").css('background', '#4CAF50');
            $(elementSelect).parent().parent().children("#lang").children("select").css('-webkit-appearance', 'none');
            $(elementSelect).parent().parent().children("#lang").children("select").css('-moz-appearance', 'none');

            $(elementSelect).parent().parent().css('background', '#4CAF50');
            $(elementSelect).parent().parent().css('color', '#fff');  

        } else if ($(elementSelect).prop("checked") == false) {
            room_id_array.splice($.inArray(room_id, room_id_array),1);
            lang_array.splice($.inArray(lang, lang_array),1);

            //style
            $(elementSelect).parent().parent().children("#lang").children("select").removeAttr('disabled');
            $(elementSelect).parent().parent().children("#lang").children("select").css('border', '1px solid black');
            $(elementSelect).parent().parent().children("#lang").children("select").css('background', '#fff');
            $(elementSelect).parent().parent().children("#lang").children("select").css('-webkit-appearance', 'menulist');
            $(elementSelect).parent().parent().children("#lang").children("select").css('-moz-appearance', 'menulist');
            
            $(elementSelect).parent().parent().css('background', 'white');
            $(elementSelect).parent().parent().css('color', '#000');
        }

        if(room_id_array.length !== 0 && !active){
            getMessage(room_id_array, lang_array);
            active = true;
        }
    }

    
    function getMessage() {

        if(room_id_array.length !== 0){
                connectAPI();
            
        }else{
            active = false;
        }  
    }

    var index = 0;

    function connectAPI(){
        setTimeout(function () {
            if( index >= room_id_array.length){
                index = 0;
                getMessage(room_id_array, lang_array);
            }else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ url("/tool/chatwork_translate/translate") }}',
                    data: {
                        room_id: room_id_array[index],
                        lang: lang_array[index]
                    },
                    success: function(data) {
                        index++;
                        if (data == 'true') {
                            
                            
                        } else {
                            
                        }
                        connectAPI();
                    },
                    error: function(data) {
                        index++;
                        connectAPI();
                    },
                });
            }
         }, 100)
    }
</script>

@endsection