<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RobotSkin;

class RobotSkinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skins = [
            // Default skin - Free
            [
                'name' => 'Classic Bot',
                'description' => 'The original eco-warrior robot!',
                'price' => 0,
                'required_streak' => 0,
                'head_color' => '#ffffff',
                'body_color' => '#ffffff',
                'accent_color' => '#2196f3',
                'special_effect' => null,
                'is_default' => true,
            ],

            // Free purchasable skins (no streak requirement)
            [
                'name' => 'Forest Guardian',
                'description' => 'Nature-inspired green theme',
                'price' => 500,
                'required_streak' => 0,
                'head_color' => '#a8e6cf',
                'body_color' => '#81c784',
                'accent_color' => '#4caf50',
                'special_effect' => null,
                'is_default' => false,
            ],
            [
                'name' => 'Ocean Protector',
                'description' => 'Deep blue ocean theme',
                'price' => 500,
                'required_streak' => 0,
                'head_color' => '#b3e5fc',
                'body_color' => '#4dd0e1',
                'accent_color' => '#0288d1',
                'special_effect' => null,
                'is_default' => false,
            ],
            [
                'name' => 'Solar Powered',
                'description' => 'Bright yellow solar theme',
                'price' => 750,
                'required_streak' => 0,
                'head_color' => '#fff9c4',
                'body_color' => '#ffeb3b',
                'accent_color' => '#ffa726',
                'special_effect' => 'glow',
                'is_default' => false,
            ],
            [
                'name' => 'Recycler Pro',
                'description' => 'Cool gray recycling theme',
                'price' => 600,
                'required_streak' => 0,
                'head_color' => '#eceff1',
                'body_color' => '#90a4ae',
                'accent_color' => '#546e7a',
                'special_effect' => null,
                'is_default' => false,
            ],

            // 10-day streak exclusives
            [
                'name' => 'Bronze Warrior',
                'description' => 'Exclusive 10-day streak skin!',
                'price' => 1000,
                'required_streak' => 10,
                'head_color' => '#ffccbc',
                'body_color' => '#ff8a65',
                'accent_color' => '#bf360c',
                'special_effect' => 'sparkle',
                'is_default' => false,
            ],
            [
                'name' => 'Earth Hero',
                'description' => 'Earned by 10 days of dedication',
                'price' => 1200,
                'required_streak' => 10,
                'head_color' => '#d7ccc8',
                'body_color' => '#a1887f',
                'accent_color' => '#5d4037',
                'special_effect' => null,
                'is_default' => false,
            ],

            // 20-day streak exclusives
            [
                'name' => 'Silver Champion',
                'description' => 'Exclusive 20-day streak skin!',
                'price' => 1500,
                'required_streak' => 20,
                'head_color' => '#f5f5f5',
                'body_color' => '#e0e0e0',
                'accent_color' => '#9e9e9e',
                'special_effect' => 'shimmer',
                'is_default' => false,
            ],
            [
                'name' => 'Aurora Bot',
                'description' => 'Beautiful aurora-inspired theme',
                'price' => 1800,
                'required_streak' => 20,
                'head_color' => '#e1bee7',
                'body_color' => '#ba68c8',
                'accent_color' => '#7b1fa2',
                'special_effect' => 'aurora',
                'is_default' => false,
            ],

            // 30-day streak exclusives
            [
                'name' => 'Golden Legend',
                'description' => 'Ultimate 30-day streak reward!',
                'price' => 2500,
                'required_streak' => 30,
                'head_color' => '#fff8e1',
                'body_color' => '#ffd54f',
                'accent_color' => '#f57f17',
                'special_effect' => 'golden-glow',
                'is_default' => false,
            ],
            [
                'name' => 'Platinum Elite',
                'description' => 'The rarest skin - 30-day masters only!',
                'price' => 3000,
                'required_streak' => 30,
                'head_color' => '#fafafa',
                'body_color' => '#eeeeee',
                'accent_color' => '#616161',
                'special_effect' => 'platinum-shine',
                'is_default' => false,
            ],
            [
                'name' => 'Rainbow Eco',
                'description' => 'Celebrate 30 days with colors!',
                'price' => 2800,
                'required_streak' => 30,
                'head_color' => '#ffcdd2',
                'body_color' => '#90caf9',
                'accent_color' => '#ce93d8',
                'special_effect' => 'rainbow',
                'is_default' => false,
            ],
        ];

        foreach ($skins as $skin) {
            RobotSkin::create($skin);
        }
    }
}
