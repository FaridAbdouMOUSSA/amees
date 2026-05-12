<form method="POST" action="{{ route('like.toggle', $epreuve->id) }}" class="inline">
    @csrf
    @php
        $userLiked = $epreuve->likes->contains('user_id', auth()->id());
    @endphp
    <button type="submit" class="btn {{ $userLiked ? 'btn-danger' : 'btn-primary' }}">
        ❤️ {{ $epreuve->likes_count }}
    </button>
</form>