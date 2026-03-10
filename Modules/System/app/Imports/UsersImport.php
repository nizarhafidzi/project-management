<?php

namespace Modules\System\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public int $createdCount = 0;
    public int $updatedCount = 0;

    /**
     * Process each row from the Excel file.
     */
    public function model(array $row)
    {
        $email    = trim(strtolower($row['email'] ?? ''));
        $name     = trim($row['name'] ?? '');
        $password = trim($row['password'] ?? '');
        $role     = trim($row['role'] ?? '');

        if (empty($email)) {
            return null;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // Update existing user
            $user->name = $name ?: $user->name;

            if (! empty($password)) {
                $user->password = Hash::make($password);
            }

            $user->save();
            $this->updatedCount++;
        } else {
            // Create new user
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make(! empty($password) ? $password : 'password123'),
            ]);
            $this->createdCount++;
        }

        // Assign role if provided
        if (! empty($role)) {
            $user->syncRoles($role);
        }

        return null; // We handle persistence manually
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role'  => ['required', 'string', 'exists:roles,name'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages(): array
    {
        return [
            'name.required'  => 'The Name column is required for each row.',
            'email.required' => 'The Email column is required for each row.',
            'email.email'    => 'The Email column must contain a valid email address.',
            'role.required'  => 'The Role column is required for each row.',
            'role.exists'    => 'The Role ":input" does not exist in the system.',
        ];
    }
}
