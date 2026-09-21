<?php

use Livewire\Component;
use App\Models\Project;

new class extends Component
{
    public $name = '';
    public $description = '';

    public function projects()
    {
        return Project::with('owner')->latest()->get();
    }

    public function createProject()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => auth()->id(),
        ]);

        $this->reset(['name', 'description']);
    }
};
?>

<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold text-indigo-900 mb-6">Mes Projets</h1>

    <form wire:submit="createProject" class="bg-white p-4 rounded-lg shadow mb-6 flex gap-3">
        <input
            type="text"
            wire:model="name"
            placeholder="Nom du projet"
            class="flex-1 border rounded px-3 py-2"
        >
        <input
            type="text"
            wire:model="description"
            placeholder="Description (optionnel)"
            class="flex-1 border rounded px-3 py-2"
        >
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Créer
        </button>
    </form>

    @error('name')
        <p class="text-red-600 mb-4">{{ $message }}</p>
    @enderror

    <div class="grid gap-4">
        @foreach ($this->projects() as $project)
            <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-gray-800">{{ $project->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $project->description }}</p>
                    <p class="text-xs text-gray-400 mt-1">Par {{ $project->owner->name }}</p>
                </div>
                <a href="/projects/{{ $project->id }}" class="text-indigo-600 hover:underline text-sm">
                    Voir les tâches →
                </a>
            </div>
        @endforeach
    </div>
</div>