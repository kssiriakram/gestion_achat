@component('mail::message')
    # Une demande d'achat est modifiee

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    email : {{$email}}


    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/manager_nouvelledm/{{$idDA}}">ce lien </a> pour valider la demande .

@endcomponent
