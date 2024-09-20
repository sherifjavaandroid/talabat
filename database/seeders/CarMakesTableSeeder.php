<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CarMakesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('car_makes')->delete();
        
        \DB::table('car_makes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Abarth',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'AC',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Acura',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Aixam',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Alfa Romeo',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'ALPINA',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Artega',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Asia Motors',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Aston Martin',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Audi',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Austin',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Austin Healey',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Bentley',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'BMW',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Borgward',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Brilliance',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Bugatti',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Buick',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Cadillac',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Casalini',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Caterham',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Chatenet',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Chevrolet',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Chrysler',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Citroën',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Cobra',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Corvette',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Cupra',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Dacia',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Daewoo',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Daihatsu',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'DeTomaso',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Dodge',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Donkervoort',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'DS Automobiles',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Ferrari',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Fiat',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'Fisker',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'Ford',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'GAC Gonow',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'Gemballa',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'GMC',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'Grecav',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'Hamann',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'Holden',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'Honda',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'Hummer',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'Hyundai',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'Infiniti',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'Isuzu',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'Iveco',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'Jaguar',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'Jeep',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'Kia',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'Koenigsegg',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'KTM',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'Lada',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'Lamborghini',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'Lancia',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'Land Rover',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'Landwind',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'Lexus',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'Ligier',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'Lincoln',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'Lotus',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'Mahindra',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'Maserati',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'Maybach',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'Mazda',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'McLaren',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'Mercedes-Benz',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'MG',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'Microcar',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'MINI',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'Mitsubishi',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'Morgan',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'Nissan',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'NSU',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'Oldsmobile',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'Opel',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'Pagani',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'Peugeot',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'Piaggio',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'Plymouth',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'Polestar',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'Pontiac',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'Porsche',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'Proton',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'Renault',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'Rolls-Royce',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'Rover',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'Ruf',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'Saab',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'Santana',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'Seat',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'Skoda',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'Smart',
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'speedART',
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'Spyker',
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'Ssangyong',
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'Subaru',
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'Suzuki',
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'Talbot',
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'Tata',
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'TECHART',
            ),
            105 => 
            array (
                'id' => 106,
                'name' => 'Tesla',
            ),
            106 => 
            array (
                'id' => 107,
                'name' => 'Toyota',
            ),
            107 => 
            array (
                'id' => 108,
                'name' => 'Trabant',
            ),
            108 => 
            array (
                'id' => 109,
                'name' => 'Triumph',
            ),
            109 => 
            array (
                'id' => 110,
                'name' => 'TVR',
            ),
            110 => 
            array (
                'id' => 111,
                'name' => 'Volkswagen',
            ),
            111 => 
            array (
                'id' => 112,
                'name' => 'Volvo',
            ),
            112 => 
            array (
                'id' => 113,
                'name' => 'Wartburg',
            ),
            113 => 
            array (
                'id' => 114,
                'name' => 'Westfield',
            ),
            114 => 
            array (
                'id' => 115,
                'name' => 'Wiesmann',
            ),
            115 => 
            array (
                'id' => 116,
                'name' => 'Other',
            ),
        ));
        
        
    }
}