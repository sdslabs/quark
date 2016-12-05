<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->competitionSeeder();
        $this->userSeeder();
        $this->teamSeeder();
        $this->roleSeeder();
        $this->problemTypeSeeder();
        $this->judgeSeeder();
        $this->solutionSeeder();
        $this->problemSeeder();
    }

    public function competitionSeeder()
    {
    	DB::statement('alter table competitions AUTO_INCREMENT = 1');
    	DB::table('competitions')->insert([
            'name' => 'competition1',
            'title' => 'competition1',
            'description' => 'description1',
            'rules' => 'rules1',
            'team_limit' => '1',
            'start_at' => '2016-11-26 00:00:00',
            'end_at' => '2016-11-28 00:00:00'
        ]);
        DB::table('competitions')->insert([
            'name' => 'competition2',
            'title' => 'competition2',
            'description' => 'description2',
            'rules' => 'rules2',
            'team_limit' => '2',
            'start_at' => '2016-12-01 00:00:00',
            'end_at' => '2016-12-10 00:00:00'
        ]);
        DB::table('competitions')->insert([
            'name' => 'competition3',
            'title' => 'competition3',
            'description' => 'description3',
            'rules' => 'rules3',
            'team_limit' => '3',
            'start_at' => '2016-12-26 00:00:00',
            'end_at' => '2016-12-28 00:00:00'
        ]);
    }

    public function userSeeder()
    {
    	DB::statement('alter table users AUTO_INCREMENT = 1');
    	DB::table('users')->insert([
            'user_id' => '10001',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user1',
            'fullname' => 'User One',
            'email' => 'user1@email.com',
            'image' => 'image1'
        ]);
        DB::table('users')->insert([
            'user_id' => '10002',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user2',
            'fullname' => 'User Two',
            'email' => 'user2@email.com',
            'image' => 'image2'
        ]);
        DB::table('users')->insert([
            'user_id' => '10003',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user3',
            'fullname' => 'User Three',
            'email' => 'user3@email.com',
            'image' => 'image3'
        ]);
        DB::table('users')->insert([
            'user_id' => '10004',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user4',
            'fullname' => 'User Four',
            'email' => 'user4@email.com',
            'image' => 'image4'
        ]);
        DB::table('users')->insert([
            'user_id' => '10005',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user5',
            'fullname' => 'User Five',
            'email' => 'user5@email.com',
            'image' => 'image5'
        ]);
        DB::table('users')->insert([
            'user_id' => '10006',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user6',
            'fullname' => 'User Six',
            'email' => 'user6@email.com',
            'image' => 'image6'
        ]);
    }

    public function teamSeeder()
    {
    	DB::statement('alter table teams AUTO_INCREMENT = 1');
    	DB::table('teams')->insert([
            'name' => 'team1',
            'competition_id' => 1,
            'owner_id' => 1
        ]);
    	DB::table('teams')->insert([
            'name' => 'team2',
            'competition_id' => 2,
            'owner_id' => 2
        ]);
        DB::table('teams')->insert([
            'name' => 'team3',
            'competition_id' => 3,
            'owner_id' => 4
        ]);
        DB::statement('alter table user_team_maps AUTO_INCREMENT = 1');
        DB::table('user_team_maps')->insert([
            'user_id' => 1,
            'team_id' => 1
        ]);
        DB::table('user_team_maps')->insert([
            'user_id' => 2,
            'team_id' => 2
        ]);
        DB::table('user_team_maps')->insert([
            'user_id' => 3,
            'team_id' => 2
        ]);
        DB::table('user_team_maps')->insert([
            'user_id' => 4,
            'team_id' => 3
        ]);
        DB::table('user_team_maps')->insert([
            'user_id' => 5,
            'team_id' => 3
        ]);
        DB::table('user_team_maps')->insert([
            'user_id' => 6,
            'team_id' => 3
        ]);
    }

    public function roleSeeder()
    {
    	DB::statement('alter table roles AUTO_INCREMENT = 1');
        DB::table('roles')->insert([
            'name' => 'role1',
            'title' => 'role1',
            'description' => 'description1'
        ]);
        DB::table('roles')->insert([
            'name' => 'role2',
            'title' => 'role2',
            'description' => 'description2'
        ]);
        DB::table('roles')->insert([
            'name' => 'role3',
            'title' => 'role3',
            'description' => 'description3'
        ]);
        DB::statement('alter table user_role_maps AUTO_INCREMENT = 1');
        DB::table('user_role_maps')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('user_role_maps')->insert([
            'user_id' => 2,
            'role_id' => 2
        ]);
        DB::table('user_role_maps')->insert([
            'user_id' => 3,
            'role_id' => 3
        ]);
        DB::table('user_role_maps')->insert([
            'user_id' => 4,
            'role_id' => 1
        ]);
        DB::table('user_role_maps')->insert([
            'user_id' => 5,
            'role_id' => 2
        ]);
        DB::table('user_role_maps')->insert([
            'user_id' => 6,
            'role_id' => 3
        ]);
    }

    public function problemTypeSeeder()
    {
    	DB::statement('alter table problem_types AUTO_INCREMENT = 1');
    	DB::table('problem_types')->insert([
            'name' => 'type1',
            'title' => 'type1',
            'description' => 'description1'
        ]);
        DB::table('problem_types')->insert([
            'name' => 'type2',
            'title' => 'type2',
            'description' => 'description2'
        ]);
        DB::table('problem_types')->insert([
            'name' => 'type3',
            'title' => 'type3',
            'description' => 'description3'
        ]);
    }

    public function judgeSeeder()
    {
    	DB::statement('alter table judges AUTO_INCREMENT = 1');
    	DB::table('judges')->insert([
            'name' => 'judge1',
            'title' => 'judge1',
            'description' => 'description1'
        ]);
        DB::table('judges')->insert([
            'name' => 'judge2',
            'title' => 'judge2',
            'description' => 'description2'
        ]);
        DB::table('judges')->insert([
            'name' => 'judge3',
            'title' => 'judge3',
            'description' => 'description3'
        ]);
    }

    public function solutionSeeder()
    {
    	DB::statement('alter table solutions AUTO_INCREMENT = 1');
    	DB::table('solutions')->insert([
            'practice_judge_id' => 1,
            'competition_judge_id' => 3,
            'practice_score' => 50,
            'competition_score' => 100,
            'solution' => 'solution1'
        ]);
        DB::table('solutions')->insert([
            'practice_judge_id' => 1,
            'competition_judge_id' => 3,
            'practice_score' => 50,
            'competition_score' => 100,
            'solution' => 'solution2'
        ]);
        DB::table('solutions')->insert([
            'practice_judge_id' => 1,
            'competition_judge_id' => 3,
            'practice_score' => 50,
            'competition_score' => 100,
            'solution' => 'solution3'
        ]);
        DB::table('solutions')->insert([
            'practice_judge_id' => 2,
            'competition_judge_id' => 3,
            'practice_score' => 50,
            'competition_score' => 100,
            'solution' => 'solution4'
        ]);
        DB::table('solutions')->insert([
            'practice_judge_id' => 2,
            'competition_judge_id' => 3,
            'practice_score' => 50,
            'competition_score' => 100,
            'solution' => 'solution5'
        ]);
    }

    public function problemSeeder()
    {
    	DB::statement('alter table problems AUTO_INCREMENT = 1');
    	DB::table('problems')->insert([
            'name' => 'problem1',
            'title' => 'problem1',
            'description' => 'description1',
            'solution_id' => 1,
            'competition_id' => 1,
            'creator_id' => 1,
            'uploader_id' => 1,
            'problem_type_id' => 1,
            'practice' => 1
        ]);
        DB::table('problems')->insert([
            'name' => 'problem2',
            'title' => 'problem2',
            'description' => 'description2',
            'solution_id' => 2,
            'competition_id' => 1,
            'creator_id' => 2,
            'uploader_id' => 2,
            'problem_type_id' => 2,
            'practice' => 1
        ]);
        DB::table('problems')->insert([
            'name' => 'problem3',
            'title' => 'problem3',
            'description' => 'description3',
            'solution_id' => 3,
            'competition_id' => 2,
            'creator_id' => 1,
            'uploader_id' => 1,
            'problem_type_id' => 2,
        ]);
        DB::table('problems')->insert([
            'name' => 'problem4',
            'title' => 'problem4',
            'description' => 'description4',
            'solution_id' => 4,
            'competition_id' => 2,
            'creator_id' => 1,
            'uploader_id' => 2,
            'problem_type_id' => 3,
        ]);
        DB::table('problems')->insert([
            'name' => 'problem5',
            'title' => 'problem5',
            'description' => 'description5',
            'solution_id' => 5,
            'competition_id' => 3,
            'creator_id' => 1,
            'uploader_id' => 4,
            'problem_type_id' => 1,
        ]);
    }
}
