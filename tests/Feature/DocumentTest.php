<?php

use App\Enums\UserRole;
use App\Models\Crew;
use App\Models\Document;
use App\Models\Role;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\Rank;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('Document Module', function () {
    beforeEach(function () {
        Storage::fake('local');
        Storage::fake('private');

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

        $uuid = fake()->uuid();
        $fileName = $uuid . '.pdf';

        $rank = Rank::factory()->create();

        $this->crew = Crew::factory()->create(['rank_id' => $rank->id]);
        $documentType = DocumentType::factory()->create();

        $issuedDate = fake()->date();
        $expiryDate = Carbon::parse($issuedDate)->addDays(30)->format('Y-m-d');

        UploadedFile::fake()->create($fileName, 100);

        Storage::disk('local')->put("tmp/{$uuid}.pdf", 'fake content');

        $this->fakeData = [
            'crew_id' => $this->crew->id,
            'document_type_id' => $documentType->id,
            'user_id' => $this->admin->id,
            'file_name' =>  $fileName,
            'file_path' => 'documents/' . $fileName,
            'code' =>  Str::upper(fake()->bothify('???')),
            'issued_date' => $issuedDate,
            'expiry_date' => $expiryDate,
            'documents' => [$uuid]
        ];
    });

    test('admin can access crew documents page on system administrator layout', function () {
        actingAs($this->admin)
            ->get(route('system-administrator.crews.documents.index',  $this->crew))
            ->assertStatus(200)
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('SystemAdministrator/Crews/Edit')
            );
    });

    test('staff cannot access crew documents page on system administrator layout', function () {
        actingAs($this->staff)
            ->get(route('system-administrator.crews.documents.index',  $this->crew))
            ->assertStatus(403)
            ->assertSeeHtml('You are not allowed to access this resource');
    });

    test('admin can store crew documents page on system administrator layout', function () {
        actingAs($this->admin)
            ->post(route('system-administrator.crews.documents.store'), $this->fakeData)
            ->assertStatus(302)
            ->assertRedirectBack();

        Storage::disk('private')->assertExists($this->fakeData['file_path']);
        Storage::disk('local')->assertMissing('tmp/' . $this->fakeData['file_name']);

        $this->assertDatabaseHas('documents', [
            'crew_id'     => $this->fakeData['crew_id'],
            'file_path'   => $this->fakeData['file_path'],
            'file_name'   => $this->fakeData['file_name'],
        ]);
    });

    test('staff cannot store crew documents on crew documents page on system administrator layout', function () {
        actingAs($this->staff)
            ->post(route('system-administrator.crews.documents.store'), $this->fakeData)
            ->assertStatus(403)
            ->assertSeeHtml('You are not allowed to access this resource');

        $this->assertDatabaseMissing('documents', Arr::except($this->fakeData, 'documents'));
    });

    test('admin can update crew documents page on system administrator layout', function () {
        $crew = Crew::factory()->create();
        $document = Document::factory()->create([
            'crew_id' => $crew->id
        ]);

        $newUuid = fake()->uuid();
        $fileName = $newUuid . '.pdf';
        Storage::disk('local')->put("tmp/{$newUuid}.pdf", 'fake content');

        $issuedDate = fake()->date();
        $expiryDate = Carbon::parse($issuedDate)->addDays(30)->format('Y-m-d');

        $updatedDocument = [
            'crew_id'              => $document->crew_id,
            'document_type_id'     => $document->document_type_id,
            'user_id'              => $this->admin->id,
            'file_name'            => $fileName,
            'file_path'            => 'documents/' . $fileName,
            'code'                 => Str::upper(fake()->bothify('???')),
            'issued_date'          => $issuedDate,
            'expiry_date'          => $expiryDate,
            'documents'            => [$newUuid],
        ];

        actingAs($this->admin)
            ->put(route('system-administrator.crews.documents.update', $document), $updatedDocument)
            ->assertStatus(302)
            ->assertRedirectBack();

        Storage::disk('private')->assertExists('documents/' . $fileName);
        Storage::disk('local')->assertMissing('tmp/' . $fileName);

        $this->assertDatabaseHas('documents', [
            'id'               => $document->id,
            'crew_id'          => $document->crew_id,
            'document_type_id' => $document->document_type_id,
            'user_id'          => $this->admin->id,
            'code'             => $updatedDocument['code'],
            'file_path'        => 'documents/' . $fileName,
            'file_name'        => $fileName,
            'issued_date'      => $issuedDate,
            'expiry_date'      => $expiryDate,
        ]);
    });

    test('staff cannot update crew documents on crew documents page on system administrator layout', function () {
        $crew = Crew::factory()->create();
        $document = Document::factory()->create([
            'crew_id' => $crew->id
        ]);

        $newUuid = fake()->uuid();
        $fileName = $newUuid . '.pdf';
        Storage::disk('local')->put("tmp/{$newUuid}.pdf", 'fake content');

        $issuedDate = fake()->date();
        $expiryDate = Carbon::parse($issuedDate)->addDays(30)->format('Y-m-d');

        $updatedDocument =  [
            'crew_id' => $document->crew_id,
            'document_type_id' => $document->document_type_id,
            'user_id' => $this->staff->id,
            'file_name' =>  $fileName,
            'file_path' => 'documents/' . $fileName,
            'code' =>  Str::upper(fake()->bothify('???')),
            'issued_date' => $issuedDate,
            'expiry_date' => $expiryDate,
            'documents' => [$newUuid],
            'existing_document_id' => $document->id

        ];

        actingAs($this->staff)
            ->put(route('system-administrator.crews.documents.update', $document), $updatedDocument)
            ->assertStatus(403)
            ->assertSeeHtml('You are not allowed to access this resource');

        $this->assertDatabaseMissing('documents', [
            'id' => $document->id,
            'crew_id' => $document->crew_id,
            'document_type_id' => $document->document_type_id,
            'user_id' => $this->staff->id,
            'code' => $updatedDocument['code'],
            'issued_date' => $issuedDate,
            'expiry_date' => $expiryDate,
        ]);
    });

    test('admin can delete crew documents page on system administrator layout', function () {
        $crew = Crew::factory()->create();
        $document = Document::factory()->create([
            'crew_id' => $crew->id
        ]);

        actingAs($this->admin)
            ->delete(route('system-administrator.crews.documents.destroy', $document))
            ->assertStatus(302)
            ->assertRedirectBack();

        $this->assertDatabaseMissing('documents', $document->toArray());
    });

    test('staff cannot delete crew documents on crew documents page on system administrator layout', function () {
        $crew = Crew::factory()->create();
        $document = Document::factory()->create([
            'crew_id' => $crew->id
        ]);

        actingAs($this->staff)
            ->delete(route('system-administrator.crews.documents.destroy', $document))
            ->assertStatus(403)
            ->assertSeeHtml('You are not allowed to access this resource');

        $this->assertDatabaseHas('documents', $document->only([
            'id',
            'crew_id',
            'document_type_id',
            'user_id',
            'file_name',
            'file_path',
            'code',
            'issued_date',
            'expiry_date',
        ]));
    });

    test('admin can view crew documents on crew documents page on system administrator layout', function () {
        $user = actingAs($this->admin);

        $crew = Crew::factory()->create();
        $document = Document::factory()->create([
            'crew_id' => $crew->id
        ]);

        $uuid = fake()->uuid();
        $fileName = $uuid . '.pdf';
        Storage::disk('local')->put("tmp/{$uuid}.pdf", 'fake content');

        $issuedDate = fake()->date();
        $expiryDate = Carbon::parse($issuedDate)->addDays(30)->format('Y-m-d');

        $data = [
            'crew_id'              => $document->crew_id,
            'document_type_id'     => $document->document_type_id,
            'user_id'              => $this->admin->id,
            'file_name'            => $fileName,
            'file_path'            => 'documents/' . $fileName,
            'code'                 => Str::upper(fake()->bothify('???')),
            'issued_date'          => $issuedDate,
            'expiry_date'          => $expiryDate,
            'documents'            => [$uuid],
        ];

        $user->post(route('system-administrator.crews.documents.store'), $data);

        Storage::disk('private')->assertExists($data['file_path']);
        Storage::disk('local')->assertMissing('tmp/' . $data['file_name']);

        $document = Document::query()->where('file_name', $fileName)->firstOrFail();

        $user->get(route('system-administrator.crews.documents.view', $document))
            ->assertStatus(200);

        $this->assertEquals('pdf', explode(".", $fileName)[1]);
    });

    test('staff cannot view crew documents on crew documents page on system administrator layout', function () {
        $user = actingAs($this->staff);

        $crew = Crew::factory()->create();
        $document = Document::factory()->create([
            'crew_id' => $crew->id
        ]);

        $uuid = fake()->uuid();
        $fileName = $uuid . '.pdf';
        Storage::disk('local')->put("tmp/{$uuid}.pdf", 'fake content');

        $issuedDate = fake()->date();
        $expiryDate = Carbon::parse($issuedDate)->addDays(30)->format('Y-m-d');

        $data = [
            'crew_id'              => $document->crew_id,
            'document_type_id'     => $document->document_type_id,
            'user_id'              => $this->admin->id,
            'file_name'            => $fileName,
            'file_path'            => 'documents/' . $fileName,
            'code'                 => Str::upper(fake()->bothify('???')),
            'issued_date'          => $issuedDate,
            'expiry_date'          => $expiryDate,
            'documents'            => [$uuid],
        ];

        Document::create($data);

        $document = Document::query()->where('file_name', $fileName)->firstOrFail();

        $user->get(route('system-administrator.crews.documents.view', $document))
            ->assertStatus(403)
            ->assertSeeHtml('You are not allowed to access this resource');
    });
});
