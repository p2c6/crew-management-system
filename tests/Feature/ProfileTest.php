<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('Profile Module Test', function() {
    beforeEach(function() {
        $this->staff = User::factory()
        ->for(Role::factory()->state([
            'slug' => UserRole::Staff->value,
        ]))
        ->create();

        $this->admin = User::factory()
        ->for(Role::factory()->state([
            'slug' => UserRole::SystemAdministrator->value,
        ]))
        ->create();
    });

    test('admin can access profile page in admin layout', function() {
        actingAs($this->admin)
        ->get(route('system-administrator.profile.edit'))
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Profile/Index')
        );
    });

    test('staff cannot access profile page in admin layout', function() {
        actingAs($this->staff)
        ->get(route('system-administrator.profile.edit'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('staff can access profile page in staff layout', function() {
        actingAs($this->staff)
        ->get(route('staff.profile.edit'))
        ->assertInertia(fn(Assert $page) =>
            $page->component('Staff/Profile/Index')
        );
    });

    test('admin cannot access profile page in staff layout', function() {
        actingAs($this->admin)
        ->get(route('staff.profile.edit'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can update personal information in profile page in admin layout', function() {
        $oldFullName = $this->admin->full_name;

        $updated = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
        ];

        actingAs($this->admin)
        ->put(route('system-administrator.profile.update.personal-information', $this->admin->id), $updated)
        ->assertStatus(200);

        $newFullName = $updated['first_name'] . " " . $updated['last_name'];

        $this->assertDatabaseHas('users', [
            'full_name' => $newFullName
        ]);
        
        $this->assertDatabaseMissing('users', [
            'full_name' => $oldFullName
        ]);
    });

    test('staff cannot update personal information in profile page in admin layout', function() {
        $oldFullName = $this->admin->full_name;

        $updated = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
        ];

        actingAs($this->staff)
        ->put(route('system-administrator.profile.update.personal-information', $this->admin->id), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $newFullName = $updated['first_name'] . " " . $updated['last_name'];

        $this->assertDatabaseHas('users', [
            'full_name' => $oldFullName
        ]);
        
        $this->assertDatabaseMissing('users', [
            'full_name' => $newFullName
        ]);
    });

    test('staff can update personal information in profile page in staff layout', function() {
        $oldFullName = $this->staff->full_name;

        $updated = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
        ];

        actingAs($this->staff)
        ->put(route('staff.profile.update.personal-information', $this->staff->id), $updated)
        ->assertStatus(200);

        $newFullName = $updated['first_name'] . " " . $updated['last_name'];

        $this->assertDatabaseHas('users', [
            'full_name' => $newFullName
        ]);
        
        $this->assertDatabaseMissing('users', [
            'full_name' => $oldFullName
        ]);
    });

    test('admin cannot update personal information in profile page in staff layout', function() {
        $oldFullName = $this->admin->full_name;

        $updated = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
        ];

        actingAs($this->admin)
        ->put(route('staff.profile.update.personal-information', $this->admin->id), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $newFullName = $updated['first_name'] . " " . $updated['last_name'];

        $this->assertDatabaseHas('users', [
            'full_name' => $oldFullName
        ]);
        
        $this->assertDatabaseMissing('users', [
            'full_name' => $newFullName
        ]);
    });
    
    test('admin can update password in profile page in admin layout', function() {
        $admin = User::factory()
        ->for(Role::factory()->state([
            'slug' => UserRole::SystemAdministrator->value,
        ]))
        ->create([
            'password' => Hash::make('password123')
        ]);

        $updated = [
            'current_password' => 'password123',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ];

        actingAs($admin)
        ->put(route('system-administrator.profile.update.password', $admin->id), $updated)
        ->assertStatus(200);

        $admin->refresh();

        $this->assertTrue(Hash::check($updated['password'], $admin->password));
    });

    test('staff cannot update password in profile page in admin layout', function() {
        $staff = User::factory()
        ->for(Role::factory()->state([
            'slug' => UserRole::Staff->value,
        ]))
        ->create([
            'password' => Hash::make('password123')
        ]);

        $updated = [
            'current_password' => 'password123',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ];

        actingAs($staff)
        ->put(route('system-administrator.profile.update.password', $staff->id), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $staff->refresh();

        $this->assertFalse(Hash::check($updated['password'], $staff->password));
    });

    test('staff can update password in profile page in staff layout', function() {
        $staff = User::factory()
        ->for(Role::factory()->state([
            'slug' => UserRole::Staff->value,
        ]))
        ->create([
            'password' => Hash::make('password123')
        ]);

        $updated = [
            'current_password' => 'password123',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ];

        actingAs($staff)
        ->put(route('staff.profile.update.password', $staff->id), $updated)
        ->assertStatus(200);

        $staff->refresh();

        $this->assertTrue(Hash::check($updated['password'], $staff->password));
    });

    test('admin cannot update password in profile page in staff layout', function() {
        $admin = User::factory()
        ->for(Role::factory()->state([
            'slug' => UserRole::SystemAdministrator->value,
        ]))
        ->create([
            'password' => Hash::make('password123')
        ]);

        $updated = [
            'current_password' => 'password123',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ];

        actingAs($admin)
        ->put(route('staff.profile.update.password', $admin->id), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $admin->refresh();

        $this->assertFalse(Hash::check($updated['password'], $admin->password));
    });
});