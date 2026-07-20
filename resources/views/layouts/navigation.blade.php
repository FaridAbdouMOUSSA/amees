<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            AMEES
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav me-auto">
                <a href="{{ route('epreuves.index') }}" class="nav-link">📚 Épreuves</a>
                @auth
                    <a href="{{ route('classement') }}" class="nav-link">🏆 Classement</a>
                @endauth
            </div>

            <div class="navbar-nav ms-auto">
                @auth
                    <span class="nav-link">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link text-danger">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                @endauth
            </div>
        </div>
    </div>
</nav>