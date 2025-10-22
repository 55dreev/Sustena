<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('badges')->upsert([
            [
                'slug' => 'carbon-under-100',
                'name' => 'Carbon Under 100',
                'icon' => '✅',
                'category' => 'carbon',
                'rule' => json_encode([
                    'type' => 'threshold','fact'=>'weekly_kg','op'=>'<','value'=>100
                ]),
                'points_reward' => 100,
                'created_at'=>$now,'updated_at'=>$now,
            ],
            [
                'slug' => 'waste-reducer-silver',
                'name' => 'Waste Reducer (Silver)',
                'icon' => '🗑️',
                'category' => 'waste',
                'rule' => json_encode([
                    'type' => 'threshold','fact'=>'waste_kg_week','op'=>'<=','value'=>3
                ]),
                'points_reward' => 120,
                'created_at'=>$now,'updated_at'=>$now,
            ],
            [
                'slug' => 'level-10',
                'name' => 'Eco Level 10',
                'icon' => '🌟',
                'category' => 'meta',
                'rule' => json_encode([
                    'type' => 'threshold','fact'=>'level','op'=>'>=','value'=>10
                ]),
                'points_reward' => 200,
                'created_at'=>$now,'updated_at'=>$now,
            ],
        ], ['slug'], ['name','icon','category','rule','points_reward','updated_at']);
    }
}

