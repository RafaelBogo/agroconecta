<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoFinalizadoVendedor extends Mailable
{
    use Queueable, SerializesModels;

    public $sellerDetails;

    /**
     * Create a new message instance.
     *
     * @param array $sellerDetails
     */
    public function __construct($sellerDetails)
    {
        $this->sellerDetails = $sellerDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Novo Pedido Recebido - AgroConecta')
                    ->view('cart.pedido_finalizado_vendedor')
                    ->with('sellerDetails', $this->sellerDetails);
    }
}
