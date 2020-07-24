<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            'text' => 'Do you have a temperature above 100.0F?',
            'group_text' => null,
            'value' => 2,
            'group_order' => 1,
            'question_order' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Cough',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Shortness of Breath or Chest Tightness',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Sore Throat',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Nasal Congestion/Runny Nose',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 1,
            'group_order' => 2,
            'question_order' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Body Aches',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 1,
            'group_order' => 2,
            'question_order' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Loss of Taste and/or Smell',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Diarrhea',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 7,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Nausea',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Vomiting',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 9,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Fever/Chills/Sweats',
            'group_text' => 'Do you have any of the following symptoms unrelated to a chronic condition?',
            'value' => 2,
            'group_order' => 2,
            'question_order' => 10,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Have you traveled outside of the state in the last 14 days?',
            'group_text' => null,
            'value' => 2,
            'group_order' => 3,
            'question_order' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('questions')->insert([
            'text' => 'Have you had any close contact in the last 14 days with someone with a diagnosis of COVID-19?',
            'group_text' => null,
            'value' => 2,
            'group_order' => 4,
            'question_order' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
