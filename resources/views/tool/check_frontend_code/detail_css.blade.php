@extends('layouts.app')
@section('content')
@if( Auth::user()->roles->first()->name =='admin' )
<div class="container">
   <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="text-align: center;">
                    <a class="btn btn-secondary float-left" href="{{ route('checkfrontendcode_index') }}">BACK</a>
                    <a style="font-size: 1.5rem;">CSS CHECK ( {{$infoSourcecode->name}} )</a>
                </div>
            </div>
        </div>
    </div>

    @foreach($result_css as $key => $value)     
        </br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" onclick="clickheader(this)" style="cursor: pointer;">
                        <a href="{{$value->result->cssvalidation->uri}}" target="_blank">{{$value->name}}</a>
                        ( {{$value->result->cssvalidation->result->errorcount}} errors, {{$value->result->cssvalidation->result->warningcount}} warnings )
                    </div>
                    @if( isset($value->result->cssvalidation->errors) || isset($value->result->cssvalidation->warnings) ) 
                        <table class="table" style="display:none">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">LINE</th>
                                    <th style="text-align: center;">STATUS</th>                                
                                    <th  style="width:85%">DESCRIPTION</th>
                                </tr>
                            </thead>                     
                            <tbody>
                                @if( isset($value->result->cssvalidation->errors) )                            
                                    @foreach($value->result->cssvalidation->errors as $result_key => $result_val)
                                      <tr>
                                        <td style="text-align: center;">
                                            Line: {{$result_val->line}}
                                        </td>
                                        <td style="text-align: center;">
                                            <button class="btn btn-danger btn-disable">
                                                Error
                                            </button>
                                        </td>                                
                                        <td>
                                            <p>{{$result_val->message}}</p>
                                            <pre><code style="word-break: break-all;">{{$result_val->context}}</code></pre>
                                        </td>                                
                                      </tr>
                                    @endforeach
                                @endif
                                @if( isset($value->result->cssvalidation->warnings) )
                                    @foreach($value->result->cssvalidation->warnings as $result_key => $result_val)
                                      <tr>
                                        <td style="text-align: center;">
                                            Line: {{$result_val->line}}
                                        </td>
                                        <td style="text-align: center;">
                                            <button class="btn btn-warning btn-disable">
                                                Warning
                                            </button>
                                        </td>                                
                                        <td>
                                            <p>{{$result_val->message}}</p>                                    
                                        </td>                                
                                      </tr>
                                    @endforeach
                                @endif
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
