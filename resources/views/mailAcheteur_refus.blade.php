@component('mail::message')
    # Votre demande d'achat est refusee

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    type : {{$type}}
    email : {{$email}}
    observation du manager : {{$observation_manager}}
    observation du directeur : {{$observation_directeur}}
    observation de l'acheteur : {{$observation_acheteur}}
    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/nouvelledm/{{$idDA}}">ce lien </a> pour modifier la demande .

@endcomponent
