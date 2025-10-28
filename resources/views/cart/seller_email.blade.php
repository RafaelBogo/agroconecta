<!DOCTYPE html>
<html>
<head>
    <title>Novo Pedido Recebido</title>
</head>
<body>
    <h1>Olá, {{ $sellerDetails['seller_name'] }}</h1>
    <p>Você recebeu um novo pedido!</p>
    <h3>Detalhes do Pedido:</h3>
    <ul>
        @foreach ($sellerDetails['items'] as $item)
            <li>
                <strong>Produto:</strong> {{ $item['name'] }}<br>
                <strong>Quantidade:</strong> {{ $item['quantity'] }}<br>
                <strong>Comprador:</strong> {{ $sellerDetails['buyer_name'] }}<br>
            </li>
        @endforeach
    </ul>
    <p><strong>Total do Pedido:</strong> R$ {{ number_format($sellerDetails['total'], 2, ',', '.') }}</p>
    <p>Obrigado por utilizar o AgroConecta!</p>
</body>
</html>
