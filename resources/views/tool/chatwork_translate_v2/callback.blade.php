@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="text-align: center;">
                    <a class="btn btn-secondary float-left" href="{{ url('/') }}">BACK</a>
                    <a style="font-size: 1.2rem;">CHATWORK LIST GROUP ( {{ $chatWork_name }} )</a>
                    <a class="btn btn-primary float-right" href="{{ route('chatwork_translate_v2_logout') }}" target="" >&nbsp;Log out</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-chatwork_translate_v2">
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
                            <tr id="tr_{{ $data['room_id'] }}" class="danger">
                                <td id="room_id">{{ $data['room_id'] }}</td>
                                <td class="room_icon"><img width="48" height="48" src="{{ $data['icon_path']}}"></td>
                                <td class="room_name"><a target="_blank" href="https://www.chatwork.com/#!rid{{ $data['room_id']}}">{{ $data['name'] }}</a></td>
                                <td id="lang" class="lang">
                                    <select class="form-control" onchange="onChangeLang(this)">
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
                                <td id="enable">
                                    <input type="checkbox" name="radiobutton" class="activeButton" value="{{ $data['room_id'] }}" onclick="onClickSelect()">
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
    initPage();

    function onClickSelect(){
        updateRoom();
    }
    function onChangeLang(el){
        var checkbox = $(el).parent().parent().children("#enable").children("input").prop('checked');
        if(checkbox){
            updateRoom();
        }        
    }    

    function updateRoom(){
        var room_array = [];        
        $('table > tbody  > tr').each(function(i, el) {
            if ($(el).children("#enable").children("input").prop("checked") == true) {
                var room_object = {};
                var room_id = $(el).children("#room_id").text();
                var lang = $(el).children("#lang").children("select").val();
                room_object.room_id = room_id;
                room_object.lang = lang;
                room_array.push(room_object);
            }
        });        
        var myJSON = JSON.stringify(room_array);

        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('chatwork_translate_v2_updateRoom') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                room_id_array: myJSON,
                account_id: "{{ $chatworkAccount['account_id'] }}"
            },
            success: function(data) {
                location.reload(true);
            }
        });
    }

    function initPage(){
        var text = "{{ $chatworkAccount['room_id_array'] }}";
        if(text != ""){
            text = text.replace(/&quot;/g,'"');
            var obj = JSON.parse(text);
            obj.forEach(function (data) {
                $("#tr_"+data.room_id).removeClass("danger");
                $("#tr_"+data.room_id).addClass("success");
                $("#tr_"+data.room_id+" #enable input").attr('checked','checked');
                $("#tr_"+data.room_id+" #lang select option[value="+data.lang+"]").attr('selected','selected');
            });
        } 
    }

</script>

@endsection