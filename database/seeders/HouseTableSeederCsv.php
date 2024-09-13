<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\House;
use Illuminate\Support\Str;

class HouseTableSeederCsv extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // apro il file csv e salvo il conteuto in una variabile
        $data_csv = fopen(__DIR__ . '/houses.csv', 'r');
        $i = 0;
        // eseguo un ciclo riga per riga
        while(($row = fgetcsv($data_csv) ) !== false){
            if($i > 0){
                $new_house = new House();
                $new_house->reference = $row[0];
                $new_house->slug = $this->generateSlug($row[8] . ' ' .$row[1]);
                $new_house->address = $row[1];
                $new_house->cap = $row[2];
                $new_house->city = $row[3];
                $new_house->state = $row[4];
                $new_house->square_meters = $row[5];
                $new_house->rooms = $row[6];
                $new_house->bathrooms = $row[7];
                $new_house->type = $row[8];
                $new_house->description = $row[9];
                $new_house->price = $row[10];
                $new_house->is_avaliable = $row[11];
                $new_house->energy_rating = $row[12];
                //dump($new_house);
                $new_house->save();
            }

            $i++;
        }

        // chiudo il file
        fclose($data_csv);

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
