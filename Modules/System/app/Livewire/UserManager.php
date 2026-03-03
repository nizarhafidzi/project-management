<?php

namespace Modules\System\Livewire;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use AuthorizesRequests, WithPagination;

    // Modal state
    public bool $isModalOpen = false;

    // Form state
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = '';

    public function mount(): void
    {
        abort_if(! auth()->user()->hasRole('Superadmin'), 403, 'Access denied. Superadmin only.');
    }

    // ── Modal Controls ─────────────────────────────────────────────────────────

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->userId   = null;
        $this->name     = '';
        $this->email    = '';
        $this->password = '';
        $this->role     = '';
        $this->resetValidation();
    }

    // ── CRUD ───────────────────────────────────────────────────────────────────

    public function edit(int $id): void
    {
        $user = User::with('roles')->findOrFail($id);

        $this->userId   = $user->id;
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->password = ''; // Always blank on edit
        $this->role     = $user->roles->first()?->name ?? '';

        $this->isModalOpen = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => [$this->userId ? 'nullable' : 'required', 'string', 'min:8'],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ]);

        if ($this->userId) {
            // Update existing user
            $user = User::findOrFail($this->userId);
            $user->name  = $this->name;
            $user->email = $this->email;

            if (! empty($this->password)) {
                $user->password = Hash::make($this->password);
            }

            $user->save();
        } else {
            // Create new user
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
            ]);
        }

        $user->syncRoles($this->role);

        $this->closeModal();
        $this->dispatch('notify', message: $this->userId
            ? "User \"{$this->name}\" updated successfully."
            : "User \"{$this->name}\" created successfully.");
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);

        // Prevent deleting the currently logged-in user
        abort_if($user->id === auth()->id(), 403, 'You cannot delete your own account.');

        $user->delete(); // Soft delete

        $this->dispatch('notify', message: "User \"{$user->name}\" has been deactivated.");
    }

    // ── Render ─────────────────────────────────────────────────────────────────

    #[Layout('layouts.app')]
    public function render()
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(10);

        $roles = Role::orderBy('name')->get();

        return view('system::livewire.user-manager', compact('users', 'roles'));
    }
}
