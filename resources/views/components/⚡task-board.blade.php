<?php

use Livewire\Component;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

new class extends Component
{
    public Project $project;
    public $title = '';
    public $description = '';
    public $assigned_to = '';

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function users()
    {
        return User::all();
    }

    public function tasksByStatus($status)
    {
        return $this->project->tasks()
            ->where('status', $status)
            ->with('assignee')
            ->latest()
            ->get();
    }

    public function createTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $this->project->tasks()->create([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to ?: null,
            'status' => 'todo',
        ]);

        $this->reset(['title', 'description', 'assigned_to']);
    }

    public function moveTask($taskId, $newStatus)
    {
        $task = Task::findOrFail($taskId);
        $task->update(['status' => $newStatus]);
    }

    public function deleteTask($taskId)
    {
        Task::findOrFail($taskId)->delete();
    }
};
?>

<div class="max-w-6xl mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-indigo-900">{{ $project->name }}</h1>
            <p class="text-gray-500">{{ $project->description }}</p>
        </div>
        <a href="/projects" class="text-indigo-600 hover:underline text-sm">← Retour aux projets</a>
    </div>

    <form wire:submit="createTask" class="bg-white p-4 rounded-lg shadow mb-6 flex gap-3">
        <input
            type="text"
            wire:model="title"
            placeholder="Titre de la tâche"
            class="flex-1 border rounded px-3 py-2"
        >
        <input
            type="text"
            wire:model="description"
            placeholder="Description"
            class="flex-1 border rounded px-3 py-2"
        >
        <select wire:model="assigned_to" class="border rounded px-3 py-2">
            <option value="">Non assigné</option>
            @foreach ($this->users() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Ajouter
        </button>
    </form>

    @error('title')
        <p class="text-red-600 mb-4">{{ $message }}</p>
    @enderror

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ([
            'todo' => ['label' => 'À faire', 'color' => 'bg-gray-100'],
            'in_progress' => ['label' => 'En cours', 'color' => 'bg-yellow-50'],
            'done' => ['label' => 'Terminé', 'color' => 'bg-green-50'],
        ] as $status => $col)
            <div class="{{ $col['color'] }} rounded-lg p-4">
                <h2 class="font-semibold text-gray-700 mb-3">{{ $col['label'] }}</h2>

                <div class="space-y-3">
                    @foreach ($this->tasksByStatus($status) as $task)
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="font-medium text-gray-800">{{ $task->title }}</p>
                            @if ($task->description)
                                <p class="text-sm text-gray-500">{{ $task->description }}</p>
                            @endif
                            @if ($task->assignee)
                                <p class="text-xs text-indigo-600 mt-1">👤 {{ $task->assignee->name }}</p>
                            @endif

                            <div class="flex gap-2 mt-2">
                                @if ($status !== 'todo')
                                    <button wire:click="moveTask({{ $task->id }}, 'todo')" class="text-xs text-gray-500 hover:underline">← À faire</button>
                                @endif
                                @if ($status !== 'in_progress')
                                    <button wire:click="moveTask({{ $task->id }}, 'in_progress')" class="text-xs text-yellow-600 hover:underline">En cours</button>
                                @endif
                                @if ($status !== 'done')
                                    <button wire:click="moveTask({{ $task->id }}, 'done')" class="text-xs text-green-600 hover:underline">Terminé →</button>
                                @endif
                                <button wire:click="deleteTask({{ $task->id }})" class="text-xs text-red-500 hover:underline ml-auto">🗑</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>