<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ explode('___', $title)[0] }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://phppot.com/wp-content/themes/solandra/papers_25220.css">
   
    <link rel="stylesheet" href="{{asset('css/report.css')}}">


    <!-- code mirror -->
    <link rel=stylesheet href="{{asset('codemirror/lib/codemirror.css')}}">
    <link rel=stylesheet href="https://codemirror.net/theme/monokai.css">
    <link rel="stylesheet" href="https://codemirror.net/addon/scroll/simplescrollbars.css">
    <script src="{{asset('codemirror/lib/codemirror.js')}}"></script>
    <script src="{{asset('codemirror/mode/xml/xml.js')}}"></script>
    <script src="https://codemirror.net/addon/scroll/simplescrollbars.js"></script>
    <script src="https://codemirror.net/mode/css/css.js"></script>

    <style type="text/css">
        .CodeMirror {
          height: 90vh;
        }

        .border-code-warning pre.CodeMirror-line > span{
            border: 1px solid yellow;
        }

        .border-code-error pre.CodeMirror-line > span{
            border: 1px solid red;
        }

        .border-code-error-animation pre.CodeMirror-line > span{
            border: 1px solid black;
            -webkit-animation: border-color-change-error 1s infinite;
            -moz-animation: border-color-change-error 1s infinite;
            -o-animation: border-color-change-error 1s infinite;
            -ms-animation: border-color-change-error 1s infinite;
            animation: border-color-change-error 1s infinite;
        }

        .border-code-warning-animation pre.CodeMirror-line > span{
            border: 1px solid black;
            -webkit-animation: border-color-change-warning 1s infinite;
            -moz-animation: border-color-change-warning 1s infinite;
            -o-animation: border-color-change-warning 1s infinite;
            -ms-animation: border-color-change-warning 1s infinite;
            animation: border-color-change-warning 1s infinite;
        }


        /*errorbox*/
         .error_active{
            border: 1px solid red!important;
         }

        .activefocus-error{
            border: 1px solid black;
            -webkit-animation: border-color-change-error 1s infinite;
            -moz-animation: border-color-change-error 1s infinite;
            -o-animation: border-color-change-error 1s infinite;
            -ms-animation: border-color-change-error 1s infinite;
            animation: border-color-change-error 1s infinite;
        }

        .activefocus-warning{
            border: 1px solid black;
            -webkit-animation: border-color-change-warning 1s infinite;
            -moz-animation: border-color-change-warning 1s infinite;
            -o-animation: border-color-change-warning 1s infinite;
            -ms-animation: border-color-change-warning 1s infinite;
            animation: border-color-change-warning 1s infinite;
        }

        @-webkit-keyframes border-color-change-error {
            0% { border-color: red; }
            50% { border-color: #2f2f2f; }
            100% { border-color: red; }
        }
        @-moz-keyframes border-color-change-error {
            0% { border-color: red; }
            50% { border-color: #2f2f2f; }
            100% { border-color: red; }
        }
        @-ms-keyframes border-color-change-error {
            0% { border-color: red; }
            50% { border-color: #2f2f2f; }
            100% { border-color: red; }
        }
        @-o-keyframes border-color-change-error {
            0% { border-color: red; }
            50% { border-color: #2f2f2f; }
            100% { border-color: red; }
        }
        @keyframes border-color-change-error {
            0% { border-color: red; }
            50% { border-color: #2f2f2f; }
            100% { border-color: red; }
        }

        @-webkit-keyframes border-color-change-warning {
            0% { border-color: yellow; }
            50% { border-color: #2f2f2f; }
            100% { border-color: yellow; }
        }
        @-moz-keyframes border-color-change-warning {
            0% { border-color: yellow; }
            50% { border-color: #2f2f2f; }
            100% { border-color: yellow; }
        }
        @-ms-keyframes border-color-change-warning {
            0% { border-color: yellow; }
            50% { border-color: #2f2f2f; }
            100% { border-color: yellow; }
        }
        @-o-keyframes border-color-change-warning {
            0% { border-color: yellow; }
            50% { border-color: #2f2f2f; }
            100% { border-color: yellow; }
        }
        @keyframes border-color-change-warning {
            0% { border-color: yellow; }
            50% { border-color: #2f2f2f; }
            100% { border-color: yellow; }
        }

        .loader-div {
        	position: fixed;
        	width: 100%;
        	height: 100%;
        	top: 0;
        	left: 0;
        	background: white;
        	z-index: 9999;
        }
        .loader {
		  border: 16px solid #f3f3f3;
		  border-radius: 50%;
		  border-top: 16px solid #3498db;
		  width: 120px;
		  height: 120px;
		  -webkit-animation: spin 2s linear infinite; /* Safari */
		  animation: spin 2s linear infinite;
		  margin: 0 auto;
    		margin-top: 40vh;
		}

		/* Safari */
		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}
    </style>
</head>

<body>
	<div class="loader-div">
		<div class="loader"></div>
	</div>
	<div class="contain">
		<nav class="navbar navbar-default navbar-fixed-top" style="margin-bottom: 60px;">
	        <div class="container">
	            <div class="navbar-header">
	                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	                    <span class="sr-only">Toggle navigation</span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                </button>
	                <a class="navbar-brand" href="{{Request::url()}}">{{ explode('___', $title)[0] }}</a>
	            </div>
	            <div id="navbar" class="navbar-collapse collapse">
	                <ul class="nav navbar-nav">
	                    @if ($check_html)
	                    <li class="dropdown">
	                        <a id="dropdown-html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	                            Html Validation <span class="caret"></span>
	                        </a>
	                        <ul class="dropdown-menu">
	                            <?php $i=0; ?>
	                            @foreach ($result as $data)
	                                <li><a href="#html{{ $i }}">
	                                    {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url  }}
	                                    @if(!empty($data->result))                                        
	                                        @if(is_array($data->result))
	                                        <span class="text-danger"><strong>( {{count($data->result)}} )</strong></span>
	                                        @endif
	                                    @else
	                                        <span class="text-success">( OK )</span>
	                                    @endif                                   
	                                </a></li>
	                                <?php $i++; ?>
	                            @endforeach
	                        </ul>
	                    </li>
	                    @endif
	                    @if ($check_css)
	                    <li class="dropdown">
	                        <a id="dropdown-css" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	                            Css Validation <span class="caret"></span>
	                        </a>
	                        <ul class="dropdown-menu">
	                            <?php $i=0; ?>
	                            @foreach ($result_css as $data)
	                                <li><a href="#css{{ $i }}">
	                                    {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url  }}
	                                    @if(!empty($data->result))                                        
	                                        @if(isset($data->result->result->errorcount) && $data->result->result->errorcount > 0)
	                                            <span class="text-danger">( Error: {{$data->result->result->errorcount}} )</span>
	                                        @endif

	                                        @if(isset($data->result->result->warningcount) && $data->result->result->warningcount > 0)
	                                            <span
	                                                class="text-warning">( Warning: {{$data->result->result->warningcount}} )</span>
	                                        @endif
	                                    @else
	                                        <span class="text-success">( OK )</span>
	                                    @endif                                      
	                                </a></li>
	                                <?php $i++; ?>
	                            @endforeach
	                        </ul>
	                    </li>
	                    @endif
	                    @if ($check_perfectpx)
	                    <li class="dropdown">
	                        <a id="dropdown-perfect_pixel" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	                            Perfect pixel <span class="caret"></span>
	                        </a>
	                        <ul class="dropdown-menu">
	                            <?php $i=0; ?>
	                            @foreach ($result_perfectpixel as $data)
	                            	@if(isset($data->url_screenshot))
	                                    <li><a href="#perfect_pixel{{ $i }}">
	                                        {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url  }}
	                                        @if(!empty($data->result))
	                                        @if(isset($data->result->rate))
	                                            <span class="text-primary">( {{ $data->result->rate }} )</span>
	                                        @endif
	                                        @endif
	                                    </a></li>	                                    
	                                @endif
	                                <?php $i++; ?>
	                            @endforeach
	                        </ul>
	                    </li>
	                    @endif                    
	                </ul>                              
	            </div>
	            <div class="tool">
	                <div class="form-check-inline">
	                  <label class="form-check-label">
	                    <input type="checkbox" class="form-check-input" id="resultdiff" checked="checked" onchange="getEventChoose()">Diff
	                  </label>
	                </div>
	                <div class="form-check-inline">
	                  <label class="form-check-label">
	                    <input type="checkbox" class="form-check-input" id="resultcompare" onchange="getEventChoose()">Compare
	                  </label>
	                </div>
	                <div class="form-group" id="opacity-tool">
	                    <label for="formControlRange">OPACITY:<span id="opacity_num"></span></label>
	                    <input class="opacity-range" type="range" class="form-control-range" id="formControlRange" min="0" max="100" value="50">
	                </div>               
	            </div> 
	            <!--/.nav-collapse -->
	        </div>
	    </nav>
        
	    <div class="container" style="padding-top: 70px">
	    	@if ($check_html)
	    	<h2>HTML CHECK</h2>
	    	<table class="table">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">URL</th>
			      <th scope="col">Result</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $i=0; ?>
                @foreach ($result as $data)
			    <tr
			    	@if(!empty($data->result))                                        
                            @if(is_array($data->result))
                            	class="bg-danger text-danger"
                            @endif
                    @else
                    	class="bg-success text-success"
                    @endif
			    >			    
			      <th scope="row">{{$i+1}}</th>
			      <td>
			      	<a href="#html{{ $i }}">
                        {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url  }}
                    </a>
                  </td>
			      <td>
			      		@if(!empty($data->result))                                        
                            @if(is_array($data->result))
                            <span><strong>( {{count($data->result)}} )</strong></span>
                            @endif
                        @else
                            <span>( OK )</span>
                        @endif
                    </td>
			    </tr>
			    <?php $i++; ?>	
			    @endforeach	    
			  </tbody>
			</table>
			@endif


			@if ($check_css)
	    	<h2>CSS CHECK</h2>
	    	<table class="table">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">URL</th>
			      <th scope="col">Result</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $i=0; ?>
                @foreach ($result_css as $data)
			    <tr
			    	@if(!empty($data->result))                                        
                        @if(isset($data->result->result->errorcount) && $data->result->result->errorcount > 0)
                            class="bg-danger text-danger"
                        @elseif(isset($data->result->result->warningcount) && $data->result->result->warningcount > 0)
                            class="bg-warning text-warning"
                        @endif	                        
                    @else
                        <span class="text-success">( OK )</span>
                    @endif
			    >			    
			      <th scope="row">{{$i+1}}</th>
			      <td>
			      	<a href="#css{{ $i }}">
                        {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url  }}
                    </a>
                  </td>
			      <td>
			      		@if(!empty($data->result))                                        
	                        @if(isset($data->result->result->errorcount) && $data->result->result->errorcount > 0)
	                            <span class="text-danger">( Error: {{$data->result->result->errorcount}} )</span>
	                        @endif

	                        @if(isset($data->result->result->warningcount) && $data->result->result->warningcount > 0)
	                            <span
	                                class="text-warning">( Warning: {{$data->result->result->warningcount}} )</span>
	                        @endif
	                    @else
	                        <span class="text-success">( OK )</span>
	                    @endif  
                    </td>
			    </tr>
			    <?php $i++; ?>	
			    @endforeach	    
			  </tbody>
			</table>
			@endif

			@if ($check_perfectpx)
	    	<h2>PERFECT PIXEL CHECK</h2>
	    	<table class="table">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">URL</th>
			      <th scope="col">Result</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $i=0; ?>
                @foreach ($result_perfectpixel as $data)
                @if(isset($data->url_screenshot))
			    <tr
			    @if(!empty($data->result))
                    @if(isset($data->result->rate))
                    	@if ( round($data->result->rate,0) > 90 )
                    		class="bg-success text-success"
                    	@elseif ( round($data->result->rate,0) > 80 )
                    		class="bg-warning text-warning"
                    	@else
                    		class="bg-danger text-danger"
                    	@endif
			     	@endif
                @endif 	
			    >			    
			      <th scope="row">{{$i+1}}</th>
			      <td>
			      	<a href="#perfect_pixel{{ $i }}">
                        {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url  }}
                    </a>
                  </td>
			      <td>
                        @if(!empty($data->result))
                        @if(isset($data->result->rate))
                            <span class="text-primary">( {{ $data->result->rate }} )</span>
                        @endif
                        @endif                                    
                    </td>
			    </tr>
			    @endif
			    <?php $i++; ?>	
			    @endforeach	    
			  </tbody>
			</table>
			@endif

	    </div>

	    @if ($check_html)
	        <?php $i=0; ?>
	        @foreach ($result as $data)
	        <div class="html target" id="html{{$i}}">        
	            <div class="jumbotron col-lg-4 col-md-4 col-sm-4"> 
	                    <div>
	                        <a target="_blank" href="{{$data->url}}" >{{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url   }}
	                            @if(!empty($data->result))                                        
	                                @if(is_array($data->result))
	                                <span class="text-danger"><strong>( {{count($data->result)}} )</strong></span>
	                                @endif
	                            @else
	                                <span class="text-success">( OK )</span>
	                            @endif
	                        </a>                    
	                        @if (!empty($data->result))                            
	                            <div style="width: 100%; height: 86.3vh; overflow-y: scroll; overflow-x: hidden;">
	                                <ol class="c-report">
	                                    @foreach ($data->result as $k => $r)
	                                        <?php $type = isset($r->type) ? $r->type : ''; ?>
	                                        <?php $subType = isset($r->subType) ? $r->subType : ''; ?>
	                                        <?php $message = isset($r->message) ? $r->message : ''; ?>
	                                        <li id="errorCode" 
	                                            block="html{{$i}}" 
	                                            line="@isset($r->lastLine){{$r->lastLine}}@endisset" 
	                                            total_type="@if(!empty($subType)){{$subType}}@else{{$type}}@endif"
	                                            >
	                                            <p class="c-report__title">
	                                                @if(!empty($subType))
	                                                    <strong class="@if($subType == 'error') text-danger @elseif($subType == 'warning') text-warning @endif">{{$k+1}}/ {{ucfirst($subType)}}  : </strong>
	                                                @else
	                                                    <strong class="@if($type == 'error') text-danger @elseif($type == 'warning') text-warning @endif">{{$k+1}}/ {{ucfirst($type)}} : </strong>
	                                                @endif
	                                                <span>{{$message}}</span>
	                                            </p>

	                                            <p class="c-report__location">
	                                                @isset($r->firstLine)
	                                                    From line {{$r->firstLine}}
	                                                @endisset
	                                                @isset($r->lastLine)
	                                                    From line {{$r->lastLine}}
	                                                @endisset
	                                                @isset($r->firstColumn)
	                                                    , column {{$r->firstColumn}}
	                                                @endisset
	                                                ;                                            

	                                                @isset($r->lastLine)
	                                                    To line {{$r->lastLine}}
	                                                    @isset($r->lastColumn)
	                                                        , column {{$r->lastColumn}}
	                                                    @endisset
	                                                @endisset
	                                            </p>

	                                            @isset($r->extract)
	                                                <p class="c-report__extract">
	                                                    <code>{{$r->extract}}</code>
	                                                </p>
	                                            @endisset
	                                        </li>
	                                    @endforeach
	                                </ol>
	                            </div>
	                        @endif
	                    </div>                              
	            </div>
	            <div id="" class="col-lg-8 col-md-8 col-sm-8">
	                <textarea id="html-block{{$i}}" style="width:100%">{{$data->htmlCode}}</textarea>               
	            </div>
	        </div>
	        <?php $i++; ?>   
	        @endforeach
	    @endif

	    @if ($check_css)
	        <?php $i=0; ?>
	        @foreach ($result_css as $data)
	        <div class="css target" id="css{{$i}}">
	            <!-- Main component for a primary marketing message or call to action -->
	            <div class="jumbotron  col-lg-4 col-md-4 col-sm-4">            
	                <div>
	                    <a target="_blank" href="{{$data->url}}">
	                        {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url   }} 
	                        @if(!empty($data->result))
	                            @if(isset($data->result->result->errorcount) && $data->result->result->errorcount > 0)
	                                <span class="text-danger">( Error: {{$data->result->result->errorcount}} )</span>
	                            @endif

	                            @if(isset($data->result->result->warningcount) && $data->result->result->warningcount > 0)
	                                <span
	                                    class="text-warning">( Warning: {{$data->result->result->warningcount}} )</span>
	                            @endif
	                        @else
	                            <span class="text-success">( OK )</span>
	                        @endif
	                    </a>
	                    <div style="width: 100%; height: 86.3vh; overflow-y: scroll; overflow-x: hidden;">
	                        <ol class="c-report">
	                            <?php $k=0; ?>
	                            @if(isset($data->result->errors))
	                                <?php
	                                foreach ($data->result->errors as $er):
	                                ?>
	                                <li id="errorCode"
	                                    block="css{{$i}}"
	                                    line="@if(isset($er->line)){{$er->line}}@endif"
	                                    total_type="error">
	                                    <p class="c-report__title">
	                                        <strong class="text-danger">{{ $k+1 }}/ Error  : </strong>
	                                        @if(isset($er->line))
	                                            <span class="c-report__line">Line {{$er->line}}</span>
	                                        @endif

	                                        @if(isset($er->message))
	                                            <span class="c-report__extract">{{$er->message}}</span>
	                                        @endif
	                                    </p>
	                                </li>
	                                <?php $k++; ?>
	                                <?php endforeach; ?>
	                            @endif

	                            @if(isset($data->result->warnings))
	                                <?php
	                                foreach ($data->result->warnings as $wr):
	                                ?>
	                                <li id="errorCode"
	                                    block="css{{$i}}"
	                                    line="@if(isset($wr->line)){{$wr->line}}@endif"
	                                    total_type="warning">
	                                    <p class="c-report__title">
	                                        <strong class="text-warning">{{ $k+1 }} / Warning  : </strong>
	                                        @if(isset($wr->line))
	                                            <span>Line {{$wr->line}}</span>
	                                        @endif
	                                        @if(isset($wr->message))
	                                            <span>{{$wr->message}}</span>
	                                        @endif
	                                    </p>
	                                </li>
	                                <?php $k++; ?>
	                                <?php endforeach; ?>
	                            @endif
	                        </ol>
	                    </div>
	                </div>
	            </div>
	            <div id="" class="col-lg-8 col-md-8 col-sm-8">
	                <textarea id="css-block{{$i}}" style="width:100%">{{$data->cssCode}}</textarea>               
	            </div>
	        </div>
	        <?php $i++; ?>
	        @endforeach
	    @endif

	    @if ($check_perfectpx)
	        <?php $i=0; ?>
	        @foreach ($result_perfectpixel as $data)
	            <div class="perfect_pixel target" id="perfect_pixel{{$i}}">             
	                <div class="jumbotron">
	                	@if(!empty($data->result))
	                    <a target="_blank" href="{{$data->url}}">
	                        {{  isset(explode($title.'/', $data->url)[1])?explode($title.'/', $data->url)[1]:$data->url  }}
	                        @if(isset($data->result->rate))                        
	                            @if ( $data->result->rate > 90 )
	                                <span class="text-success">( {{ $data->result->rate }} )</span>
	                            @elseif ( $data->result->rate > 80 )
	                                <span class="text-warning">( {{ $data->result->rate }} )</span>
	                            @else
	                                <span class="text-danger">( {{ $data->result->rate }} )</span>
	                            @endif                        
	                        @endif
	                    </a>
	                    @endif                          
	                    <p>	                        
                            <a class="c-report__extract diff-image">
                                    <img id="image-original" src="@isset($data->url_screenshot){{$data->url_screenshot}}@endisset" alt="">
                                    <img class="diff" src="@isset($data->url_design){{$data->url_design}}@endisset" alt="">
                            </a>
	                        @if(isset($data->result->result))
	                            <a class="compare-image" href="{{$data->result->result}}"
	                                              target="_blank" class="c-report__extract ">
	                                    <img class="img-fluid" src="{{$data->result->result}}" alt="">
	                            </a>
	                        @endif
	                    </p>
	                </div>
	            </div>
	            <?php $i++; ?>	        
	        @endforeach
	    @endif
	</div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="{{asset('js/report.js')}}"></script>
    @if ($check_html)
        <script id="scripthtml">
            <?php $i=0; ?>
            @foreach ($result as $data)
                    var editor_html{{$i}} = CodeMirror.fromTextArea(document.getElementById("html-block{{$i}}"), {
                        lineNumbers: true,
                        mode: "text/html",
                        matchBrackets: true,
                        theme : "monokai",
                        scrollbarStyle: "simple",
                        lineWrapping: true,
                        readOnly: true,
                    });

                   $(document).on('click', '#errorCode[block="html{{$i}}"]', function (event) {
                        event.preventDefault();
                        var lineNum = $.attr(this, 'line');                       
                        jumpToLine(editor_html{{$i}},lineNum);

                        $(".CodeMirror-code .border-code-error").removeClass("border-code-error-animation");
                        $(".CodeMirror-code .border-code-warning").removeClass("border-code-warning-animation");

                        var total_type = $.attr(this, 'total_type');
                        if(total_type == "error"){
                            editor_html{{$i}}.addLineClass(parseInt(lineNum)-1,"","border-code-error-animation"); 
                        }
                        if(total_type == "warning"){
                            editor_html{{$i}}.addLineClass(parseInt(lineNum)-1,"","border-code-warning-animation"); 
                        }
                    });

                    @if (!empty($data->result))                
                        @foreach ($data->result as $r)
                        <?php $type = isset($r->type) ? $r->type : ''; ?>
                        <?php $subType = isset($r->subType) ? $r->subType : ''; ?>
                        <?php $message = isset($r->message) ? $r->message : ''; ?>
                            @isset($r->lastLine)
                                var lineNum = {{$r->lastLine }} ;                            
                                @if(!empty($subType))
                                @if($subType == 'error')
                                        editor_html{{$i}}.addLineClass( lineNum-1 ,"","border-code-error");                                               
                                    @elseif($subType == 'warning')
                                        editor_html{{$i}}.addLineClass( lineNum-1 ,"","border-code-warning"); 
                                    @endif                                                
                                @else
                                    @if($type == 'error') 
                                        editor_html{{$i}}.addLineClass( lineNum-1 ,"","border-code-error");
                                    @elseif($type == 'warning')
                                        editor_html{{$i}}.addLineClass( lineNum-1 ,"","border-code-warning"); 
                                    @endif 
                                @endif
                            @endisset 
                        @endforeach                
                    @endif
                <?php $i++; ?>
            @endforeach
        </script>
    @endif

    @if ($check_css)
        <script id="scriptcss">
            <?php $i=0; ?>
            @foreach ($result_css as $data)                
                var editor_css{{$i}} = CodeMirror.fromTextArea(document.getElementById("css-block{{$i}}"), {
                    lineNumbers: true,
                    mode: "text/css",
                    matchBrackets: true,
                    theme : "monokai",
                    scrollbarStyle: "simple",
                    lineWrapping: true,
                    readOnly: true,
                });

                 $(document).on('click', '#errorCode[block="css{{$i}}"]', function (event) {
                    event.preventDefault();
                    var lineNum = $.attr(this, 'line');
                    jumpToLine(editor_css{{$i}},lineNum);

                    $(".CodeMirror-code .border-code-error").removeClass("border-code-error-animation");
                    $(".CodeMirror-code .border-code-warning").removeClass("border-code-warning-animation");

                    var total_type = $.attr(this, 'total_type');
                    if(total_type == "error"){
                        editor_css{{$i}}.addLineClass(parseInt(lineNum)-1,"","border-code-error-animation"); 
                    }
                    if(total_type == "warning"){
                        editor_css{{$i}}.addLineClass(parseInt(lineNum)-1,"","border-code-warning-animation"); 
                    }
                });
                
                @if (!empty($data->result))
                    @if(isset($data->result->errors))
                        @foreach($data->result->errors as $er)
                            var lineNum = {{$er->line}};
                            editor_css{{$i}}.addLineClass( lineNum-1 ,"","border-code-error");
                        @endforeach
                    @endif
                    @if(isset($data->result->warnings))
                        @foreach($data->result->warnings as $wn)
                            var lineNum = {{$wn->line}};
                            editor_css{{$i}}.addLineClass( lineNum-1 ,"","border-code-warning");
                        @endforeach
                    @endif
                @endif
                <?php $i++; ?>
            @endforeach
        </script>
    @endif

    <script type="text/javascript">
        function jumpToLine(cd,i) {
            cd.scrollIntoView({line:i, char:0}, 300);
        }       

        $(document).on('click', '#errorCode', function (event) {  
            if ( $(".c-report li").hasClass("activefocus-error") ) $(".c-report li").removeClass("activefocus-error");
            if ( $(".c-report li").hasClass("activefocus-warning") ) $(".c-report li").removeClass("activefocus-warning");

            var total_type = $.attr(this, 'total_type');
            $(this).toggleClass('activefocus-'+total_type);
        });

        window.onload = function(e){ 
		    $(".loader-div").hide();
		}
    </script>
</body>

</html>