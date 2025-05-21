<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<header class="d-flex justify-content-center py-3">
      <ul class="nav nav-pills">
        <li class="nav-item"><a href="#" class="nav-link active" aria-current="page">Home</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Features</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Pricing</a></li>
        <li class="nav-item"><a href="#" class="nav-link">FAQs</a></li>
        <li class="nav-item"><a href="#" class="nav-link">About</a></li>
      </ul>
    </header>

    @if(session('success'))
    <p> {{ session('success') }}</p>
    @endif
    Hello {{ Auth::user()->name }},

    <form action="{{route('change-password')}}" method="post">
        @csrf
        <input type="submit" name="change-password" value="Change Password">
    </form>
<form action="{{route('logout')}}" method="post">
    @csrf
    <input type="submit" name="logout" value="Logout">
</form>
    
</body>