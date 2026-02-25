<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('Document Module', function(){
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
            'name' => fake()->jobTitle()
        ];
    });

    
    test('admin can access documents page on system administrator layout', function () {
        actingAs($this->admin)
        ->get(route('system-administrator.documents.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Documents/Index')
        );
    });


    test('staff cannot access documents page on administrator layout', function () {
        actingAs($this->staff)
        ->get(route('system-administrator.documents.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can create new document on documents page on system administrator layout', function () {
        actingAs($this->admin)
        ->post(route('system-administrator.documents.store'), $this->fakeData)
        ->assertStatus(200);
        
        $this->assertDatabaseHas('documents', $this->fakeData);
    });

    test('staff cannot create new document on documents page on system administrator layout', function () {
        actingAs($this->staff)
        ->post(route('system-administrator.documents.store'), $this->fakeData)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('documents', $this->fakeData);
    });

    test('admin can see documents in the list on documents page on system administrator layout', function () {
        Document::factory(10)->create();
        
        actingAs($this->admin)
        ->get(route('system-administrator.documents.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/Documents/Index')
            ->has('documents.data', 10)
        );
    });

    test('staff cannot see documents in the list on documents page on system administrator layout', function () {
        Document::factory(10)->create();

        actingAs($this->staff)
        ->get(route('system-administrator.documents.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can edit document on documents page on system administrator layout', function () {
        $document = Document::factory()->create($this->fakeData);
        $updated = [
            'name' => fake()->jobTitle()
        ];

        actingAs($this->admin)
        ->put(route('system-administrator.documents.update', $document), $updated)
        ->assertStatus(200);

        $this->assertDatabaseMissing('documents', $this->fakeData);
        $this->assertDatabaseHas('documents', $updated);
    });

    test('staff cannot edit document on documents page on system administrator layout', function () {
        $document = Document::factory()->create($this->fakeData);
        $updated = [
            'name' => fake()->jobTitle()
        ];

        actingAs($this->staff)
        ->put(route('system-administrator.documents.update', $document), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('documents', $updated);
    });

    test('admin can delete document on documents page on system administrator layout', function () {
        $document = Document::factory()->create($this->fakeData);

        actingAs($this->admin)
        ->delete(route('system-administrator.documents.destroy', $document))
        ->assertStatus(200);

        $this->assertDatabaseMissing('documents', $this->fakeData);
    });

    test('staff cannot delete document on documents page on system administrator layout', function () {
        $document = Document::factory()->create($this->fakeData);

        actingAs($this->staff)
        ->delete(route('system-administrator.documents.destroy', $document))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseHas('documents', [
            'id' => $document->id
        ]);
    });
});
