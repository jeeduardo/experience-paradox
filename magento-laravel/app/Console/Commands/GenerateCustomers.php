<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fake customers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(\Faker\Provider\en_US\Person::class);

        echo date('Y-m-d H:i:s').' : Memory usage: '.memory_get_usage()."\n";

        $chunkLimit = 30;

        $limit = 10000;
        $minLength = 8;
        for ($i = 0; $i < $chunkLimit; $i++) {
            $usersData = [];

            for ($j = 0; $j < $limit; $j++) {
                $usersData[] = [
                    'firstname' => fake()->firstName(),
                    'lastname' => fake()->lastName(),
                    'email' => fake()->email(),
                    'address' => fake()->address(),
                    'birthdate' => fake()->date('Y-m-d', '2000-01-01')
                ];
            }
//            echo $someCsv;

            $result = DB::table('sample_customer')->insert($usersData);
//            print_r([$result]);

//            file_put_contents('target.csv', $someCsv, FILE_APPEND);
            echo date('Y-m-d H:i:s')." : After ".(($i+1)*$limit)." rows memory usage ".memory_get_usage()."\n";

            $usersData = null;
        }

        return Command::SUCCESS;
    }
}
