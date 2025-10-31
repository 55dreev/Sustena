<?php

// database/seeders/ChallengeSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Challenge;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // Easy ⚪
            ['Go Meatless','Eat Vegan Meals','Choose plant-based meals for the day.',1,50,'🥗'],
            ['Turn Off Lights','Save Electricity','Switch off lights when leaving a room.',1,30,'💡'],
            ['Use a Reusable Bottle','Avoid Plastic','Carry a reusable water bottle today.',1,40,'🥤'],
            ['Pick Up Trash','Clean Your Area','Collect litter from your surroundings.',1,45,'🗑️'],
            ['Short Shower','Save Water','Keep your shower under 5 minutes.',1,35,'🚿'],
            ['Skip the Straw','Plastic-Free Drink','Say no to plastic straws today.',1,20,'🥛'],
            ['Reuse a Bag','Ditch Plastic','Bring your own bag when shopping.',1,25,'🛍️'],
            ['Unplug Devices','Save Power','Unplug electronics not in use.',1,30,'🔌'],
            ['Recycle Correctly','Sort Waste','Properly sort recyclables today.',1,40,'♻️'],
            ['Open the Window','Fresh Air','Use natural ventilation instead of AC.',1,20,'🌬️'],
            ['Use Cloth Napkin','Avoid Paper','Replace tissues with a cloth napkin.',1,25,'🧻'],
            ['Print Double-Sided','Save Paper','If you must print, use both sides.',1,30,'🖨️'],
            ['Eat Leftovers','No Food Waste','Eat your leftovers instead of wasting.',1,35,'🍛'],
            ['Turn Off Tap','Save Water','Turn off water while brushing teeth.',1,20,'🚰'],
            ['Share a Ride','Carpool','Share your ride with someone.',1,40,'🚗'],

            // Medium ⚡
            ['Bike Instead of Drive','Use Your Bicycle','Bike for short trips instead of driving.',2,60,'🚲'],
            ['Public Transport','Eco Travel','Use public transportation today.',2,70,'🚌'],
            ['Meal Prep','Reduce Waste','Cook meals at home to avoid packaging waste.',2,65,'🍱'],
            ['Digital Declutter','Save Energy','Delete unnecessary files and emails.',2,55,'💻'],
            ['Bring Your Mug','Skip Disposable Cups','Use your own mug at cafes.',2,50,'☕'],
            ['Cold Wash Laundry','Save Energy','Wash clothes in cold water.',2,75,'👕'],
            ['Compost Food','Reduce Waste','Start composting kitchen scraps.',2,70,'🍂'],
            ['Switch to E-Docs','Paperless Day','Avoid printing, go fully digital today.',2,60,'📄'],
            ['DIY Repair','Fix, Don’t Replace','Repair something instead of buying new.',2,80,'🔧'],
            ['Support Local','Shop Nearby','Buy from local stores or markets.',2,65,'🏪'],
            ['Buy Seasonal Fruits','Local Food','Only buy seasonal, local fruits.',2,70,'🍎'],
            ['Donate Clothes','Give Away','Donate old clothes to those in need.',2,85,'👕'],
            ['Turn Down AC','Save Power','Raise your AC temp by 2 degrees.',2,65,'❄️'],
            ['Eco-Friendly Gift','Green Giving','Give a sustainable gift today.',2,75,'🎁'],
            ['Batch Cooking','Efficient Meals','Cook in bulk to save energy.',2,70,'🍲'],

            // Hard 🔥
            ['Plant a Tree','Contribute to Nature','Plant and care for a tree.',3,100,'🌳'],
            ['Go Car-Free Day','No Driving','Avoid using a car for the whole day.',3,120,'🚶'],
            ['Plastic-Free Day','Zero Plastic Use','Don’t use any single-use plastic today.',3,150,'🚯'],
            ['Volunteer Cleanup','Help the Community','Join or organize a cleanup drive.',3,140,'🧹'],
            ['No Meat Day','Plant-Based Only','Eat no meat products all day.',3,130,'🥦'],
            ['Energy-Free Evening','No Electricity','Spend the evening without electricity.',3,125,'🕯️'],
            ['Walk 10,000 Steps','Skip Vehicles','Walk at least 10,000 steps today.',3,110,'👟'],
            ['Eco Shopping','Sustainable Products','Buy only eco-friendly items today.',3,135,'🛒'],
            ['Cook for Friends','Sustainable Meal','Cook a zero-waste meal for others.',3,150,'🍲'],
            ['Minimalist Day','Buy Nothing','Don’t buy anything non-essential today.',3,120,'🚫'],
            ['Zero-Waste Meal','No Packaged Food','Cook only unpackaged ingredients.',3,140,'🥘'],
            ['Plastic-Free Groceries','Eco Shopping','Buy all groceries without plastic.',3,150,'🛍️'],
            ['DIY Cleaning Product','Eco-Friendly','Make your own natural cleaner.',3,135,'🧴'],
            ['Community Gardening','Grow Together','Help plant in a community garden.',3,145,'🌱'],
            ['Thrift Shopping','Second-Hand','Buy second-hand instead of new.',3,130,'👗'],
        ];

        foreach ($rows as [$title, $subtitle, $desc, $diff, $xp, $icon]) {
            Challenge::updateOrCreate(
                ['title' => $title],
                [
                    'subtitle'    => $subtitle,
                    'description' => $desc,
                    'difficulty'  => $diff,
                    'points_xp'   => $xp,
                    'icon'        => $icon,
                    'is_active'   => true,
                ]
            );
        }
    }
}
