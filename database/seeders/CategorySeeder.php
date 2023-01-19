<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $namesEn = ['Bible', 'Logic', 'Funny'];
        $namesHe = ['תנ"ך', 'לוגיקה', 'מצחיק'];
        $namesRu = ['Библия', 'Логика', 'Смешные'];
        $availabilityEn = [false, false, false];
        $availabilityHe = [false, false, false];
        $availabilityRu = [true, false, false];
        
        for($i = 0; $i < 3; $i++) {
            $id = $i + 1;

            DB::table('categories')->insert([
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            DB::table('category_ens')->insert([
                'name'=>$namesEn[$i],
                'available'=>$availabilityEn[$i],
                'category_id'=>$id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            
            DB::table('category_hes')->insert([
                'name'=>$namesHe[$i],
                'available'=>$availabilityHe[$i],
                'category_id'=>$id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            
            DB::table('category_rus')->insert([
                'name'=>$namesRu[$i],
                'available'=>$availabilityRu[$i],
                'category_id'=>$id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
    }
}