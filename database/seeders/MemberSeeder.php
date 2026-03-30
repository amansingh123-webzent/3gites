<?php

namespace Database\Seeders;

use App\Models\Birthday;
use App\Models\Profile;
use App\Models\Tribute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    /**
     * Dummy member data.
     * Format: [name, email, status, account_locked]
     * Replace names/emails with real data before production deploy.
     */
    private array $members = [
        // ── 29 ACTIVE members ──────────────────────────────────────────────
        ['Alice Beaumont',     'alice.beaumont@example.com',     'active',    false],
        ['Bernard Chauvet',    'bernard.chauvet@example.com',    'active',    false],
        ['Céleste Dubois',     'celeste.dubois@example.com',     'active',    false],
        ['Denis Fournier',     'denis.fournier@example.com',     'active',    false],
        ['Eliane Girard',      'eliane.girard@example.com',      'active',    false],
        ['François Hubert',    'francois.hubert@example.com',    'active',    false],
        ['Geneviève Imbert',   'genevieve.imbert@example.com',   'active',    false],
        ['Henri Joliet',       'henri.joliet@example.com',       'active',    false],
        ['Isabelle Kervern',   'isabelle.kervern@example.com',   'active',    false],
        ['Jacques Lemaire',    'jacques.lemaire@example.com',    'active',    false],
        ['Karine Morin',       'karine.morin@example.com',       'active',    false],
        ['Laurent Noel',       'laurent.noel@example.com',       'active',    false],
        ['Madeleine Olivier',  'madeleine.olivier@example.com',  'active',    false],
        ['Nicolas Perrin',     'nicolas.perrin@example.com',     'active',    false],
        ['Odette Quentin',     'odette.quentin@example.com',     'active',    false],
        ['Pierre Renard',      'pierre.renard@example.com',      'active',    false],
        ['Quentin Savard',     'quentin.savard@example.com',     'active',    false],
        ['Renée Tessier',      'renee.tessier@example.com',      'active',    false],
        ['Sylvain Ursini',     'sylvain.ursini@example.com',     'active',    false],
        ['Thérèse Vidal',      'therese.vidal@example.com',      'active',    false],
        ['Ulysse Weil',        'ulysse.weil@example.com',        'active',    false],
        ['Véronique Xavier',   'veronique.xavier@example.com',   'active',    false],
        ['William Ybert',      'william.ybert@example.com',      'active',    false],
        ['Xavière Zanier',     'xaviere.zanier@example.com',     'active',    false],
        ['Yannick Arnaud',     'yannick.arnaud@example.com',     'active',    false],
        ['Zoé Blanc',          'zoe.blanc@example.com',          'active',    false],
        ['André Clement',      'andre.clement@example.com',      'active',    false],
        ['Brigitte Dumas',     'brigitte.dumas@example.com',     'active',    false],
        ['Claude Etienne',     'claude.etienne@example.com',     'active',    false],

        // ── 13 SEARCHING members ──────────────────────────────────────────
        // Accounts are locked until admin activates them
        ['Daniel Faure',       'daniel.faure@example.com',       'searching', true],
        ['Estelle Gros',       'estelle.gros@example.com',       'searching', true],
        ['Fabien Hamelin',     'fabien.hamelin@example.com',     'searching', true],
        ['Ghislaine Isnard',   'ghislaine.isnard@example.com',   'searching', true],
        ['Hervé Juge',         'herve.juge@example.com',         'searching', true],
        ['Irène Kahn',         'irene.kahn@example.com',         'searching', true],
        ['Joseph Lambert',     'joseph.lambert@example.com',     'searching', true],
        ['Laure Millet',       'laure.millet@example.com',       'searching', true],
        ['Marcel Nourry',      'marcel.nourry@example.com',      'searching', true],
        ['Nadine Oudin',       'nadine.oudin@example.com',       'searching', true],
        ['Olivier Perin',      'olivier.perin@example.com',      'searching', true],
        ['Paulette Remy',      'paulette.remy@example.com',      'searching', true],
        ['Robert Soleil',      'robert.soleil@example.com',      'searching', true],

        // ── 8 DECEASED members ────────────────────────────────────────────
        // No password, account_locked=true (they can never log in)
        ['Sophie Talon',       null,                              'deceased',  true],
        ['Thomas Ulmer',       null,                              'deceased',  true],
        ['Ursula Varin',       null,                              'deceased',  true],
        ['Victor Warnet',      null,                              'deceased',  true],
        ['Wendy Aubert',       null,                              'deceased',  true],
        ['Xavier Bonnet',      null,                              'deceased',  true],
        ['Yvette Caron',       null,                              'deceased',  true],
        ['Zacharie Delon',     null,                              'deceased',  true],
    ];

    // Birth year/death year ranges for deceased (dummy data)
    private array $deceasedYears = [
        ['Sophie Talon',    1957, 2018],
        ['Thomas Ulmer',    1957, 2015],
        ['Ursula Varin',    1958, 2020],
        ['Victor Warnet',   1957, 2019],
        ['Wendy Aubert',    1958, 2022],
        ['Xavier Bonnet',   1957, 2016],
        ['Yvette Caron',    1958, 2021],
        ['Zacharie Delon',  1957, 2023],
    ];

    public function run(): void
    {
        $defaultPassword = Hash::make('Welcome1975!'); // Members change on first login
        $deceasedIndex = 0;

        foreach ($this->members as $index => [$name, $email, $status, $locked]) {

            // Generate unique email for deceased (null email in array = no real email)
            $userEmail = $email ?? Str::slug($name, '.') . '@deceased.3gites.org';

            $user = User::firstOrCreate(
                ['email' => $userEmail],
                [
                    'name'           => $name,
                    'password'       => ($status === 'deceased') ? null : $defaultPassword,
                    'member_status'  => $status,
                    'account_locked' => $locked,
                    'must_change_password' => $status === 'active',
                ]
            );

            $user->assignRole($status === 'active' ? 'active_member' : 'active_member');
            // Note: searching members get active_member role pre-assigned
            // but account_locked prevents login until admin activates

            // Create blank profile
            Profile::firstOrCreate(['user_id' => $user->id]);

            // Seed a birthday for active/searching members (month + day only, no year)
            if ($status !== 'deceased') {
                Birthday::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'birth_month' => rand(1, 12),
                        'birth_day'   => rand(1, 28), // max 28 to avoid invalid dates
                        'birth_year'  => null,        // year not required publicly
                    ]
                );
            }

            // Create tribute page for deceased members
            if ($status === 'deceased') {
                $years = collect($this->deceasedYears)
                    ->firstWhere(0, $name);

                Tribute::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'member_name'   => $name,
                        'birth_year'    => $years[1] ?? 1957,
                        'death_year'    => $years[2] ?? 2020,
                        'tribute_text'  => "In loving memory of {$name}, a cherished member of the Class of 1975. "
                            . "Their laughter, wisdom, and friendship enriched the lives of all who knew them. "
                            . "They remain forever in our hearts.",
                        'photo'         => null,
                    ]
                );
            }
        }

        $this->command->info('✅ 50 members seeded (29 active, 13 searching, 8 deceased).');
        $this->command->warn('📝 Default password for active members: Welcome1975!');
        $this->command->warn('📝 Remind members to change password on first login.');
    }
}
