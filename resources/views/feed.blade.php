<h1>🔥 Fil AMEES</h1>

@foreach($epreuves as $e)

<div style="border:1px solid #ddd; margin:10px; padding:10px; border-radius:10px">

    <!-- 🏫 ÉTABLISSEMENT -->
    <h3>🏫 {{ $e->user->name ?? 'Établissement' }}</h3>

    <!-- 📚 CONTENU -->
    <p><strong>{{ $e->titre }}</strong></p>
    <p>{{ $e->matiere }} - {{ $e->classe }}</p>

    <!-- 📥 DOWNLOAD -->
    <a href="/download/{{ $e->id }}">
        📥 Télécharger
    </a>

    <br><br>

    <!-- ❤️ LIKE -->
    <form method="POST" action="/like/{{ $e->id }}">
        @csrf
        <button>❤️ {{ $e->likes_count }}</button>
    </form>

    <!-- 💬 COMMENTAIRES -->
    <p>💬 {{ $e->commentaires_count }}</p>

    <!-- 🏆 SCORE (option futur) -->
    <p>
        🏆 Score :
        {{ ($e->likes_count * 2) + ($e->commentaires_count * 1) + ($e->downloads * 3) }}
    </p>

</div>

@endforeach