<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;


uses(RefreshDatabase::class);

describe('Dashboard Module', function() {
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

    test('staff can access dashboard page on staff layout', function () {
        actingAs($this->staff)
        ->get(route('staff.dashboard.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('Staff/Dashboard/Index')
        );
    });

    test('admin cannot access dashboard page on staff layout', function () {
        actingAs($this->admin)
        ->get(route('staff.dashboard.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });


    test('admin can access dashboard page on system administrator layout', function () {
        actingAs($this->admin)
        ->get(route('system-administrator.dashboard.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Dashboard/Index')
        );
    });

    test('staff cannot access dashboard page on system administrator layout', function () {
        actingAs($this->staff)
        ->get(route('system-administrator.dashboard.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });
});
