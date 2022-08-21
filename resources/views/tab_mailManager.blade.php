@component('mail::message')
    # Nouveau tableau comparatif est recu

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    email : {{$email}}
    observation du manager : {{$observation_manager}}

    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/directeur_nouvelletab/{{$idDA}}">ce lien </a> pour valider la demande .

@endcomponent
