<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Programming Languages
            ['name' => 'PHP', 'category' => 'Programming Language'],
            ['name' => 'JavaScript', 'category' => 'Programming Language'],
            ['name' => 'Python', 'category' => 'Programming Language'],
            ['name' => 'Java', 'category' => 'Programming Language'],
            ['name' => 'C#', 'category' => 'Programming Language'],
            ['name' => 'Ruby', 'category' => 'Programming Language'],
            ['name' => 'Go', 'category' => 'Programming Language'],
            ['name' => 'TypeScript', 'category' => 'Programming Language'],

            // Frameworks
            ['name' => 'Laravel', 'category' => 'Framework'],
            ['name' => 'React', 'category' => 'Framework'],
            ['name' => 'Vue.js', 'category' => 'Framework'],
            ['name' => 'Angular', 'category' => 'Framework'],
            ['name' => 'Node.js', 'category' => 'Framework'],
            ['name' => 'Django', 'category' => 'Framework'],
            ['name' => 'Spring Boot', 'category' => 'Framework'],
            ['name' => 'Express.js', 'category' => 'Framework'],

            // Databases
            ['name' => 'MySQL', 'category' => 'Database'],
            ['name' => 'PostgreSQL', 'category' => 'Database'],
            ['name' => 'MongoDB', 'category' => 'Database'],
            ['name' => 'Redis', 'category' => 'Database'],
            ['name' => 'Elasticsearch', 'category' => 'Database'],

            // DevOps
            ['name' => 'Docker', 'category' => 'DevOps'],
            ['name' => 'Kubernetes', 'category' => 'DevOps'],
            ['name' => 'AWS', 'category' => 'DevOps'],
            ['name' => 'CI/CD', 'category' => 'DevOps'],
            ['name' => 'Git', 'category' => 'DevOps'],

            // Other Skills
            ['name' => 'REST API', 'category' => 'General'],
            ['name' => 'GraphQL', 'category' => 'General'],
            ['name' => 'Microservices', 'category' => 'Architecture'],
            ['name' => 'TDD', 'category' => 'Methodology'],
            ['name' => 'Agile', 'category' => 'Methodology'],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(
                ['name' => $skill['name']],
                ['category' => $skill['category']]
            );
        }
    }
}
