<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DAMail_directeur_refus extends  Mailable
{
    use Queueable, SerializesModels;
    public $societe;
    public $type;
    public $email;
    public $description;
    public $sujet;
    public $username;
    public $idDA;
    public $destinataire;
    public $observation_manager;
    public $observation_directeur;
    /**
     * @param $societe
     * @param $type
     * @param $email
     * @param $description
     * @param $idDA
     */
    public function __construct($username, $societe, $type, $email, $description, $sujet , $idDA,$observation_manager,$observation_directeur)
    {
        $this->sujet = $sujet;

        $this->societe = $societe;
        $this->username = $username;
        $this->type = $type;
        $this->email = $email;

        $this->description=$description;
        $this->observation_manager=$observation_manager;
        $this->observation_directeur=$observation_directeur;
        $this->idDA=$idDA;

    }

    /**
     * Create a new message instance.
     *
     * @return void
     */

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email, $name = "Plateforme de gestion des achat coficab")->
        subject($this->sujet)->markdown('mailDirecteur_refus');
    }
}
