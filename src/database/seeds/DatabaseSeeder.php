<?php

namespace SDSLabs\Quark\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;

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
        $this->problemSeeder();
        $this->solutionSeeder();
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
            'image' => 'image1',
            'score' => 100,
            'score_updated_at' => '2016-12-07 00:00:01'
        ]);
        DB::table('users')->insert([
            'user_id' => '10002',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user2',
            'fullname' => 'User Two',
            'email' => 'user2@email.com',
            'image' => 'image2',
            'score' => 0,
            'score_updated_at' => '2016-12-07 00:00:02'
        ]);
        DB::table('users')->insert([
            'user_id' => '10003',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user3',
            'fullname' => 'User Three',
            'email' => 'user3@email.com',
            'image' => 'image3',
            'score' => 100,
            'score_updated_at' => '2016-12-07 00:00:03'
        ]);
        DB::table('users')->insert([
            'user_id' => '10004',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user4',
            'fullname' => 'User Four',
            'email' => 'user4@email.com',
            'image' => 'image4',
            'score' => 1000,
            'score_updated_at' => '2016-12-07 00:00:04'
        ]);
        DB::table('users')->insert([
            'user_id' => '10005',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user5',
            'fullname' => 'User Five',
            'email' => 'user5@email.com',
            'image' => 'image5',
            'score' => 0,
            'score_updated_at' => '2016-12-07 00:00:05'
        ]);
        DB::table('users')->insert([
            'user_id' => '10006',
            'provider' => 'Facebook',
            'credentials' => random_bytes(10),
            'username' => 'user6',
            'fullname' => 'User Six',
            'email' => 'user6@email.com',
            'image' => 'image6',
            'score' => 10,
            'score_updated_at' => '2016-12-07 00:00:06'
        ]);
    }

    public function teamSeeder()
    {
    	DB::statement('alter table teams AUTO_INCREMENT = 1');
    	DB::table('teams')->insert([
            'name' => 'team1',
            'competition_id' => 1,
            'owner_id' => 1,
            'score' => 10,
            'score_updated_at' => '2016-12-07 00:00:06'
        ]);
    	DB::table('teams')->insert([
            'name' => 'team2',
            'competition_id' => 1,
            'owner_id' => 2,
            'score' => 20,
            'score_updated_at' => '2016-12-07 00:00:07'
        ]);
        DB::table('teams')->insert([
            'name' => 'team3',
            'competition_id' => 3,
            'owner_id' => 4,
            'score' => 10,
            'score_updated_at' => '2016-12-07 00:00:08'
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

    public function solutionSeeder()
    {
    	DB::statement('alter table solutions AUTO_INCREMENT = 1');
    	DB::table('solutions')->insert([
            'problem_id' => 1,
            'score' => 10,
            'answer' => 'answer1'
        ]);
        DB::table('solutions')->insert([
            'problem_id' => 2,
            'score' => 20,
            'answer' => 'answer2'
        ]);
        DB::table('solutions')->insert([
            'problem_id' => 3,
            'score' => 30,
            'answer' => 'answer3'
        ]);
        DB::table('solutions')->insert([
            'problem_id' => 4,
            'score' => 40,
            'answer' => 'answer4'
        ]);
        DB::table('solutions')->insert([
            'problem_id' => 5,
            'score' => 50,
            'answer' => 'answer5'
        ]);
    }

    public function problemSeeder()
    {
    	DB::statement('alter table problems AUTO_INCREMENT = 1');
    	DB::table('problems')->insert([
            'name' => 'problem1',
            'title' => 'problem1',
            'description' => 'description1',
            'competition_id' => 1,
            'creator_id' => 1,
            'uploader_id' => 1,
            'practice' => 1
        ]);
        DB::table('problems')->insert([
            'name' => 'problem2',
            'title' => 'problem2',
            'description' => 'description2',
            'competition_id' => 1,
            'creator_id' => 2,
            'uploader_id' => 2,
            'practice' => 1
        ]);
        DB::table('problems')->insert([
            'name' => 'problem3',
            'title' => 'problem3',
            'description' => 'description3',
            'competition_id' => 2,
            'creator_id' => 1,
            'uploader_id' => 1,
        ]);
        DB::table('problems')->insert([
            'name' => 'problem4',
            'title' => 'problem4',
            'description' => 'description4',
            'competition_id' => 2,
            'creator_id' => 1,
            'uploader_id' => 2,
        ]);
        DB::table('problems')->insert([
            'name' => 'problem5',
            'title' => 'problem5',
            'description' => 'description5',
            'competition_id' => 3,
            'creator_id' => 1,
            'uploader_id' => 4,
        ]);
    }
}
