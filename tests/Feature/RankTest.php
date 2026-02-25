<?php

use App\Enums\UserRole;
use App\Models\Rank;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('Rank Module', function(){
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
            'code' => fake()->bothify('??'),
            'short_name' => fake()->jobTitle(),
            'alias' =>  fake()->jobTitle(),
        ];
    });

    
    test('admin can access ranks page on system administrator layout', function () {
        actingAs($this->admin)
        ->get(route('system-administrator.ranks.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Ranks/Index')
        );
    });


    test('staff cannot access ranks page on administrator layout', function () {
        actingAs($this->staff)
        ->get(route('system-administrator.ranks.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can create new role on ranks page on system administrator layout', function () {
        actingAs($this->admin)
        ->post(route('system-administrator.ranks.store'), $this->fakeData)
        ->assertStatus(200);
        
        $this->assertDatabaseHas('ranks', $this->fakeData);
    });

    test('staff cannot create new role on ranks page on system administrator layout', function () {
        actingAs($this->staff)
        ->post(route('system-administrator.ranks.store'), $this->fakeData)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('ranks', $this->fakeData);
    });

    test('admin can see ranks in the list on ranks page on system administrator layout', function () {
        Rank::factory(10)->create();
        
        actingAs($this->admin)
        ->get(route('system-administrator.ranks.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Ranks/Index')
            ->has('ranks.data', 10)
        );
    });

    test('staff cannot see ranks in the list on ranks page on system administrator layout', function () {
        Rank::factory(10)->create();

        actingAs($this->staff)
        ->get(route('system-administrator.ranks.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can edit role on ranks page on system administrator layout', function () {
        $rank = Rank::factory()->create($this->fakeData);
        $updated = [
            'code' => fake()->randomLetter() . fake()->randomLetter(),
            'short_name' => fake()->jobTitle(),
            'alias' =>  fake()->jobTitle(),
        ];

        actingAs($this->admin)
        ->put(route('system-administrator.ranks.update', $rank), $updated)
        ->assertStatus(200);

        $this->assertDatabaseMissing('ranks', $this->fakeData);
        $this->assertDatabaseHas('ranks', $updated);
    });

    test('staff cannot edit role on ranks page on system administrator layout', function () {
        $rank = Rank::factory()->create($this->fakeData);
        $updated = [
            'code' => fake()->randomLetter() . fake()->randomLetter(),
            'short_name' => fake()->jobTitle(),
            'alias' =>  fake()->jobTitle(),
        ];

        actingAs($this->staff)
        ->put(route('system-administrator.ranks.update', $rank), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('ranks', $updated);
    });

    test('admin can delete role on ranks page on system administrator layout', function () {
        $rank = Rank::factory()->create($this->fakeData);

        actingAs($this->admin)
        ->delete(route('system-administrator.ranks.destroy', $rank))
        ->assertStatus(200);

        $this->assertDatabaseMissing('ranks', $this->fakeData);
    });

    test('staff cannot delete role on ranks page on system administrator layout', function () {
        $rank = Rank::factory()->create($this->fakeData);

        actingAs($this->staff)
        ->delete(route('system-administrator.ranks.destroy', $rank))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseHas('ranks', [
            'id' => $rank->id
        ]);
    });
});
