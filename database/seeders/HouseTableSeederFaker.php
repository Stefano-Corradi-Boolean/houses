<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\House;
use Illuminate\Support\Str;

class HouseTableSeederFaker extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $types = [
            'appartamento',
            'villa',
            'loft',
            'seminterrato',
            'laboratorio'
        ];
        // genero 100 appartamenti fake
        for($i = 0; $i < 1000; $i++){
            $new_house = new House();
            $new_house->reference = $faker->word();
            $new_house->address = $faker->address;
            $new_house->cap = $faker->postcode;
            $new_house->city = $faker->city;
            $new_house->state = $faker->state;
            $new_house->square_meters = $faker->numberBetween(10,10000);
            $new_house->rooms = $faker->numberBetween(1,100);
            $new_house->bathrooms = $faker->numberBetween(1,10);
            $new_house->type = $faker->randomElements($types)[0];
            $new_house->slug = $this->generateSlug($new_house->address . ' ' . $new_house->type);
            $new_house->description = $faker->text(200);
            $new_house->price = $faker->numberBetween(10000,10000000);
            $new_house->energy_rating = $faker->randomElements(['A','AA','AAA','AAA+','B','C','D','E'])[0];
            //dump($new_house);
            $new_house->save();
        }
    }

    private function generateSlug($string){

        /*
            1. ricevo la stringa da "sluggare"
            2. genero lo slug
            3. faccio una query al db per verificare se lo slug è ancora presente
            4. SE non è presente lo slug generato è valido e lo restitisco
            5. SE è presente ne genero uno mantenedo il valore iniziale con concatenato un numero incrementale
            6. una volta generato lo restiuisco

        */
        $slug = Str::slug($string, '-');
        $original_slug = $slug;

        // se trovo uno slug esistente $exists non sarà null
        $exists = House::where('slug', $slug)->first();

        // inizializzo un contatore
        $c = 1;

        // ciglo fino a quano exists non diventa null
        // queso ciclo partirà solo se lo slug è presnte
        while($exists){
            $slug = $original_slug . '-' . $c;
            $exists = House::where('slug', $slug)->first();
            $c++;
        }

        return $slug;
    }
}
