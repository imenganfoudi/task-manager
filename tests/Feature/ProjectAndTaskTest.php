<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProjectAndTaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);
    }

    public function test_authenticated_user_can_create_a_project(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('project-list')
            ->set('name', 'Site E-commerce')
            ->set('description', 'Refonte complète')
            ->call('createProject');

        $this->assertDatabaseHas('projects', [
            'name' => 'Site E-commerce',
            'owner_id' => $user->id,
        ]);
    }

    public function test_project_creation_requires_a_name(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('project-list')
            ->set('name', '')
            ->call('createProject')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_authenticated_user_can_create_a_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $this->actingAs($user);

        Livewire::test('task-board', ['project' => $project])
            ->set('title', 'Créer la page d\'accueil')
            ->call('createTask');

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'title' => 'Créer la page d\'accueil',
            'status' => 'todo',
        ]);
    }

    public function test_task_can_be_moved_between_statuses(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'status' => 'todo']);
        $this->actingAs($user);

        Livewire::test('task-board', ['project' => $project])
            ->call('moveTask', $task->id, 'in_progress');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_admin_can_delete_a_task(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $project = Project::factory()->create(['owner_id' => $admin->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        $this->actingAs($admin);

        Livewire::test('task-board', ['project' => $project])
            ->call('deleteTask', $task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_member_cannot_delete_a_task(): void
    {
        $member = User::factory()->create();
        $member->assignRole('member');
        $project = Project::factory()->create(['owner_id' => $member->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        $this->actingAs($member);

        Livewire::test('task-board', ['project' => $project])
            ->call('deleteTask', $task->id)
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}