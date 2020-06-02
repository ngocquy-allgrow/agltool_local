@extends('layouts.app')

@section('content')

<script type="text/javascript">

    postCronjob();

    function postCronjob(){
        var start = new Date().getTime();
        $.ajax({
            type: "get",
            dataType: "json",
            url: "{{ route('chatwork_translate_v5_cronjob') }}",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                var end = new Date().getTime();
                var time = end - start;

                //console
                console.log('time : '+time);
                data.debug.forEach(function(element){
                    var element = element.replace("[code]", "");
                    var element = element.replace("[/code]", "");
                    console.log(element);
                });
                
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