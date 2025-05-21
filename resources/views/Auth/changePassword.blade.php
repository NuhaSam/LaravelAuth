<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
@if(session('error'))
  <div class="text red-500" style="color:red">{{ session('error') }}</div>
  @endif 
  Hello {{Auth::user()->name ,}}
  <form action="{{route('change-password2')}}" method="post">
    @csrf
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Current Password</label>
      <input type="password" name="current_password" class="form-control" id="exampleInputPassword1">
      @error('current_password')
      <p> {{$message}}
        @enderror
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">New Password</label>
      <input type="password" name="password" class="form-control" id="exampleInputPassword1">
      @error('new_password')
      <p> {{$message}}
        @enderror
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Confirm New Password</label>
      <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword1">
      @error('password_confirmation')
      <p> {{$message}}
        @enderror
    </div>
    <div class="mb-3">
      <input type="submit" name="save" value="Save">
    </div>
</body>
</form>

</html>