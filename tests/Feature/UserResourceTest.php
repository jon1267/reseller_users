<?php


use App\Filament\Resources\ResellerUsers\Pages\CreateResellerUser;
use App\Filament\Resources\ResellerUsers\Pages\ListResellerUsers;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // create default commands (tenants) for tests
    }

    public function test_restricts_access_to_reset_password_action_based_on_role()
    {
        $team = Team::factory()->create();
        $admin = User::factory()->create(['role' => 'reseller_admin']);
        $agent = User::factory()->create(['role' => 'reseller_agent']);

        $admin->teams()->attach($team);
        $agent->teams()->attach($team);

        $targetUser = User::factory()->create();
        $targetUser->teams()->attach($team);

        // 1. Check, that Admin see the action
        $this->actingAs($admin);
        Filament::setTenant($team);

        Livewire::test(ListResellerUsers::class, ['tenant' => $team->getKey()])
            ->assertTableActionVisible('resetPassword', $targetUser);

        // 2. Check, that Agent can't see the action'
        $this->actingAs($agent);
        // Change tenant for user context as well
        Filament::setTenant($team);

        Livewire::test(ListResellerUsers::class, ['tenant' => $team->getKey()])
            ->assertTableActionHidden('resetPassword', $targetUser);
    }

    public function test_validates_form_on_user_creation()
    {
        $team = Team::factory()->create();
        $admin = User::factory()->create(['role' => 'reseller_admin']);
        $admin->teams()->attach($team);

        $this->actingAs($admin);
        Filament::setTenant($team);

        Livewire::test(CreateResellerUser::class)
            ->fillForm([
                'name' => '',
                'email' => 'not-an-email',
                'role' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'email',
                'role' => 'required',
            ]);
    }

    public function test_successfully_resets_user_password()
    {
        $team = Team::factory()->create();
        $admin = User::factory()->create(['role' => 'reseller_admin']);
        $admin->teams()->attach($team);

        $targetUser = User::factory()->create([
            'password' => Hash::make('old-password')
        ]);
        $targetUser->teams()->attach($team);

        $this->actingAs($admin);
        Filament::setTenant($team);

        Livewire::test(ListResellerUsers::class, ['tenant' => $team->getKey()])
            ->mountTableAction('resetPassword', $targetUser)
            ->setTableActionData([
                'new_password' => 'new-secure-password',
                'new_password_confirmation' => 'new-secure-password',
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        // check that password was updated
        $this->assertTrue(Hash::check('new-secure-password', $targetUser->refresh()->password));
    }

    public function test_users_cannot_access_other_tenants_data()
    {
        // create two teams
        $teamA = Team::factory()->create(['name' => 'Team A']);
        $teamB = Team::factory()->create(['name' => 'Team B']);

        // create admin in Team A
        $adminA = User::factory()->create([
            'role' => 'reseller_admin',
            'tenant_id' => 1,
        ]);
        $adminA->teams()->attach($teamA);

        // create reseller_agent in Team B
        $userInTeamB = User::factory()->create([
            'name' => 'Secret User B',
            'role' => 'reseller_agent',
            'tenant_id' => 2,
        ]);
        $userInTeamB->teams()->attach($teamB);

        // 1. Check agent from Team B don't see data from Team A
        $this->actingAs($userInTeamB);

        // Try get page for other tenant via URL
        $this->get(ListResellerUsers::getUrl(['tenant' => $teamA]))
            ->assertStatus(404);


        // 2. Check that user from Team B don't see data from Team A
        $this->actingAs($adminA);

        // Try get page for other tenant via URL
        $this->get(ListResellerUsers::getUrl(['tenant' => $teamB]))
            ->assertStatus(404);
    }

}
