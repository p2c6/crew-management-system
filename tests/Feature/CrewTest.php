<?php

use App\Enums\UserRole;
use App\Jobs\ImportCrewJob;
use App\Models\Crew;
use App\Models\Rank;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;


uses(RefreshDatabase::class);

describe('Crew Module', function () {
    beforeEach(function () {
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
            'rank_id' => Rank::factory()->create()->id,
            'first_name' => fake()->firstName(),
            'middle_name' =>  fake()->firstName(),
            'last_name' =>  fake()->lastName(),
            'address' => fake()->address(),
            'birth_date' => fake()->date(),
            'email' => fake()->email(),
            'weight' => fake()->numberBetween(10, 999),
            'height' => fake()->numberBetween(100, 999),
        ];
    });

    test('staff can access crew page on staff layout', function () {
        actingAs($this->staff)
            ->get(route('staff.crews.index'))
            ->assertStatus(200)
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('Staff/Crews/Index')
            );
    });

    test('admin cannot access crew page on staff layout', function () {
        actingAs($this->admin)
            ->get(route('staff.crews.index'))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');
    });

    test('admin can access crew page on admin layout', function () {
        actingAs($this->admin)
            ->get(route('system-administrator.crews.index'))
            ->assertStatus(200)
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('SystemAdministrator/Crews/Index')
            );
    });

    test('staff cannot access crew page on admin layout', function () {
        actingAs($this->staff)
            ->get(route('system-administrator.crews.index'))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');
    });

    test('admin can access create crew page on admin layout', function () {
        actingAs($this->admin)
            ->get(route('system-administrator.crews.create'))
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('SystemAdministrator/Crews/Create')
            );
    });

    test('staff cannot access create page on admin layout', function () {
        actingAs($this->staff)
            ->get(route('system-administrator.crews.create'))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');
    });

    test('staff can access create crew page on staff layout', function () {
        actingAs($this->staff)
            ->get(route('staff.crews.create'))
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('Staff/Crews/Create')
            );
    });

    test('admin cannot access create page on staff layout', function () {
        actingAs($this->admin)
            ->get(route('staff.crews.create'))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');
    });

    test('staff can store crew on crew page on staff layout', function () {
        actingAs($this->staff)
            ->post(route('staff.crews.store'), $this->fakeData)
            ->assertStatus(302)
            ->assertRedirect();
    });

    test('admin cannot store crew on crew page on staff layout', function () {
        actingAs($this->admin)
            ->post(route('staff.crews.store'), $this->fakeData)
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('crews', $this->fakeData);
    });

    test('admin can store crew on crew page on admin layout', function () {
        actingAs($this->admin)
            ->post(route('system-administrator.crews.store'), $this->fakeData)
            ->assertStatus(302)
            ->assertRedirect();
    });

    test('staff cannot store crew on crew page on admin layout', function () {
        actingAs($this->staff)
            ->post(route('system-administrator.crews.store'), $this->fakeData)
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('crews', $this->fakeData);
    });

    test('admin can access edit crew on crew page on admin layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->admin)
            ->get(route('system-administrator.crews.edit', $crew->id))
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('SystemAdministrator/Crews/Edit')
            );
    });

    test('staff cannot access edit crew on crew page on admin layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->staff)
            ->get(route('system-administrator.crews.edit', $crew->id))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');
    });

    test('staff can access edit crew on crew page on staff layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->staff)
            ->get(route('staff.crews.edit', $crew->id))
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('Staff/Crews/Edit')
            );
    });

    test('admin cannot access edit crew on crew page on staff layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->admin)
            ->get(route('staff.crews.edit', $crew->id))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');
    });

    test('admin can update crew on crew page on admin layout', function () {
        $crew = Crew::create($this->fakeData);
        $updated = [
            'rank_id' => Rank::factory()->create()->id,
            'first_name' => fake()->firstName(),
            'middle_name' =>  fake()->firstName(),
            'last_name' =>  fake()->lastName(),
            'address' => fake()->address(),
            'birth_date' => fake()->date(),
            'email' => fake()->email(),
            'weight' => fake()->numberBetween(10, 999),
            'height' => fake()->numberBetween(100, 999),
        ];

        actingAs($this->admin)
            ->put(route('system-administrator.crews.update', $crew), $updated)
            ->assertStatus(302)
            ->assertRedirect();

        $this->assertDatabaseMissing('crews', $this->fakeData);
        $this->assertDatabaseHas('crews', $updated);
    });

    test('staff cannot update crew on crew page on admin layout', function () {
        $crew = Crew::create($this->fakeData);
        $updated = [
            'rank_id' => Rank::factory()->create()->id,
            'first_name' => fake()->firstName(),
            'middle_name' =>  fake()->firstName(),
            'last_name' =>  fake()->lastName(),
            'address' => fake()->address(),
            'birth_date' => fake()->date(),
            'email' => fake()->email(),
            'weight' => fake()->numberBetween(10, 999),
            'height' => fake()->numberBetween(100, 999),
        ];

        actingAs($this->staff)
            ->put(route('system-administrator.crews.update', $crew), $updated)
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('crews', $updated);
        $this->assertDatabaseHas('crews', $this->fakeData);
    });

    test('staff can update crew on crew page on staff layout', function () {
        $crew = Crew::create($this->fakeData);
        $updated = [
            'rank_id' => Rank::factory()->create()->id,
            'first_name' => fake()->firstName(),
            'middle_name' =>  fake()->firstName(),
            'last_name' =>  fake()->lastName(),
            'address' => fake()->address(),
            'birth_date' => fake()->date(),
            'email' => fake()->email(),
            'weight' => fake()->numberBetween(10, 999),
            'height' => fake()->numberBetween(100, 999),
        ];

        actingAs($this->staff)
            ->put(route('staff.crews.update', $crew), $updated)
            ->assertStatus(302)
            ->assertRedirect();

        $this->assertDatabaseMissing('crews', $this->fakeData);
        $this->assertDatabaseHas('crews', $updated);
    });

    test('admin cannot update crew on crew page on staff layout', function () {
        $crew = Crew::create($this->fakeData);
        $updated = [
            'rank_id' => Rank::factory()->create()->id,
            'first_name' => fake()->firstName(),
            'middle_name' =>  fake()->firstName(),
            'last_name' =>  fake()->lastName(),
            'address' => fake()->address(),
            'birth_date' => fake()->date(),
            'email' => fake()->email(),
            'weight' => fake()->numberBetween(10, 999),
            'height' => fake()->numberBetween(100, 999),
        ];

        actingAs($this->admin)
            ->put(route('staff.crews.update', $crew), $updated)
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseMissing('crews', $updated);
        $this->assertDatabaseHas('crews', $this->fakeData);
    });

    test('admin can delete crew on crew page on admin layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->admin)
            ->delete(route('system-administrator.crews.destroy', $crew))
            ->assertStatus(200);

        $this->assertDatabaseMissing('crews', $this->fakeData);
    });

    test('staff cannot delete crew on crew page on admin layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->staff)
            ->delete(route('system-administrator.crews.destroy', $crew))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseHas('crews', $this->fakeData);
    });

    test('staff can delete crew on crew page on staff layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->staff)
            ->delete(route('staff.crews.destroy', $crew))
            ->assertStatus(200);

        $this->assertDatabaseMissing('crews', $this->fakeData);
    });

    test('admin cannot delete crew on crew page on staff layout', function () {
        $crew = Crew::create($this->fakeData);

        actingAs($this->admin)
            ->delete(route('staff.crews.destroy', $crew))
            ->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');

        $this->assertDatabaseHas('crews', $this->fakeData);
    });

    test('admin can bulk upload crews and dispatch jobs on admin layout', function () {
        Queue::fake();

        $this->actingAs($this->admin);

        $csvContent = "rank_id,first_name,middle_name,last_name,address,birth_date,email,weight,height
        1,John,A,Doe,Manila,1995-05-10,john@example.com,70,175
        5,Jane,B,Smith,Quezon City,1998-08-20,jane@example.com,60,165
        12,Mark,C,Reyes,Cebu City,1992-03-15,mark@example.com,80,180
        18,Ana,D,Cruz,Davao City,1996-11-25,ana@example.com,55,160";

        $file = UploadedFile::fake()->createWithContent(
            'crews.csv',
            $csvContent
        );

        $response = $this->post(route('system-administrator.crews.bulk-upload'), [
            'file' => $file
        ]);

        $response->assertRedirect();

        Queue::assertPushed(ImportCrewJob::class, function ($job) {
            return count($job->rows) > 0;
        });
    });

    test('staff cannot bulk upload crews and dispatch jobs on admin layout', function () {
        Queue::fake();

        $this->actingAs($this->staff);

        $csvContent = "rank_id,first_name,middle_name,last_name,address,birth_date,email,weight,height
        1,John,A,Doe,Manila,1995-05-10,john@example.com,70,175
        5,Jane,B,Smith,Quezon City,1998-08-20,jane@example.com,60,165
        12,Mark,C,Reyes,Cebu City,1992-03-15,mark@example.com,80,180
        18,Ana,D,Cruz,Davao City,1996-11-25,ana@example.com,55,160";

        $file = UploadedFile::fake()->createWithContent(
            'crews.csv',
            $csvContent
        );

        $response = $this->post(route('system-administrator.crews.bulk-upload'), [
            'file' => $file
        ]);

        $response->assertStatus(403)
            ->assertSee('You are not allowed to access this resource');


        Queue::assertNothingPushed(ImportCrewJob::class, function ($job) {
            return count($job->rows) > 0;
        });
    });
});
