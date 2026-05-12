<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <a href="{{ route('home') }}" class="text-xl font-bold">AMEES</a>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('epreuves.index') }}">📚 Épreuves</a>
                    @auth
                        <a href="{{ route('ranking.top') }}">🏆 Classement</a>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <div class="ml-3 relative">
                        <div class="flex items-center">
                            <span>{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="ml-3">
                                @csrf
                                <button>Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}">Connexion</a>
                @endauth
            </div>
        </div>
    </div>
</nav>