@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-8 offset-md-2">

    <div class="card">
      <div class="card-header">
        <h4>
          <i class="glyphicon glyphicon-edit"></i> 编辑个人资料
        </h4>
      </div>

      <div class="card-body">

        <form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
          <input type="hidden" name="_method" value="PUT">
          <!-- 等价于 {{ method_field('put') }} -->
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <!-- 等价于 {{ csrf_field() }} -->

          <!-- 表单验证错误信息提示 -->
          @include('shared._error')

          <div class="form-group">
            <label for="name-field">用户名</label>
            <input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $user->name) }}" />
          </div>
          <div class="form-group">
            <label for="email-field">邮 箱</label>
            <input class="form-control" type="text" name="email" id="email-field" value="{{ old('email', $user->email) }}" readonly />
          </div>
          <!-- 个人简介 -->
          <div class="form-group">
            <label for="introduction-field">个人简介</label>
            <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction', $user->introduction) }}</textarea>
          </div>
          <!-- 头像 -->
          <div class="form-group mb-4">
            <label for="" class="avatar-label">用户头像</label>
            <input type="file" name="avatar" class="form-control-file">
            <br>
            @if($user->avatar)
            <br>
            <img class="thumbnail img-responsive" src="{{ $user->avatar }}" width="200" />
            @else
            <img class="thumbnail img-responsive" src="https://s2.ax1x.com/2019/05/05/E04Mb6.jpg" width="200" />
            @endif
          </div>
          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">保存</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
