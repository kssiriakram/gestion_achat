<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">´
            <h4>Login</h4>
            <hr>
           <!-- <form action="/signin" method="post"> -->
            
           <form action="/gestion_achat/public/signin" method="post">

                @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
                @csrf


            <div class="form-group">
                <label for="username" >username</label>
                <input type="text" class="text form-control" name="username" value="{{old('username')}}" placeholder="Enter username">
                <span class="text-danger">@error('username'){{ $message }}@enderror</span>
                </div>



                <div class="form-group">
                    <label for="password" >Password</label>
                    <input type="password" class="text form-control" name="password" value="{{old('password')}}" placeholder="Enter password">
                    <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                    </div>



                    <button  type="submit" class="btn btn-block btn-primary">Submit</button>
                    <br>

                    <a href="/gestion_achat/public/registration" class="">Not registered || click here</a>
                </form>
        </div>

    </div>
    </div>
</div>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>
