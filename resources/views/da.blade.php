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
        <div class="col-md-4 col-md-offset-4">

            <h4>veuillez renseigner les champs suivant:</h4>
            <hr>
            <form action="/gestion_achat/public/add_da" method="post">
                @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
                @csrf
            <div class="form-group">
                <label for="delai" >Delai</label>
                <input type="date" class="text form-control" name="delai" value="{{old('delai')}}" placeholder="Selectionner un delai">
                <span class="text-danger">@error('delai'){{ $message }}@enderror</span>
                </div>

                <div class="form-group">
                    <label for="reference">reference</label>
                    <input type="text" class="text form-control" name="reference" value="{{old('reference')}}" placeholder="Entrer une reference">
                    <span class="text-danger">@error('reference'){{ $message }}@enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="qte" >Quantite</label>
                        <input type="text" class="text form-control" name="qte" value="{{old('qte')}}" placeholder="Entrer la quantite">
                        <span class="text-danger">@error('qte'){{ $message }}@enderror</span>
                        </div>

                            <div class="form-group">
                                <label for="acheteur" >L'acheteur</label>
                                <select name="acheteur" id="acheteur" class="select form-control">
                                    @foreach ($acheteurs as $acheteur )
                                    <option value={{ $acheteur->id }}>{{ $acheteur->name }}</option>
                                    @endforeach
                                </select>

                                </div>

                                <div class="form-group">
                                    <label for="code_CC" >Code centre de cout</label>
                                    <input type="text" class="text form-control" name="code_CC" value="{{old('code_CC')}}" placeholder="Entrer le code de centre de cout">
                                    <span class="text-danger">@error('code_CC'){{ $message }}@enderror</span>
                                    </div>

                <div class="form-group">
                    <label for="code_NE" >Code nature economie</label>
                    <input type="code_NE" class="text form-control" name="code_NE" value="{{old('code_NE')}}" placeholder="Enter Code de nature economie">
                    <span class="text-danger">@error('code_NE'){{ $message }}@enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="societe" >societe</label>
                        <select name="societe" id="societe" class="select form-control">
                            <option value='coficab maroc'>coficab maroc</option>
                            <option value='coficab international'>coficab international</option>
                        </select>
                        <span class="text-danger">@error('societe'){{ $message }}@enderror</span>
                        </div>

                    <button  type="submit" class="btn btn-block btn-primary">Submit</button>
                    <br>

                    <a href="/gestion_achat/public/logout" class="">Logout</a>
                </form>
        </div>


    </div>
    </div>
</div>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>
