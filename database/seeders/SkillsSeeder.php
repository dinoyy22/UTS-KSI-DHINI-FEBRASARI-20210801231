<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find admin user to update with skills
        $user = User::where('is_admin', 0)->first();

        if ($user) {
            $user->skills = [
                [
                    'title' => 'Teamwork',
                    'description' => 'Collaborating effectively with others to achieve common goals.',
                ],
                [
                    'title' => 'Communication',
                    'description' => 'Clearly conveying ideas and information to students and colleagues.',
                ],
                [
                    'title' => 'Research',
                    'description' => 'Conducting thorough academic research to stay current in the field.',
                ],
                [
                    'title' => 'Problem Solving',
                    'description' => 'Analyzing complex issues and developing creative solutions.',
                ],
            ];

            $user->save();

            $this->command->info('Skills have been added to the user.');
        } else {
            $this->command->error('No non-admin user found to update with skills.');
        }
    }
}
