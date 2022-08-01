@component('mail::message')
    # Nouveaux tableau comparatif est recu

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    email : {{$email}}


    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/#">ce lien </a> pour valider la demande .

@endcomponent
