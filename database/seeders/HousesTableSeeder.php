<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\House;
use Illuminate\Support\Str;

class HousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $houses = config('houses');

        // in questa funzione si inseriscono i dati nel database
        // ciclo l'array
        // ad ogni ciclo creo una nuova instanza di House
        // ad ogni parametro dò il valore corrispondenete
        // salvo il dato
        foreach ($houses as  $house) {
            $new_house = new House();
            $new_house->reference = $house['reference'];
            $new_house->slug = $this->generateSlug($house['type'] . ' ' .$house['address']);
            $new_house->address = $house['address'];
            $new_house->cap = $house['cap'];
            $new_house->city = $house['city'];
            $new_house->state = $house['state'];
            $new_house->square_meters = $house['square_meters'];
            $new_house->rooms = $house['rooms'];
            $new_house->bathrooms = $house['bathrooms'];
            $new_house->type = $house['type'];
            $new_house->description = $house['description'];
            $new_house->price = $house['price'];
            $new_house->is_avaliable = $house['is_avaliable'];
            $new_house->energy_rating = $house['energy_rating'];
            $new_house->save();
            // dump($new_house->slug);
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
