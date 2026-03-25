<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use App\Models\DocumentType;
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

    
    test('admin can access document types page on system administrator layout', function () {
        actingAs($this->admin)
        ->get(route('system-administrator.document-types.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/DocumentTypes/Index')
        );
    });


    test('staff cannot access document types page on administrator layout', function () {
        actingAs($this->staff)
        ->get(route('system-administrator.document-types.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can create new document on document types page on system administrator layout', function () {
        actingAs($this->admin)
        ->post(route('system-administrator.document-types.store'), $this->fakeData)
        ->assertStatus(200);
        
        $this->assertDatabaseHas('document_types', $this->fakeData);
    });

    test('staff cannot create new document on document types page on system administrator layout', function () {
        actingAs($this->staff)
        ->post(route('system-administrator.document-types.store'), $this->fakeData)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('document_types', $this->fakeData);
    });

    test('admin can see document types in the list on document types page on system administrator layout', function () {
        DocumentType::factory(10)->create();
        
        actingAs($this->admin)
        ->get(route('system-administrator.document-types.index'))
        ->assertStatus(200)
        ->assertInertia(fn(Assert $page) =>
            $page->component('SystemAdministrator/DocumentTypes/Index')
            ->has('document_types.data', 10)
        );
    });

    test('staff cannot see document types in the list on document types page on system administrator layout', function () {
        DocumentType::factory(10)->create();

        actingAs($this->staff)
        ->get(route('system-administrator.document-types.index'))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');
    });

    test('admin can edit document on document types page on system administrator layout', function () {
        $documentType = DocumentType::factory()->create($this->fakeData);
        $updated = [
            'name' => fake()->jobTitle()
        ];

        actingAs($this->admin)
        ->put(route('system-administrator.document-types.update', $documentType), $updated)
        ->assertStatus(200);

        $this->assertDatabaseMissing('document_types', $this->fakeData);
        $this->assertDatabaseHas('document_types', $updated);
    });

    test('staff cannot edit document on document types page on system administrator layout', function () {
        $documentType = DocumentType::factory()->create($this->fakeData);
        $updated = [
            'name' => fake()->jobTitle()
        ];

        actingAs($this->staff)
        ->put(route('system-administrator.document-types.update', $documentType), $updated)
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('document_types', $updated);
    });

    test('admin can delete document on document types page on system administrator layout', function () {
        $documentType = DocumentType::factory()->create($this->fakeData);

        actingAs($this->admin)
        ->delete(route('system-administrator.document-types.destroy', $documentType))
        ->assertStatus(200);

        $this->assertDatabaseMissing('document_types', $this->fakeData);
    });

    test('staff cannot delete document on document types page on system administrator layout', function () {
        $documentType = DocumentType::factory()->create($this->fakeData);

        actingAs($this->staff)
        ->delete(route('system-administrator.document-types.destroy', $documentType))
        ->assertStatus(403)
        ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseHas('document_types', [
            'id' => $documentType->id
        ]);
    });
});
