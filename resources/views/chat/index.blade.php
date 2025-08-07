@extends('layouts.app')

@section('title', 'Chat')

@section('content')
    <h3 class="text-center mb-4">Conversando com {{ $user->name }}</h3>

    <div class="mb-4" style="height: 400px; overflow-y: scroll; border: 1px solid #ccc; padding: 15px; border-radius: 10px; background: white;">
        @forelse($messages as $msg)
            <div class="mb-3 {{ $msg->sender_id == auth()->id() ? 'text-end' : 'text-start' }}">
                <small class="d-block text-muted">
                    {{ $msg->sender_id == auth()->id() ? 'Você' : $user->name }} - {{ $msg->created_at->format('d/m/Y H:i') }}
                </small>
                <span class="d-inline-block p-2 rounded {{ $msg->sender_id == auth()->id() ? 'bg-success text-white' : 'bg-light' }}">
                    {{ $msg->message }}
                </span>
            </div>
        @empty
            <p class="text-center text-muted">Nenhuma mensagem ainda.</p>
        @endforelse
    </div>

    <form action="{{ route('chat.send') }}" method="POST" class="mt-3">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
        <div class="input-group">
            <textarea name="message" class="form-control" placeholder="Digite sua mensagem..." required rows="1"></textarea>
            <button class="btn btn-success" type="submit">Enviar</button>
        </div>
    </form>
@endsection
