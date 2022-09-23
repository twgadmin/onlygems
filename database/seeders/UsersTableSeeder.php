<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profile = new Profile();
        $adminRole = Role::whereName('Admin')->first();
        $userRole = Role::whereName('User')->first();

        // Seed test admin
        $seededAdminEmail = 'superadmin@gmail.com';
        $user = User::where('email', '=', $seededAdminEmail)->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => 'Super Admin',
                'first_name'                     => 'Super',
                'last_name'                      => 'Admin',
                'email'                          => $seededAdminEmail,
                'password'                       => Hash::make('123456'),
                'phone'                          => '',
                'job_title'                      => '',
                'address'                        => '{"street":"1560 Warner Street","city":"Miami","state_abbr":"FL","state_full":"Florida","zip":"33169"}',
                'token'                          => str_random(64),
                'activated'                      => true,
                'signup_confirmation_ip_address' => '127.0.0.1',
                'admin_ip_address'               => '127.0.0.1',
            ]);

            $user->profile()->save($profile);
            $user->attachRole($adminRole);
            $user->save();
        }

        // Seed test user
        $user = User::where('email', '=', 'user@user.com')->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => 'Amy Granger',
                'first_name'                     => 'Amy',
                'last_name'                      => 'Granger',
                'email'                          => 'amy_granger@gmail.com',
                'password'                       => Hash::make('123456'),
                'phone'                          => '',
                'job_title'                      => '',
                'address'                        => '{"street":"1036 Crestview Terrace","city":"Victoria","state_abbr":"TX","state_full":"Texas","zip":"77901"}',
                'token'                          => str_random(64),
                'activated'                      => true,
                'signup_ip_address'              => '127.0.0.1',
                'signup_confirmation_ip_address' => '127.0.0.1',
            ]);

            $user->profile()->save(new Profile());
            $user->attachRole($userRole);
            $user->save();
        }

        // Seed test users
        // $user = factory(App\Models\Profile::class, 5)->create();
        // $users = User::All();
        // foreach ($users as $user) {
        //     if (!($user->isAdmin()) && !($user->isUnverified())) {
        //         $user->attachRole($userRole);
        //     }
        // }
    }
}
