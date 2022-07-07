@component('mail::message')
    # Nouvelle demande d'achat est recu

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    type : {{$type}}
    email : {{$email}}


    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/manager_nouvelledm/{{$idDA}}">ce lien </a> pour valider la demande .

@endcomponent
