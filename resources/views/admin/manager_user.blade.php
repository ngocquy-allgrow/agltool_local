@extends('layouts.app')
@section('content')
@if( Auth::user()->roles->first()->name =='admin' )
<div class="container">        
  <div class="card-header" style="text-align: center; text-transform: none; font-weight: normal;">
    <a class="btn btn-secondary float-left" href="{{ url('/') }}">BACK
    </a>
    <a style="font-size: 1.5rem;">MANAGER USER</a>
    <button id="btnRegister" type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">ADD NEW
    </button>
  </h2>
  <br><br>
  <ul class="nav nav-tabs" id="myTab"  role="tablist">
    @foreach($users as $key => $user)
    <li class="nav-item">
      <a data-toggle="tab" href="#{{$key}}" role="tab" aria-controls="{{$key}}" >{{$key}} ({{ count($user) }})
      </a>
    </li>
    @endforeach
  </ul>
  
  <div class="tab-content" id="myTabContent" style="background: #fff; border-bottom: 1px solid #dee2e6; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6">
    @foreach($users as $key => $user)
    <div id="{{$key}}" class="tab-pane fade">
      <table class="table table-hover" style="text-align: left; word-wrap: break-word;">
        <thead>
          <th>ID
          </th>
          <th>NAME
          </th>
          <th>EMAIL
          </th>
          <th>PASSWORD
          </th>
          <th>PERMISSION
          </th>
          <th>
          </th>
        </thead>
        <tbody>
          @foreach($user as $m_user_key =>$m_user)                                        
          <tr>
            <td>{{$m_user_key+1}}
            </td>
            <td>{{$m_user->name}}
            </td>
            <td style="color: #4590b8">{{$m_user->email}}
            </td>
            <td style="color: red; max-width: 300px">{{$m_user->remember_password}}
            </td>
            <td>
              <select class="form-control" onchange="editUser({{$m_user->id}}, this.value)">
                @foreach($roles as $role)
                <option @if( $key ==  $role->name ) selected="selected" @endif value="{{$role->name}}">{{$role->name}}
                </option>
                @endforeach
              </select>
            </td>
            <td>                
                @if( $key ==  'block' )
                <button type="button" class="btn btn-danger" onclick="deleteUser({{$m_user->id}})">Delete</button>
                @else
                <button type="button" class="btn btn-link" onclick="resetPasswordUser({{$m_user->id}})">Reset Password</button>
                @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endforeach
  </div>
  <br>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST" action="{{ route('add_users') }}">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;
            </button>
            <h4 class="modal-title">Register new user
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
              <label for="email" class="col-md-4 col-form-label text-md-right">Email
              </label>
              <div class="col-md-6">
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}
                </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label for="role_name" class="col-md-4 col-form-label text-md-right">Permission
              </label>
              <div class="col-md-6">
                <select name="role_name" class="form-control">
                  @foreach($roles as $role)
                  <option @if( old('role_name') ==  $role->name ) selected="selected" @endif value="{{$role->name}}">{{$role->name}}
                  </option>
                  @endforeach
                </select>
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
    function editUser(id, value) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('edit_users') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
                role_name: value
            },
            success: function(data) {
                location.reload(true);
            }
        });
    }
    function deleteUser(id) {
        var r = confirm("Do you want to delete this user?");
        if (r == true) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ route('delete_users') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                success: function(data) {
                    location.reload(true);
                }
            });
        }        
    }
    function resetPasswordUser(id) {
        var r = confirm("Do you want to reset password this user?");
        if (r == true) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ route('reset_password_users') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                success: function(data) {
                    location.reload(true);
                }
            });
        }
    }
    $(document).ready(function() {
        $('a[data-toggle="tab"]').on('click', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        // modal
        @if(count($errors) > 0)
        $('#btnRegister').trigger('click');
        @endif
    });
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab == null) {
        $('.nav-tabs a:first').tab('show');
    } else {
        $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
    }
</script>
@endif
@endsection
