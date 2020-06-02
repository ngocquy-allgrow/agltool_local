@extends('layouts.app')
@section('content')
@if( Auth::user()->roles->first()->name =='admin' )
<div class="container">
	 <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="text-align: center;">
                    <a class="btn btn-secondary float-left" href="{{ url('/') }}">BACK</a>
				    <a style="font-size: 1.5rem;">MANAGER TOOL</a>
				    <button id="btnRegister" type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">ADD NEW</button>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-chatwork_translate_v2">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
								<th>NAME</th>
								<th>IMAGE</th>
								<th>ROUTE</th>
								<th>STATUS</th>
								<th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tools as $tool_key => $tool)
                            	<tr id="tr_{{ $tool->id }}" class="@if( 1 ==  $tool->status ) success @else danger @endif">
	                            	<td class="text-center">{{$tool_key+1}}<input type="hidden" name="id" value="{{ $tool->id }}"></td>
				          			<td><input type="text" name="name" class="form-control" value="{{$tool->name}}"></td>
				          			<td class="text-center">
                                        <label for="file-input_{{ $tool->id }}">
                                            <img width="48" height="48" src="{{ asset('img/tools/'.$tool->image) }}">
                                        </label>
                                        <input id="file-input_{{ $tool->id }}" type="file" name="image" class="form-control" onchange="uploadImage(this)" style="display: none">
                                    </td>
				          			<td><input type="text" name="route" class="form-control" value="{{$tool->route}}"></td>
				          			<td class="text-center">
				          				<select name="status" class="form-control">
							                <option @if( 0 ==  $tool->status ) selected="selected" @endif value="0">Disable
							                </option>
							                <option @if( 1 ==  $tool->status ) selected="selected" @endif value="1">Enable
							                </option>
                                        </select>
				          			<td>
				          				<button type="button" class="btn btn-success" name="type" value="change" onclick="editTool(this)">Change</button>
				          				@if( $tool->status ==  0 )
				          				<button type="button" class="btn btn-danger" name="type" value="delete" onclick="deleteTool({{ $tool->id }})">Delete</button>
				          				@endif
				          			</td>
	                            </tr>   
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form method="POST" action="{{ route('add_tools') }}" enctype="multipart/form-data" >
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;
                </button>
                <h4 class="modal-title">Register new tool
                </h4>
              </div>
              <div class="modal-body">
                @csrf
                <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">Name
                  </label>
                  <div class="col-md-6">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}
                    </span>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label for="image" class="col-md-4 col-form-label text-md-right">Image
                  </label>
                  <div class="col-md-6">
                    <input type="file" name="image" class="form-control">
                    @if ($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}
                    </span>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label for="route" class="col-md-4 col-form-label text-md-right">Route
                  </label>
                  <div class="col-md-6">
                    <input type="text" name="route" class="form-control" value="{{ old('route') }}">
                    @if ($errors->has('route'))
                    <span class="text-danger">{{ $errors->first('route') }}
                    </span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Register
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close
                </button>
              </div>
            </form>
          </div>
        </div>
    </div>   
</div>
<script type="text/javascript">
	
	function uploadImage(el){
		var fd = new FormData();
        var files = $(el)[0].files[0];
        fd.append('id',$(el).parent().parent().children('td').children('input[name="id"]').val());
        fd.append('image',files);
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});	
		$.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('edit_tools_uploadfile') }}",
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
                location.reload(true);
            }
        });
	}

	function editTool(el) {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('edit_tools') }}",
            data: {
                id: $(el).parent().parent().children('td').children('input[name="id"]').val(),
                name: $(el).parent().parent().children('td').children('input[name="name"]').val(),
                route: $(el).parent().parent().children('td').children('input[name="route"]').val(),
                status: $(el).parent().parent().children('td').children('select[name="status"]').val(),
            },
            success: function(data) {
                location.reload(true);
            }
        });
    }

    function deleteTool(id) {
        var r = confirm("Do you want to delete this tool?");
        if (r == true) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ route('delete_tools') }}",
                data: {
                    id: id,
                },
                success: function(data) {
                    location.reload(true);
                }
            });
        }        
    }

    $(document).ready(function() {
        // modal
        @if(count($errors) > 0)
        $('#btnRegister').trigger('click');
        @endif
    });
</script>
@endif
@endsection
