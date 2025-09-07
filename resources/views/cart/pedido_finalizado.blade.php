<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Finalizado</title>
</head>
<body>
    <h1>Olá, {{ $orderDetails['user_name'] }}!</h1>
    <p>Obrigado por realizar sua compra conosco!</p>

    <h3>Detalhes do Pedido:</h3>
    <ul>
        @foreach ($orderDetails['items'] as $item)
            <li>
                {{ $item['quantity'] }}x {{ $item['name'] }} - R$ {{ number_format($item['price'], 2, ',', '.') }}
                <br>
                <small><strong>Endereço de Retirada:</strong> {{ $item['seller_address'] }}</small>
            </li>
        @endforeach
    </ul>

    <p><strong>Total:</strong> R$ {{ number_format($orderDetails['total'], 2, ',', '.') }}</p>

    <p>Estamos à disposição para qualquer dúvida.</p>
    <p><strong>Equipe AgroConecta</strong></p>
</body>
</html>
