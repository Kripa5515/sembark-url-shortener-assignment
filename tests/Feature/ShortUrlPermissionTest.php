<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShortUrlPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_short_url(): void
    {
        $company = Company::create([
            'name' => 'Company A'
        ]);

        $role = Role::create([
            'name' => 'Admin'
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($user)
            ->post('/short-urls', [
                'original_url' => 'https://google.com'
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://google.com'
        ]);
    }

    public function test_member_can_create_short_url(): void
    {
        $company = Company::create([
            'name' => 'Company A'
        ]);

        $role = Role::create([
            'name' => 'Member'
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($user)
            ->post('/short-urls', [
                'original_url' => 'https://github.com'
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://github.com'
        ]);
    }

    public function test_superadmin_cannot_create_short_url(): void
    {
        $company = Company::create([
            'name' => 'Company A'
        ]);

        $role = Role::create([
            'name' => 'SuperAdmin'
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/short-urls/create');

        $response->assertForbidden();
    }

    public function test_superadmin_can_see_all_company_urls(): void
    {
        $companyA = Company::create([
            'name' => 'Company A'
        ]);

        $companyB = Company::create([
            'name' => 'Company B'
        ]);

        $superAdminRole = Role::create([
            'name' => 'SuperAdmin'
        ]);

        $salesRole = Role::create([
            'name' => 'Sales'
        ]);

        $superAdmin = User::factory()->create([
            'role_id' => $superAdminRole->id,
            'company_id' => null,
        ]);

        $salesA = User::factory()->create([
            'company_id' => $companyA->id,
            'role_id' => $salesRole->id,
        ]);

        $salesB = User::factory()->create([
            'company_id' => $companyB->id,
            'role_id' => $salesRole->id,
        ]);

        ShortUrl::create([
            'company_id' => $companyA->id,
            'created_by' => $salesA->id,
            'original_url' => 'https://company-a.com',
            'short_code' => 'AAAA1111'
        ]);

        ShortUrl::create([
            'company_id' => $companyB->id,
            'created_by' => $salesB->id,
            'original_url' => 'https://company-b.com',
            'short_code' => 'BBBB2222'
        ]);

        $response = $this->actingAs($superAdmin)
            ->get('/short-urls');

        $response->assertSee('AAAA1111');
        $response->assertSee('BBBB2222');
    }

    public function test_admin_can_only_see_own_company_urls(): void
    {
        $companyA = Company::create([
        'name' => 'Company A'
        ]);

        $companyB = Company::create([
            'name' => 'Company B'
        ]);

        $adminRole = Role::create([
            'name' => 'Admin'
        ]);

        $salesRole = Role::create([
            'name' => 'Sales'
        ]);

        $admin = User::factory()->create([
            'company_id' => $companyA->id,
            'role_id' => $adminRole->id,
        ]);

        $salesA = User::factory()->create([
            'company_id' => $companyA->id,
            'role_id' => $salesRole->id,
        ]);

        $salesB = User::factory()->create([
            'company_id' => $companyB->id,
            'role_id' => $salesRole->id,
        ]);

        ShortUrl::create([
            'company_id' => $companyA->id,
            'created_by' => $salesA->id,
            'original_url' => 'https://company-a.com',
            'short_code' => 'AAAA1111'
        ]);

        ShortUrl::create([
            'company_id' => $companyB->id,
            'created_by' => $salesB->id,
            'original_url' => 'https://company-b.com',
            'short_code' => 'BBBB2222'
        ]);

        $response = $this->actingAs($admin)
            ->get('/short-urls');

        $response->assertSee('AAAA1111');
        $response->assertDontSee('BBBB2222');

    }

    public function test_member_can_see_only_own_created_urls(): void
    {
        $company = Company::create([
        'name' => 'Company A'
        ]);

        $memberRole = Role::create([
            'name' => 'Member'
        ]);

        $salesRole = Role::create([
            'name' => 'Sales'
        ]);

        $member = User::factory()->create([
            'company_id' => $company->id,
            'role_id' => $memberRole->id,
        ]);

        $sales = User::factory()->create([
            'company_id' => $company->id,
            'role_id' => $salesRole->id,
        ]);

        ShortUrl::create([
            'company_id' => $company->id,
            'created_by' => $member->id,
            'original_url' => 'https://own-url.com',
            'short_code' => 'OWN11111'
        ]);

        ShortUrl::create([
            'company_id' => $company->id,
            'created_by' => $sales->id,
            'original_url' => 'https://other-url.com',
            'short_code' => 'OTH22222'
        ]);

        $response = $this->actingAs($member)
            ->get('/short-urls');

        $response->assertSee('OWN11111');
        $response->assertDontSee('OTH22222');
        
    }
    
    public function test_short_urls_are_not_publicly_resolvable(): void
    {
        $response = $this->get('/ABC12345');
        $response->assertStatus(404);
    }

}
