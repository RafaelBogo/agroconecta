<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoFinalizado extends Mailable
{
    use Queueable, SerializesModels;

    public $orderDetails;

    /**
     * Cria uma nova instância.
     *
     * @param array $orderDetails
     */
    public function __construct($orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    /**
     * Constrói o e-mail.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Detalhes do seu Pedido - AgroConecta')
                    ->view('cart.pedido_finalizado');
    }


}
