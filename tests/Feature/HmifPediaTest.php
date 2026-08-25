<?php

namespace Tests\Feature;

use App\Filament\Resources\HmifPedias\HmifPediaResource;
use App\Models\Divisi;
use App\Models\HmifPedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HmifPediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_pao_and_superadmin_are_authorized_for_every_crud_operation(): void
    {
        $record = HmifPedia::create([
            'judul' => 'Buku HMIF',
            'file_pdf' => 'hmif-pedia/buku.pdf',
            'is_active' => true,
        ]);

        foreach (['pao', 'superadmin'] as $singkatan) {
            $this->actingAs($this->userForDivision($singkatan));

            $this->assertTrue(HmifPediaResource::canViewAny());
            $this->assertTrue(HmifPediaResource::canView($record));
            $this->assertTrue(HmifPediaResource::canCreate());
            $this->assertTrue(HmifPediaResource::canEdit($record));
            $this->assertTrue(HmifPediaResource::canDelete($record));
        }

        foreach (['kominfo', null] as $singkatan) {
            $user = $singkatan ? $this->userForDivision($singkatan) : $this->userForDivision(null);
            $this->actingAs($user);

            $this->assertFalse(HmifPediaResource::canViewAny());
            $this->assertFalse(HmifPediaResource::canView($record));
            $this->assertFalse(HmifPediaResource::canCreate());
            $this->assertFalse(HmifPediaResource::canEdit($record));
            $this->assertFalse(HmifPediaResource::canDelete($record));

            $this->get('/admin/hmif-pedias')->assertForbidden();
            $this->get('/admin/hmif-pedias/create')->assertForbidden();
            $this->get("/admin/hmif-pedias/{$record->id}/edit")->assertForbidden();
        }
    }

    public function test_activating_a_record_deactivates_the_previous_record(): void
    {
        $first = HmifPedia::create([
            'judul' => 'Pertama',
            'file_pdf' => 'hmif-pedia/pertama.pdf',
            'is_active' => true,
        ]);

        $second = HmifPedia::create([
            'judul' => 'Kedua',
            'file_pdf' => 'hmif-pedia/kedua.pdf',
            'is_active' => true,
        ]);

        $this->assertFalse($first->fresh()->is_active);
        $this->assertTrue($second->fresh()->is_active);
        $this->assertSame(1, HmifPedia::where('is_active', true)->count());
    }

    public function test_public_api_returns_active_record_with_public_url(): void
    {
        HmifPedia::create([
            'judul' => 'Buku Aktif',
            'deskripsi' => 'Deskripsi buku',
            'file_pdf' => 'hmif-pedia/buku-aktif.pdf',
            'is_active' => true,
        ]);

        $this->getJson('/api/hmif-pedia')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.judul', 'Buku Aktif')
            ->assertJsonPath('data.file_pdf', 'hmif-pedia/buku-aktif.pdf')
            ->assertJsonStructure(['data' => ['pdf_url']]);
    }

    public function test_public_api_returns_null_when_there_is_no_active_record(): void
    {
        HmifPedia::create([
            'judul' => 'Buku Nonaktif',
            'file_pdf' => 'hmif-pedia/nonaktif.pdf',
            'is_active' => false,
        ]);

        $this->getJson('/api/hmif-pedia')
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'data' => null,
            ]);
    }

    public function test_public_pdf_endpoint_streams_the_active_pdf(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('hmif-pedia/aktif.pdf', '%PDF-1.4 test');

        HmifPedia::create([
            'judul' => 'Buku Aktif',
            'file_pdf' => 'hmif-pedia/aktif.pdf',
            'is_active' => true,
        ]);

        $this->get('/api/hmif-pedia/pdf')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_public_pdf_endpoint_returns_json_404_when_file_is_missing(): void
    {
        Storage::fake('public');

        HmifPedia::create([
            'judul' => 'Buku Hilang',
            'file_pdf' => 'hmif-pedia/hilang.pdf',
            'is_active' => true,
        ]);

        $this->getJson('/api/hmif-pedia/pdf')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    private function userForDivision(?string $singkatan): User
    {
        $divisi = $singkatan === null ? null : Divisi::create([
            'nama' => strtoupper($singkatan),
            'singkatan' => $singkatan,
        ]);

        return User::create([
            'nama' => 'User '.($singkatan ?? 'tanpa divisi'),
            'nim' => fake()->unique()->numerify('########'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'id_divisi' => $divisi?->id,
            'status_keanggotaan' => 'aktif',
        ]);
    }
}
