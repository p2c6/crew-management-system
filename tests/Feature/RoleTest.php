<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('Role Module', function(){
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

        $this->fakeData = [
            'name' => fake()->jobTitle(),
            'slug' => Str::snake(fake()->jobTitle()),
        ];
    });

    
    test('admin can access roles page on system administrator layout', function () {
        actingAs($this->admin)
        ->get(route('system-administrator.roles.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Roles/Index')
        );
    });


    test('staff cannot access roles page on administrator layout', function () {
        actingAs($this->staff)
        ->get(route('system-administrator.roles.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can create new role on roles page on system administrator layout', function () {
        actingAs($this->admin)
        ->post(route('system-administrator.roles.store'), $this->fakeData)
        ->assertStatus(200);
        
        $this->assertDatabaseHas('roles', $this->fakeData);
    });

    test('staff cannot create new role on roles page on system administrator layout', function () {
        actingAs($this->staff)
        ->post(route('system-administrator.roles.store'), $this->fakeData)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('roles', $this->fakeData);
    });

    test('admin can see roles in the list on roles page on system administrator layout', function () {
        role::factory(10)->create();
        
        actingAs($this->admin)
        ->get(route('system-administrator.roles.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Roles/Index')
            ->has('roles.data', 10)
        );
    });

    test('staff cannot see roles in the list on roles page on system administrator layout', function () {
        role::factory(10)->create();

        actingAs($this->staff)
        ->get(route('system-administrator.roles.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can edit role on roles page on system administrator layout', function () {
        $role = role::factory()->create($this->fakeData);
        $updated = [
            'name' => fake()->jobTitle(),
            'slug' => Str::slug(fake()->jobTitle())
        ];

        actingAs($this->admin)
        ->put(route('system-administrator.roles.update', $role), $updated)
        ->assertStatus(200);

        $this->assertDatabaseMissing('roles', $this->fakeData);
        $this->assertDatabaseHas('roles', $updated);
    });

    test('staff cannot edit role on roles page on system administrator layout', function () {
        $role = role::factory()->create($this->fakeData);
        $updated = [
            'name' => fake()->jobTitle(),
            'slug' => Str::slug(fake()->jobTitle())
        ];

        actingAs($this->staff)
        ->put(route('system-administrator.roles.update', $role), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('roles', $updated);
    });

    test('admin can delete role on roles page on system administrator layout', function () {
        $role = role::factory()->create($this->fakeData);

        actingAs($this->admin)
        ->delete(route('system-administrator.roles.destroy', $role))
        ->assertStatus(200);

        $this->assertDatabaseMissing('roles', $this->fakeData);
    });

    test('staff cannot delete role on roles page on system administrator layout', function () {
        $role = role::factory()->create($this->fakeData);

        actingAs($this->staff)
        ->delete(route('system-administrator.roles.destroy', $role))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseHas('roles', [
            'id' => $role->id
        ]);
    });
});
