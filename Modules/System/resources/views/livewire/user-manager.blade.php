<div class="tw-font-sans">

    {{-- ── Header ── --}}
    <div class="tw-mb-6">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-700">System Settings</span>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">User Management</span>
        </nav>
        <div>
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">System Settings / User Management</h1>
            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Manage application users and their assigned roles.</p>
        </div>
    </div>

    {{-- ── Action Bar (Search + Buttons) ── --}}
    <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-3 tw-mb-4">
        {{-- Left: Search --}}
        <div class="tw-relative tw-w-full sm:tw-w-80">
            <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3 tw-flex tw-items-center tw-pointer-events-none">
                <svg class="tw-w-4 tw-h-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search by name or email..."
                   class="tw-block tw-w-full tw-pl-10 tw-pr-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-transition-colors tw-bg-white">
        </div>

        {{-- Right: Action Buttons --}}
        <div class="tw-flex tw-items-center tw-gap-2 tw-flex-shrink-0">
            {{-- Download Template --}}
            <button wire:click="downloadTemplate"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Template
            </button>

            {{-- Import Users --}}
            <button wire:click="openImportModal"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md"
                    style="background-color: #2d8659;"
                    onmouseover="this.style.backgroundColor='#246e49'"
                    onmouseout="this.style.backgroundColor='#2d8659'">
                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import
            </button>

            {{-- Add New User --}}
            <button wire:click="openCreateModal"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md"
                    style="background-color: #174D9D;"
                    onmouseover="this.style.backgroundColor='#123f82'"
                    onmouseout="this.style.backgroundColor='#174D9D'">
                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </button>
        </div>
    </div>

    {{-- ── Bulk Edit Bar (Conditional) ── --}}
    @if (count($selectedUsers) > 0)
        <div class="tw-mb-4 tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-gap-3 tw-px-4 tw-py-3 tw-rounded-lg tw-border tw-border-blue-200 tw-bg-blue-50">
            <div class="tw-flex tw-items-center tw-gap-2">
                <svg class="tw-w-5 tw-h-5 tw-text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="tw-text-sm tw-font-semibold tw-text-blue-800">{{ count($selectedUsers) }} user(s) selected</span>
            </div>
            <div class="tw-flex tw-items-center tw-gap-2 sm:tw-ml-auto">
                <select wire:model="bulkRole"
                        class="tw-block tw-px-3 tw-py-2 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-bg-white">
                    <option value="">— Select Role —</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
                <button wire:click="applyBulkRole"
                        wire:loading.attr="disabled"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md disabled:tw-opacity-50"
                        style="background-color: #174D9D;"
                        onmouseover="this.style.backgroundColor='#123f82'"
                        onmouseout="this.style.backgroundColor='#174D9D'">
                    <svg wire:loading.remove wire:target="applyBulkRole" class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg wire:loading wire:target="applyBulkRole" class="tw-animate-spin tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Apply Role
                </button>
            </div>
        </div>
    @endif

    {{-- ── Data Table Card ── --}}
    <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
        <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
            <thead class="tw-bg-gray-50">
                <tr>
                    <th class="tw-px-4 tw-py-3 tw-text-center tw-w-10">
                        <input type="checkbox"
                               wire:model.live="selectAll"
                               class="tw-w-4 tw-h-4 tw-rounded tw-border-gray-300 tw-text-[#174D9D] focus:tw-ring-[#174D9D]">
                    </th>
                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Name</th>
                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Email</th>
                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Role</th>
                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Joined Date</th>
                    <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="tw-bg-white tw-divide-y tw-divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                        {{-- Checkbox --}}
                        <td class="tw-px-4 tw-py-4 tw-text-center">
                            <input type="checkbox"
                                   wire:model.live="selectedUsers"
                                   value="{{ $user->id }}"
                                   class="tw-w-4 tw-h-4 tw-rounded tw-border-gray-300 tw-text-[#174D9D] focus:tw-ring-[#174D9D]">
                        </td>

                        {{-- Name + Avatar --}}
                        <td class="tw-px-6 tw-py-4">
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-9 tw-h-9 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 tw-text-white tw-text-sm tw-font-bold"
                                     style="background-color: #174D9D;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-gray-600">{{ $user->email }}</td>

                        {{-- Role Badge --}}
                        <td class="tw-px-6 tw-py-4">
                            @php $roleName = $user->roles->first()?->name ?? 'No Role'; @endphp
                            @if ($roleName === 'Superadmin')
                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-purple-100 tw-text-purple-800">{{ $roleName }}</span>
                            @elseif ($roleName === 'Manager')
                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-blue-100 tw-text-blue-800">{{ $roleName }}</span>
                            @elseif ($roleName === 'Team Leader')
                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-yellow-100 tw-text-yellow-800">{{ $roleName }}</span>
                            @elseif ($roleName === 'Employee')
                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-green-100 tw-text-green-800">{{ $roleName }}</span>
                            @else
                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-gray-100 tw-text-gray-600">{{ $roleName }}</span>
                            @endif
                        </td>

                        {{-- Joined Date --}}
                        <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="tw-px-6 tw-py-4 tw-text-right">
                            <div class="tw-flex tw-items-center tw-justify-end tw-gap-2">
                                {{-- Edit --}}
                                <button wire:click="edit({{ $user->id }})"
                                        title="Edit User"
                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-text-gray-500 hover:tw-text-[#174D9D] hover:tw-bg-blue-50 tw-transition-colors">
                                    <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Delete (disabled for self) --}}
                                @if ($user->id !== auth()->id())
                                    <button wire:click="delete({{ $user->id }})"
                                            wire:confirm="Are you sure you want to deactivate this user? This action can be undone by a database administrator."
                                            title="Deactivate User"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-text-gray-400 hover:tw-text-red-600 hover:tw-bg-red-50 tw-transition-colors">
                                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @else
                                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-text-gray-200 tw-cursor-not-allowed" title="Cannot delete your own account">
                                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="tw-px-6 tw-py-16 tw-text-center">
                            <div class="tw-flex tw-flex-col tw-items-center tw-gap-2">
                                <svg class="tw-w-12 tw-h-12 tw-text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                @if ($search)
                                    <p class="tw-text-sm tw-font-medium tw-text-gray-400">No users found matching "{{ $search }}"</p>
                                    <button wire:click="$set('search', '')" class="tw-text-xs tw-text-[#174D9D] hover:tw-underline">Clear search</button>
                                @else
                                    <p class="tw-text-sm tw-font-medium tw-text-gray-400">No users found</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- ── Create/Edit Modal ── --}}
    @if ($isModalOpen)
        <div class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-z-50 tw-flex tw-items-center tw-justify-center tw-p-4">
            <div class="tw-bg-white tw-rounded-lg tw-shadow-xl tw-w-full tw-max-w-lg" wire:click.stop>

                {{-- Modal Header --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-100">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(23, 77, 157, 0.1);">
                            <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">
                            {{ $userId ? 'Edit User' : 'Add New User' }}
                        </h2>
                    </div>
                    <button wire:click="closeModal" class="tw-text-gray-400 hover:tw-text-gray-600 tw-transition-colors tw-p-1 tw-rounded-lg hover:tw-bg-gray-100">
                        <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="tw-px-6 tw-py-5 tw-space-y-5">

                    {{-- Name --}}
                    <div>
                        <label for="user_name" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">Full Name</label>
                        <input type="text"
                               wire:model="name"
                               id="user_name"
                               placeholder="e.g. John Doe"
                               class="tw-block tw-w-full tw-px-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-transition-colors @error('name') tw-border-red-400 @enderror">
                        @error('name')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="user_email" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">Email Address</label>
                        <input type="email"
                               wire:model="email"
                               id="user_email"
                               placeholder="e.g. john@company.com"
                               class="tw-block tw-w-full tw-px-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-transition-colors @error('email') tw-border-red-400 @enderror">
                        @error('email')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="user_role" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">Role</label>
                        <select wire:model="role"
                                id="user_role"
                                class="tw-block tw-w-full tw-px-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-transition-colors tw-bg-white @error('role') tw-border-red-400 @enderror">
                            <option value="">— Select a role —</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="user_password" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">Password</label>
                        <input type="password"
                               wire:model="password"
                               id="user_password"
                               placeholder="Minimum 8 characters"
                               autocomplete="new-password"
                               class="tw-block tw-w-full tw-px-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-transition-colors @error('password') tw-border-red-400 @enderror">
                        @error('password')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                        @if ($userId)
                            <p class="tw-mt-1.5 tw-text-xs tw-text-gray-500">Leave blank if you don't want to change the password.</p>
                        @endif
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-px-6 tw-py-4 tw-border-t tw-border-gray-100 tw-bg-gray-50 tw-rounded-b-lg">
                    <button wire:click="closeModal"
                            class="tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                        Cancel
                    </button>
                    <button wire:click="save"
                            wire:loading.attr="disabled"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                            style="background-color: #174D9D;"
                            onmouseover="this.style.backgroundColor='#123f82'"
                            onmouseout="this.style.backgroundColor='#174D9D'">
                        <svg wire:loading.remove wire:target="save" class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg wire:loading wire:target="save" class="tw-animate-spin tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">Save User</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- ── Import Modal ── --}}
    @if ($isImportModalOpen)
        <div class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-z-50 tw-flex tw-items-center tw-justify-center tw-p-4">
            <div class="tw-bg-white tw-rounded-lg tw-shadow-xl tw-w-full tw-max-w-md" wire:click.stop>

                {{-- Modal Header --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-100">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(45, 134, 89, 0.1);">
                            <svg class="tw-w-5 tw-h-5 tw-text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">Import Users</h2>
                    </div>
                    <button wire:click="closeImportModal" class="tw-text-gray-400 hover:tw-text-gray-600 tw-transition-colors tw-p-1 tw-rounded-lg hover:tw-bg-gray-100">
                        <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="tw-px-6 tw-py-5 tw-space-y-4">
                    <div class="tw-p-3 tw-rounded-lg tw-bg-blue-50 tw-border tw-border-blue-200">
                        <p class="tw-text-xs tw-text-blue-700">
                            <strong>Format:</strong> Upload an <code>.xlsx</code> or <code>.csv</code> file with columns:
                            <strong>Name, Email, Password, Role</strong>. If Password is empty, default <code>password123</code> is used.
                            Existing emails will be updated.
                        </p>
                    </div>

                    <div>
                        <label class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">Select File</label>
                        <input type="file"
                               wire:model="importFile"
                               accept=".xlsx,.csv,.xls"
                               class="tw-block tw-w-full tw-text-sm tw-text-gray-600 file:tw-mr-3 file:tw-py-2 file:tw-px-4 file:tw-rounded-lg file:tw-border-0 file:tw-text-sm file:tw-font-semibold file:tw-bg-blue-50 file:tw-text-[#174D9D] hover:file:tw-bg-blue-100 tw-cursor-pointer">
                        @error('importFile')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror

                        {{-- Upload progress --}}
                        <div wire:loading wire:target="importFile" class="tw-mt-2 tw-flex tw-items-center tw-gap-2">
                            <svg class="tw-animate-spin tw-w-4 tw-h-4 tw-text-[#174D9D]" fill="none" viewBox="0 0 24 24">
                                <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="tw-text-xs tw-text-gray-500">Uploading file...</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-px-6 tw-py-4 tw-border-t tw-border-gray-100 tw-bg-gray-50 tw-rounded-b-lg">
                    <button wire:click="closeImportModal"
                            class="tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                        Cancel
                    </button>
                    <button wire:click="importUsers"
                            wire:loading.attr="disabled"
                            wire:target="importUsers"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                            style="background-color: #2d8659;"
                            onmouseover="this.style.backgroundColor='#246e49'"
                            onmouseout="this.style.backgroundColor='#2d8659'">
                        <svg wire:loading.remove wire:target="importUsers" class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <svg wire:loading wire:target="importUsers" class="tw-animate-spin tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="importUsers">Import Users</span>
                        <span wire:loading wire:target="importUsers">Importing...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
