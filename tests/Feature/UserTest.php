<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('User Module', function(){
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

        $role = Role::factory()->create();

        $this->fakeData = [
            'email' => fake()->email(),
            'full_name' => fake()->name(),
            'password' => fake()->password(),
            'role_id' => $role->id,
        ];
    });

    
    test('admin can access users page on system administrator layout', function () {
        actingAs($this->admin)
        ->get(route('system-administrator.users.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Users/Index')
        );
    });


    test('staff cannot access users page on administrator layout', function () {
        actingAs($this->staff)
        ->get(route('system-administrator.users.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can create new user on users page on system administrator layout', function () {
        actingAs($this->admin)
        ->post(route('system-administrator.users.store'), $this->fakeData)
        ->assertStatus(200);
        
        $this->assertDatabaseHas('users', [
            'email' => $this->fakeData['email'],
            'role_id' => $this->fakeData['role_id'],
        ]);
    });

    test('staff cannot create new user on users page on system administrator layout', function () {
        actingAs($this->staff)
        ->post(route('system-administrator.users.store'), $this->fakeData)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('users', [
            'email' => $this->fakeData['email'],
            'role_id' => $this->fakeData['role_id'],
        ]);
    });

    test('admin can see users in the list on users page on system administrator layout', function () {
        User::factory(10)->create();
        
        actingAs($this->admin)
        ->get(route('system-administrator.users.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Users/Index')
            ->has('users.data', 10)
        );
    });

    test('staff cannot see users in the list on users page on system administrator layout', function () {
        User::factory(10)->create();

        actingAs($this->staff)
        ->get(route('system-administrator.users.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can edit user on users page on system administrator layout', function () {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $updated = [
            'email' => fake()->email(),
            'password' => fake()->password(),
            'full_name' => fake()->name(),
            'role_id' => $role->id,
        ];

        actingAs($this->admin)
        ->put(route('system-administrator.users.update', $user), $updated)
        ->assertStatus(200);

        $this->assertDatabaseMissing('users',[
            'email' => $this->fakeData['email'],
            'full_name' => $this->fakeData['full_name'],
            'role_id' => $this->fakeData['role_id'],
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $updated['email'],
            'full_name' => $updated['full_name'],
            'role_id' => $updated['role_id'],
        ]);
    });

    test('staff cannot edit user on users page on system administrator layout', function () {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $updated = [
            'email' => fake()->email(),
            'full_name' => fake()->name(),
            'password' => fake()->password(),
            'role_id' => $role->id,
        ];

        actingAs($this->staff)
        ->put(route('system-administrator.users.update', $user), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('users', [
            'email' => $updated['email'],
            'role_id' => $updated['role_id'],
        ]);
    });

    test('admin can delete user on users page on system administrator layout', function () {
        $user = User::factory()->create($this->fakeData);

        actingAs($this->admin)
        ->delete(route('system-administrator.users.destroy', $user))
        ->assertStatus(200);

        $this->assertDatabaseMissing('users', $this->fakeData);
    });

    test('staff cannot delete user on users page on system administrator layout', function () {
        $user = User::factory()->create($this->fakeData);

        actingAs($this->staff)
        ->delete(route('system-administrator.users.destroy', $user))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);
    });
});
