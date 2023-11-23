<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Train;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class TrainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 0; $i < 5; $i++){
            $trains = new Train();
            $trains->agency = $faker->randomElement(["Trenitalia", "Freccia Rossa", "Italo"]);
            $trains->slug = $this->generateSlug($trains->agency);
            $trains->departure_station = $faker->city();
            $trains->arrival_station = $faker->city();
            $trains->departure_time = $faker->time;
            $trains->arrival_time = $faker->time;
            $trains->train_code = $faker->bothify("??###");
            $trains->number_carriages = $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9]);
            $trains->is_in_time = false;
            $trains->is_deleted = false;

            $trains->save();
        }
    }

    private function generateSlug($reference){
        /*
            1. genero lo slug
            2. faccio una query al db per controllare se lo slug esiste già nel database
            3. controllo se lo slug esiste già nel database
            4. se esiste aggiungo 1 allo slug generato e così via fino a quando non trovo una slug inesistente
        */

        //1.
        $slug =  Str::slug($reference, '-');
        $original_slug = $slug;

        //2.
        $exists = Train::where('slug', $slug)->first();
        $c = 1;

        //3.
        while($exists){
            $slug = $original_slug. '-'. $c;
            $exists = Train::where('slug', $slug)->first();

            $c++;
        }

        return $slug;
    }
}
