<?php

namespace Modules\System\Livewire;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Modules\System\Imports\UsersImport;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use AuthorizesRequests, WithFileUploads, WithPagination;

    // Modal state
    public bool $isModalOpen = false;
    public bool $isImportModalOpen = false;

    // Form state
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = '';

    // Search
    public string $search = '';

    // Bulk Edit
    public array $selectedUsers = [];
    public bool $selectAll = false;
    public string $bulkRole = '';

    // Import
    public $importFile;

    public function mount(): void
    {
        abort_if(! auth()->user()->hasRole('Superadmin'), 403, 'Access denied. Superadmin only.');
    }

    // ── Search ────────────────────────────────────────────────────────────────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Select All Toggle ─────────────────────────────────────────────────────

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedUsers = User::query()
                ->when($this->search, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%");
                    });
                })
                ->latest()
                ->paginate(10)
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    // ── Bulk Edit ─────────────────────────────────────────────────────────────

    public function applyBulkRole(): void
    {
        $this->validate([
            'bulkRole' => ['required', 'string', 'exists:roles,name'],
        ]);

        if (empty($this->selectedUsers)) {
            $this->dispatch('notify', message: 'No users selected.');
            return;
        }

        $updatedCount = 0;
        $skippedSelf = false;

        foreach ($this->selectedUsers as $id) {
            // Prevent Superadmin from changing their own role
            if ((int) $id === auth()->id()) {
                $skippedSelf = true;
                continue;
            }

            $user = User::find($id);
            if ($user) {
                $user->syncRoles($this->bulkRole);
                $updatedCount++;
            }
        }

        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->bulkRole = '';

        $message = "Bulk role updated for {$updatedCount} user(s).";
        if ($skippedSelf) {
            $message .= ' Your own role was skipped for security.';
        }

        $this->dispatch('notify', message: $message);
    }

    // ── Modal Controls ────────────────────────────────────────────────────────

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

    public function openImportModal(): void
    {
        $this->importFile = null;
        $this->isImportModalOpen = true;
    }

    public function closeImportModal(): void
    {
        $this->isImportModalOpen = false;
        $this->importFile = null;
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

    // ── CRUD ──────────────────────────────────────────────────────────────────

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

    // ── Import / Export ───────────────────────────────────────────────────────

    public function importUsers(): void
    {
        $this->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,csv,xls', 'max:5120'],
        ]);

        try {
            $import = new UsersImport();
            Excel::import($import, $this->importFile->getRealPath());

            $message = "Import complete! {$import->createdCount} created, {$import->updatedCount} updated.";
            $this->dispatch('notify', message: $message);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = collect($failures)
                ->take(3)
                ->map(fn ($f) => "Row {$f->row()}: {$f->errors()[0]}")
                ->implode(' | ');
            $this->dispatch('notify', message: "Import failed: {$errorMsg}");
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Import failed: ' . $e->getMessage());
        }

        $this->closeImportModal();
    }

    public function downloadTemplate()
    {
        $data = [
            ['Name', 'Email', 'Password', 'Role'],
            ['John Doe', 'john@company.com', 'secret123', 'Employee'],
            ['Jane Smith', 'jane@company.com', '', 'Manager'],
        ];

        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function array(): array { return $this->data; }
        }, 'users_import_template.xlsx');
    }

    // ── Render ─────────────────────────────────────────────────────────────────

    #[Layout('layouts.app')]
    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        $roles = Role::orderBy('name')->get();

        return view('system::livewire.user-manager', compact('users', 'roles'));
    }
}
