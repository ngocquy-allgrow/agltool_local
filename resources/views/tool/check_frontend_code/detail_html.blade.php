@extends('layouts.app')
@section('content')
@if( Auth::user()->roles->first()->name =='admin' )
<div class="container">
   <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="text-align: center;">
                    <a class="btn btn-secondary float-left" href="{{ route('checkfrontendcode_index') }}">BACK</a>
                    <a style="font-size: 1.5rem;">HTML CHECK ( {{$infoSourcecode->name}} )</a>
                </div>
            </div>
        </div>
    </div>

    @foreach($result as $key => $value)
    </br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" onclick="clickheader(this)" style="cursor: pointer;">
                    <a href="{{$value->result->url}}" target="_blank">{{$value->name}}</a>
                </div>
                @if (count($value->result->messages)>0)
                    <table class="table" style="display:none">
                        <thead>
                            <tr>
                                <th style="text-align: center;">LINE</th>
                                <th style="text-align: center;">STATUS</th>                                
                                <th  style="width:85%">DESCRIPTION</th>
                            </tr>
                        </thead>                     
                        <tbody>
                            @foreach($value->result->messages as $msg_key => $msg_val)
                                @if( $msg_val->type =='error' )
                                    <tr>
                                        <td style="text-align: center;">
                                            @if( isset($msg_val->lastLine) ) Line:  {{$msg_val->lastLine}} @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <button class="btn btn-danger btn-disable">
                                                Error
                                            </button>
                                        </td>                                
                                        <td>
                                            <p>{{$msg_val->message}}</p>
                                            @if( isset($msg_val->extract) )
                                                <pre><code style="word-break: break-all;">{{$msg_val->extract}}</code></pre>
                                            @endif
                                        </td>                                
                                    </tr>
                                @endif
                                @if( $msg_val->type =='info' )
                                    <tr>
                                        <td style="text-align: center;">
                                            @if( isset($msg_val->lastLine) ) Line: {{$msg_val->lastLine}} @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <button class="btn btn-warning btn-disable">
                                                Warning
                                            </button>
                                        </td>                                
                                        <td>
                                            <p>{{$msg_val->message}}</p>
                                            @if( isset($msg_val->extract) )
                                                <pre><code style="word-break: break-all;">{{$msg_val->extract}}</code></pre>
                                            @endif
                                        </td>                                
                                    </tr>
                                @endif
                                @if( $msg_val->type =='non-document-error' )
                                    <tr>
                                        <td style="text-align: center;">
                                            @if( isset($msg_val->lastLine) ) Line: {{$msg_val->lastLine}} @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <button class="btn btn-warning btn-disable">
                                                Warning
                                            </button>
                                        </td>                                
                                        <td>
                                            <p>{{$msg_val->message}}</p>
                                            @if( isset($msg_val->extract) )
                                                <pre><code style="word-break: break-all;">{{$msg_val->extract}}</code></pre>
                                            @endif
                                        </td>                                
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>  
    @endforeach
    <script type="text/javascript">
        function clickheader(el){
            if ( $( el ).next('table').is( ":hidden" ) ) {
                $( el ).next('table').show( "slow" );
            } else {
                $( el ).next('table').hide();
            }
        }
    </script>
</div>
@endif
@endsection
