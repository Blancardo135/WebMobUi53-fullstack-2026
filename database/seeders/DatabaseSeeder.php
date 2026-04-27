<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // ── Users ──────────────────────────────────────────
            DB::table('users')->insert([
                'id'         => 1,
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'username'   => 'johndoe',
                'email'      => 'john.doe@example.com',
                'password'   => Hash::make('password'),
                'created_at' => new \DateTime('2026-02-09 10:00:00'),
                'updated_at' => new \DateTime('2026-02-09 10:00:00'),
            ]);

            DB::table('users')->insert([
                'id'         => 2,
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'username'   => 'janedoe',
                'email'      => 'jane.doe@example.com',
                'password'   => Hash::make('password'),
                'created_at' => new \DateTime('2026-02-09 11:00:00'),
                'updated_at' => new \DateTime('2026-02-09 11:00:00'),
            ]);

            // ── Posts ──────────────────────────────────────────
            DB::table('posts')->insert([
                ['id' => 1, 'user_id' => 1, 'title' => "John's First Post",  'content' => "This is the content of John's first post.",  'created_at' => new \DateTime('2026-02-09 12:00:00'), 'updated_at' => new \DateTime('2026-02-09 12:00:00')],
                ['id' => 2, 'user_id' => 1, 'title' => null,                  'content' => "This is the content of John's second post.", 'created_at' => new \DateTime('2026-02-09 12:05:00'), 'updated_at' => new \DateTime('2026-02-09 12:05:00')],
                ['id' => 3, 'user_id' => 1, 'title' => null,                  'content' => "This is the content of John's third post.",  'created_at' => new \DateTime('2026-02-09 12:10:00'), 'updated_at' => new \DateTime('2026-02-09 12:10:00')],
                ['id' => 4, 'user_id' => 2, 'title' => null,                  'content' => "This is the content of Jane's first post.",  'created_at' => new \DateTime('2026-02-09 12:05:00'), 'updated_at' => new \DateTime('2026-02-09 12:05:00')],
                ['id' => 5, 'user_id' => 2, 'title' => "Jane's Second Post",  'content' => "This is the content of Jane's second post.", 'created_at' => new \DateTime('2026-02-09 12:10:00'), 'updated_at' => new \DateTime('2026-02-09 12:10:00')],
                ['id' => 6, 'user_id' => 2, 'title' => "Jane's Third Post",   'content' => "This is the content of Jane's third post.",  'created_at' => new \DateTime('2026-02-09 12:15:00'), 'updated_at' => new \DateTime('2026-02-09 12:15:00')],
            ]);

            // ── Likes ──────────────────────────────────────────
            DB::table('likes')->insert([
                ['user_id' => 2, 'post_id' => 1, 'reaction' => 'like', 'created_at' => new \DateTime('2026-02-09 12:20:00'), 'updated_at' => new \DateTime('2026-02-09 12:20:00')],
                ['user_id' => 1, 'post_id' => 2, 'reaction' => 'love', 'created_at' => new \DateTime('2026-02-09 12:25:00'), 'updated_at' => new \DateTime('2026-02-09 12:25:00')],
                ['user_id' => 1, 'post_id' => 4, 'reaction' => 'like', 'created_at' => new \DateTime('2026-02-09 12:30:00'), 'updated_at' => new \DateTime('2026-02-09 12:30:00')],
                ['user_id' => 1, 'post_id' => 5, 'reaction' => 'love', 'created_at' => new \DateTime('2026-02-09 12:35:00'), 'updated_at' => new \DateTime('2026-02-09 12:35:00')],
                ['user_id' => 2, 'post_id' => 5, 'reaction' => 'wow',  'created_at' => new \DateTime('2026-02-09 12:40:00'), 'updated_at' => new \DateTime('2026-02-09 12:40:00')],
            ]);

            // ── Polls ──────────────────────────────────────────

            // Poll 1 — John (draft)
            DB::table('polls')->insert([
                'id'                    => 1,
                'user_id'               => 1,
                'title'                 => 'Couleur préférée',
                'question'              => 'Quelle est votre couleur préférée ?',
                'secret_token'          => Str::random(32),
                'is_draft'              => true,
                'allow_multiple_choices' => false,
                'allow_vote_change'     => false,
                'results_public'        => false,
                'duration'              => null,
                'started_at'            => null,
                'ends_at'               => null,
                'created_at'            => new \DateTime('2026-04-19 10:00:00'),
                'updated_at'            => new \DateTime('2026-04-19 10:00:00'),
            ]);
            DB::table('poll_options')->insert([
                ['poll_id' => 1, 'label' => 'Rouge',  'created_at' => new \DateTime('2026-04-19 10:00:00'), 'updated_at' => new \DateTime('2026-04-19 10:00:00')],
                ['poll_id' => 1, 'label' => 'Bleu',   'created_at' => new \DateTime('2026-04-19 10:00:00'), 'updated_at' => new \DateTime('2026-04-19 10:00:00')],
                ['poll_id' => 1, 'label' => 'Vert',   'created_at' => new \DateTime('2026-04-19 10:00:00'), 'updated_at' => new \DateTime('2026-04-19 10:00:00')],
                ['poll_id' => 1, 'label' => 'Jaune',  'created_at' => new \DateTime('2026-04-19 10:00:00'), 'updated_at' => new \DateTime('2026-04-19 10:00:00')],
            ]);

            // Poll 2 — John (publié, en cours)
            DB::table('polls')->insert([
                'id'                    => 2,
                'user_id'               => 1,
                'title'                 => 'Langage préféré',
                'question'              => 'Quel est votre langage de programmation préféré ?',
                'secret_token'          => Str::random(32),
                'is_draft'              => false,
                'allow_multiple_choices' => false,
                'allow_vote_change'     => true,
                'results_public'        => true,
                'duration'              => 7,
                'started_at'            => new \DateTime('2026-04-20 08:00:00'),
                'ends_at'               => new \DateTime('2026-04-27 08:00:00'),
                'created_at'            => new \DateTime('2026-04-20 08:00:00'),
                'updated_at'            => new \DateTime('2026-04-20 08:00:00'),
            ]);
            DB::table('poll_options')->insert([
                ['poll_id' => 2, 'label' => 'PHP',        'created_at' => new \DateTime('2026-04-20 08:00:00'), 'updated_at' => new \DateTime('2026-04-20 08:00:00')],
                ['poll_id' => 2, 'label' => 'JavaScript', 'created_at' => new \DateTime('2026-04-20 08:00:00'), 'updated_at' => new \DateTime('2026-04-20 08:00:00')],
                ['poll_id' => 2, 'label' => 'Python',     'created_at' => new \DateTime('2026-04-20 08:00:00'), 'updated_at' => new \DateTime('2026-04-20 08:00:00')],
                ['poll_id' => 2, 'label' => 'TypeScript', 'created_at' => new \DateTime('2026-04-20 08:00:00'), 'updated_at' => new \DateTime('2026-04-20 08:00:00')],
            ]);

            // Poll 3 — John (choix multiples autorisés)
            DB::table('polls')->insert([
                'id'                    => 3,
                'user_id'               => 1,
                'title'                 => 'Frameworks préférés',
                'question'              => 'Quels frameworks utilisez-vous ?',
                'secret_token'          => Str::random(32),
                'is_draft'              => false,
                'allow_multiple_choices' => true,
                'allow_vote_change'     => false,
                'results_public'        => false,
                'duration'              => 3,
                'started_at'            => new \DateTime('2026-04-25 09:00:00'),
                'ends_at'               => new \DateTime('2026-04-28 09:00:00'),
                'created_at'            => new \DateTime('2026-04-25 09:00:00'),
                'updated_at'            => new \DateTime('2026-04-25 09:00:00'),
            ]);
            DB::table('poll_options')->insert([
                ['poll_id' => 3, 'label' => 'Laravel', 'created_at' => new \DateTime('2026-04-25 09:00:00'), 'updated_at' => new \DateTime('2026-04-25 09:00:00')],
                ['poll_id' => 3, 'label' => 'Vue.js',  'created_at' => new \DateTime('2026-04-25 09:00:00'), 'updated_at' => new \DateTime('2026-04-25 09:00:00')],
                ['poll_id' => 3, 'label' => 'React',   'created_at' => new \DateTime('2026-04-25 09:00:00'), 'updated_at' => new \DateTime('2026-04-25 09:00:00')],
                ['poll_id' => 3, 'label' => 'Alpine',  'created_at' => new \DateTime('2026-04-25 09:00:00'), 'updated_at' => new \DateTime('2026-04-25 09:00:00')],
            ]);

            // Poll 4 — Jane
            DB::table('polls')->insert([
                'id'                    => 4,
                'user_id'               => 2,
                'title'                 => 'Saison préférée',
                'question'              => 'Quelle est votre saison préférée ?',
                'secret_token'          => Str::random(32),
                'is_draft'              => false,
                'allow_multiple_choices' => false,
                'allow_vote_change'     => false,
                'results_public'        => true,
                'duration'              => 5,
                'started_at'            => new \DateTime('2026-04-22 10:00:00'),
                'ends_at'               => new \DateTime('2026-04-27 10:00:00'),
                'created_at'            => new \DateTime('2026-04-22 10:00:00'),
                'updated_at'            => new \DateTime('2026-04-22 10:00:00'),
            ]);
            DB::table('poll_options')->insert([
                ['poll_id' => 4, 'label' => 'Printemps', 'created_at' => new \DateTime('2026-04-22 10:00:00'), 'updated_at' => new \DateTime('2026-04-22 10:00:00')],
                ['poll_id' => 4, 'label' => 'Été',       'created_at' => new \DateTime('2026-04-22 10:00:00'), 'updated_at' => new \DateTime('2026-04-22 10:00:00')],
                ['poll_id' => 4, 'label' => 'Automne',   'created_at' => new \DateTime('2026-04-22 10:00:00'), 'updated_at' => new \DateTime('2026-04-22 10:00:00')],
                ['poll_id' => 4, 'label' => 'Hiver',     'created_at' => new \DateTime('2026-04-22 10:00:00'), 'updated_at' => new \DateTime('2026-04-22 10:00:00')],
            ]);
        });
    }
}