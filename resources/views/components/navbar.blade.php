<nav class="bg-white shadow-sm">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
        <a href="/projects" class="font-semibold text-indigo-900">Task Manager</a>

        <div class="flex items-center gap-4 text-sm">
            <span class="text-gray-600">{{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-indigo-600 hover:underline">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>