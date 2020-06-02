@extends('layouts.app')

@section('content')

<div class="container">
    <ul class="list-group" id="list">
            
    </ul>
</div>

<script type="text/javascript">

    postCronjob();

    function postCronjob(){
        var start = new Date().getTime();
        $.ajax({
            type: "get",
            dataType: "json",
            url: "{{ route('chatwork_translate_v4_cronjob') }}",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                var end = new Date().getTime();
                var time = end - start;

                $result = '';
                data.debug.forEach(function(element){
                    $result += '<li class="list-group-item list-group-item-secondary">';
                    $result += element;
                    $result += '</li>';
                });
                $result += '<li class="list-group-item list-group-item-success">';
                $result += time;
                $result += '</li>';
                $('#list').append($result);

                if( $('.list-group-item').length > 10 ){
                    $('.list-group-item').first().remove();
                }
                
                if(time > 2000){
                    postCronjob();
                }else{
                    setTimeout(function(){
                        postCronjob();
                    }, 2000);
                }                
            },
            error: function(data) {
                setTimeout(function(){
                    location.reload();
                }, 2000);
            }
        });
    }

</script>

@endsection