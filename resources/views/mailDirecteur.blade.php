@component('mail::message')
    # Nouvelle demande d'achat est recu

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    type : {{$type}}
    email : {{$email}}
    observation du manager : {{$observation_manager}}
    observation du directeur : {{$observation_directeur}}

    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/manager_nouvelledm/{{$idDA}}">ce lien </a> pour valider la demande .

@endcomponent
