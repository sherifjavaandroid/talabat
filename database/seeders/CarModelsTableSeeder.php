<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CarModelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('car_models')->delete();
        
        \DB::table('car_models')->insert(array (
            0 => 
            array (
                'id' => 1,
                'car_make_id' => 1,
                'name' => '124 Spider',
            ),
            1 => 
            array (
                'id' => 2,
                'car_make_id' => 1,
                'name' => '500',
            ),
            2 => 
            array (
                'id' => 3,
                'car_make_id' => 1,
                'name' => '500C',
            ),
            3 => 
            array (
                'id' => 4,
                'car_make_id' => 1,
                'name' => '595',
            ),
            4 => 
            array (
                'id' => 5,
                'car_make_id' => 1,
                'name' => '595C',
            ),
            5 => 
            array (
                'id' => 6,
                'car_make_id' => 1,
                'name' => '595 Competizione',
            ),
            6 => 
            array (
                'id' => 7,
                'car_make_id' => 1,
                'name' => '595 Turismo',
            ),
            7 => 
            array (
                'id' => 8,
                'car_make_id' => 1,
                'name' => '695',
            ),
            8 => 
            array (
                'id' => 9,
                'car_make_id' => 1,
                'name' => '695C',
            ),
            9 => 
            array (
                'id' => 10,
                'car_make_id' => 1,
                'name' => 'Grande Punto',
            ),
            10 => 
            array (
                'id' => 11,
                'car_make_id' => 1,
                'name' => 'Punto Evo',
            ),
            11 => 
            array (
                'id' => 12,
                'car_make_id' => 1,
                'name' => 'Other',
            ),
            12 => 
            array (
                'id' => 13,
                'car_make_id' => 2,
                'name' => 'Other',
            ),
            13 => 
            array (
                'id' => 14,
                'car_make_id' => 3,
                'name' => 'MDX',
            ),
            14 => 
            array (
                'id' => 15,
                'car_make_id' => 3,
                'name' => 'NSX',
            ),
            15 => 
            array (
                'id' => 16,
                'car_make_id' => 3,
                'name' => 'RL',
            ),
            16 => 
            array (
                'id' => 17,
                'car_make_id' => 3,
                'name' => 'RSX',
            ),
            17 => 
            array (
                'id' => 18,
                'car_make_id' => 3,
                'name' => 'TL',
            ),
            18 => 
            array (
                'id' => 19,
                'car_make_id' => 3,
                'name' => 'TSX',
            ),
            19 => 
            array (
                'id' => 20,
                'car_make_id' => 3,
                'name' => 'Other',
            ),
            20 => 
            array (
                'id' => 21,
                'car_make_id' => 4,
                'name' => 'City',
            ),
            21 => 
            array (
                'id' => 22,
                'car_make_id' => 4,
                'name' => 'Crossline',
            ),
            22 => 
            array (
                'id' => 23,
                'car_make_id' => 4,
                'name' => 'Roadline',
            ),
            23 => 
            array (
                'id' => 24,
                'car_make_id' => 4,
                'name' => 'Scouty R',
            ),
            24 => 
            array (
                'id' => 25,
                'car_make_id' => 4,
                'name' => 'Other',
            ),
            25 => 
            array (
                'id' => 26,
                'car_make_id' => 5,
                'name' => '4C',
            ),
            26 => 
            array (
                'id' => 27,
                'car_make_id' => 5,
                'name' => '8C',
            ),
            27 => 
            array (
                'id' => 28,
                'car_make_id' => 5,
                'name' => 'Alfa 145',
            ),
            28 => 
            array (
                'id' => 29,
                'car_make_id' => 5,
                'name' => 'Alfa 146',
            ),
            29 => 
            array (
                'id' => 30,
                'car_make_id' => 5,
                'name' => 'Alfa 147',
            ),
            30 => 
            array (
                'id' => 31,
                'car_make_id' => 5,
                'name' => 'Alfa 155',
            ),
            31 => 
            array (
                'id' => 32,
                'car_make_id' => 5,
                'name' => 'Alfa 156',
            ),
            32 => 
            array (
                'id' => 33,
                'car_make_id' => 5,
                'name' => 'Alfa 159',
            ),
            33 => 
            array (
                'id' => 34,
                'car_make_id' => 5,
                'name' => 'Alfa 164',
            ),
            34 => 
            array (
                'id' => 35,
                'car_make_id' => 5,
                'name' => 'Alfa 166',
            ),
            35 => 
            array (
                'id' => 36,
                'car_make_id' => 5,
                'name' => 'Alfa 33',
            ),
            36 => 
            array (
                'id' => 37,
                'car_make_id' => 5,
                'name' => 'Alfa 75',
            ),
            37 => 
            array (
                'id' => 38,
                'car_make_id' => 5,
                'name' => 'Alfa 90',
            ),
            38 => 
            array (
                'id' => 39,
                'car_make_id' => 5,
                'name' => 'Alfasud',
            ),
            39 => 
            array (
                'id' => 40,
                'car_make_id' => 5,
                'name' => 'Alfetta',
            ),
            40 => 
            array (
                'id' => 41,
                'car_make_id' => 5,
                'name' => 'Brera',
            ),
            41 => 
            array (
                'id' => 42,
                'car_make_id' => 5,
                'name' => 'Crosswagon',
            ),
            42 => 
            array (
                'id' => 43,
                'car_make_id' => 5,
                'name' => 'Giulia',
            ),
            43 => 
            array (
                'id' => 44,
                'car_make_id' => 5,
                'name' => 'Giulietta',
            ),
            44 => 
            array (
                'id' => 45,
                'car_make_id' => 5,
                'name' => 'GT',
            ),
            45 => 
            array (
                'id' => 46,
                'car_make_id' => 5,
                'name' => 'GTV',
            ),
            46 => 
            array (
                'id' => 47,
                'car_make_id' => 5,
                'name' => 'Junior',
            ),
            47 => 
            array (
                'id' => 48,
                'car_make_id' => 5,
                'name' => 'MiTo',
            ),
            48 => 
            array (
                'id' => 49,
                'car_make_id' => 5,
                'name' => 'Spider',
            ),
            49 => 
            array (
                'id' => 50,
                'car_make_id' => 5,
                'name' => 'Sprint',
            ),
            50 => 
            array (
                'id' => 51,
                'car_make_id' => 5,
                'name' => 'Stelvio',
            ),
            51 => 
            array (
                'id' => 52,
                'car_make_id' => 5,
                'name' => 'Other',
            ),
            52 => 
            array (
                'id' => 53,
                'car_make_id' => 6,
                'name' => 'B10',
            ),
            53 => 
            array (
                'id' => 54,
                'car_make_id' => 6,
                'name' => 'B12',
            ),
            54 => 
            array (
                'id' => 55,
                'car_make_id' => 6,
                'name' => 'B3',
            ),
            55 => 
            array (
                'id' => 56,
                'car_make_id' => 6,
                'name' => 'B4',
            ),
            56 => 
            array (
                'id' => 57,
                'car_make_id' => 6,
                'name' => 'B5',
            ),
            57 => 
            array (
                'id' => 58,
                'car_make_id' => 6,
                'name' => 'B6',
            ),
            58 => 
            array (
                'id' => 59,
                'car_make_id' => 6,
                'name' => 'B7',
            ),
            59 => 
            array (
                'id' => 60,
                'car_make_id' => 6,
                'name' => 'B8',
            ),
            60 => 
            array (
                'id' => 61,
                'car_make_id' => 6,
                'name' => 'D10',
            ),
            61 => 
            array (
                'id' => 62,
                'car_make_id' => 6,
                'name' => 'D3',
            ),
            62 => 
            array (
                'id' => 63,
                'car_make_id' => 6,
                'name' => 'D4',
            ),
            63 => 
            array (
                'id' => 64,
                'car_make_id' => 6,
                'name' => 'D5',
            ),
            64 => 
            array (
                'id' => 65,
                'car_make_id' => 6,
                'name' => 'Roadster S',
            ),
            65 => 
            array (
                'id' => 66,
                'car_make_id' => 6,
                'name' => 'XD3',
            ),
            66 => 
            array (
                'id' => 67,
                'car_make_id' => 6,
                'name' => 'XD4',
            ),
            67 => 
            array (
                'id' => 68,
                'car_make_id' => 6,
                'name' => 'Other',
            ),
            68 => 
            array (
                'id' => 69,
                'car_make_id' => 7,
                'name' => 'GT',
            ),
            69 => 
            array (
                'id' => 70,
                'car_make_id' => 7,
                'name' => 'Other',
            ),
            70 => 
            array (
                'id' => 71,
                'car_make_id' => 8,
                'name' => 'Rocsta',
            ),
            71 => 
            array (
                'id' => 72,
                'car_make_id' => 8,
                'name' => 'Other',
            ),
            72 => 
            array (
                'id' => 73,
                'car_make_id' => 9,
                'name' => 'AR1',
            ),
            73 => 
            array (
                'id' => 74,
                'car_make_id' => 9,
                'name' => 'Cygnet',
            ),
            74 => 
            array (
                'id' => 75,
                'car_make_id' => 9,
                'name' => 'DB',
            ),
            75 => 
            array (
                'id' => 76,
                'car_make_id' => 9,
                'name' => 'DB11',
            ),
            76 => 
            array (
                'id' => 77,
                'car_make_id' => 9,
                'name' => 'DB7',
            ),
            77 => 
            array (
                'id' => 78,
                'car_make_id' => 9,
                'name' => 'DB9',
            ),
            78 => 
            array (
                'id' => 79,
                'car_make_id' => 9,
                'name' => 'DBS',
            ),
            79 => 
            array (
                'id' => 80,
                'car_make_id' => 9,
                'name' => 'Lagonda',
            ),
            80 => 
            array (
                'id' => 81,
                'car_make_id' => 9,
                'name' => 'Rapide',
            ),
            81 => 
            array (
                'id' => 82,
                'car_make_id' => 9,
                'name' => 'V12 Vantage',
            ),
            82 => 
            array (
                'id' => 83,
                'car_make_id' => 9,
                'name' => 'V8 Vantage',
            ),
            83 => 
            array (
                'id' => 84,
                'car_make_id' => 9,
                'name' => 'Vanquish',
            ),
            84 => 
            array (
                'id' => 85,
                'car_make_id' => 9,
                'name' => 'Virage',
            ),
            85 => 
            array (
                'id' => 86,
                'car_make_id' => 9,
                'name' => 'Other',
            ),
            86 => 
            array (
                'id' => 87,
                'car_make_id' => 10,
                'name' => '100',
            ),
            87 => 
            array (
                'id' => 88,
                'car_make_id' => 10,
                'name' => '200',
            ),
            88 => 
            array (
                'id' => 89,
                'car_make_id' => 10,
                'name' => '80',
            ),
            89 => 
            array (
                'id' => 90,
                'car_make_id' => 10,
                'name' => '90',
            ),
            90 => 
            array (
                'id' => 91,
                'car_make_id' => 10,
                'name' => 'A1',
            ),
            91 => 
            array (
                'id' => 92,
                'car_make_id' => 10,
                'name' => 'A2',
            ),
            92 => 
            array (
                'id' => 93,
                'car_make_id' => 10,
                'name' => 'A3',
            ),
            93 => 
            array (
                'id' => 94,
                'car_make_id' => 10,
                'name' => 'A4',
            ),
            94 => 
            array (
                'id' => 95,
                'car_make_id' => 10,
                'name' => 'A4 Allroad',
            ),
            95 => 
            array (
                'id' => 96,
                'car_make_id' => 10,
                'name' => 'A5',
            ),
            96 => 
            array (
                'id' => 97,
                'car_make_id' => 10,
                'name' => 'A6',
            ),
            97 => 
            array (
                'id' => 98,
                'car_make_id' => 10,
                'name' => 'A6 Allroad',
            ),
            98 => 
            array (
                'id' => 99,
                'car_make_id' => 10,
                'name' => 'A7',
            ),
            99 => 
            array (
                'id' => 100,
                'car_make_id' => 10,
                'name' => 'A8',
            ),
            100 => 
            array (
                'id' => 101,
                'car_make_id' => 10,
                'name' => 'Cabriolet',
            ),
            101 => 
            array (
                'id' => 102,
                'car_make_id' => 10,
                'name' => 'Coupé',
            ),
            102 => 
            array (
                'id' => 103,
                'car_make_id' => 10,
                'name' => 'e-tron',
            ),
            103 => 
            array (
                'id' => 104,
                'car_make_id' => 10,
                'name' => 'Q1',
            ),
            104 => 
            array (
                'id' => 105,
                'car_make_id' => 10,
                'name' => 'Q2',
            ),
            105 => 
            array (
                'id' => 106,
                'car_make_id' => 10,
                'name' => 'Q3',
            ),
            106 => 
            array (
                'id' => 107,
                'car_make_id' => 10,
                'name' => 'Q5',
            ),
            107 => 
            array (
                'id' => 108,
                'car_make_id' => 10,
                'name' => 'Q7',
            ),
            108 => 
            array (
                'id' => 109,
                'car_make_id' => 10,
                'name' => 'Q8',
            ),
            109 => 
            array (
                'id' => 110,
                'car_make_id' => 10,
                'name' => 'quattro',
            ),
            110 => 
            array (
                'id' => 111,
                'car_make_id' => 10,
                'name' => 'R8',
            ),
            111 => 
            array (
                'id' => 112,
                'car_make_id' => 10,
                'name' => 'RS2',
            ),
            112 => 
            array (
                'id' => 113,
                'car_make_id' => 10,
                'name' => 'RS3',
            ),
            113 => 
            array (
                'id' => 114,
                'car_make_id' => 10,
                'name' => 'RS4',
            ),
            114 => 
            array (
                'id' => 115,
                'car_make_id' => 10,
                'name' => 'RS5',
            ),
            115 => 
            array (
                'id' => 116,
                'car_make_id' => 10,
                'name' => 'RS6',
            ),
            116 => 
            array (
                'id' => 117,
                'car_make_id' => 10,
                'name' => 'RS7',
            ),
            117 => 
            array (
                'id' => 118,
                'car_make_id' => 10,
                'name' => 'RSQ3',
            ),
            118 => 
            array (
                'id' => 119,
                'car_make_id' => 10,
                'name' => 'S1',
            ),
            119 => 
            array (
                'id' => 120,
                'car_make_id' => 10,
                'name' => 'S2',
            ),
            120 => 
            array (
                'id' => 121,
                'car_make_id' => 10,
                'name' => 'S3',
            ),
            121 => 
            array (
                'id' => 122,
                'car_make_id' => 10,
                'name' => 'S4',
            ),
            122 => 
            array (
                'id' => 123,
                'car_make_id' => 10,
                'name' => 'S5',
            ),
            123 => 
            array (
                'id' => 124,
                'car_make_id' => 10,
                'name' => 'S6',
            ),
            124 => 
            array (
                'id' => 125,
                'car_make_id' => 10,
                'name' => 'S7',
            ),
            125 => 
            array (
                'id' => 126,
                'car_make_id' => 10,
                'name' => 'S8',
            ),
            126 => 
            array (
                'id' => 127,
                'car_make_id' => 10,
                'name' => 'SQ2',
            ),
            127 => 
            array (
                'id' => 128,
                'car_make_id' => 10,
                'name' => 'SQ5',
            ),
            128 => 
            array (
                'id' => 129,
                'car_make_id' => 10,
                'name' => 'SQ7',
            ),
            129 => 
            array (
                'id' => 130,
                'car_make_id' => 10,
                'name' => 'SQ8',
            ),
            130 => 
            array (
                'id' => 131,
                'car_make_id' => 10,
                'name' => 'TT',
            ),
            131 => 
            array (
                'id' => 132,
                'car_make_id' => 10,
                'name' => 'TT RS',
            ),
            132 => 
            array (
                'id' => 133,
                'car_make_id' => 10,
                'name' => 'TTS',
            ),
            133 => 
            array (
                'id' => 134,
                'car_make_id' => 10,
                'name' => 'V8',
            ),
            134 => 
            array (
                'id' => 135,
                'car_make_id' => 10,
                'name' => 'Other',
            ),
            135 => 
            array (
                'id' => 136,
                'car_make_id' => 11,
                'name' => 'Other',
            ),
            136 => 
            array (
                'id' => 137,
                'car_make_id' => 12,
                'name' => 'Other',
            ),
            137 => 
            array (
                'id' => 138,
                'car_make_id' => 13,
                'name' => 'Arnage',
            ),
            138 => 
            array (
                'id' => 139,
                'car_make_id' => 13,
                'name' => 'Azure',
            ),
            139 => 
            array (
                'id' => 140,
                'car_make_id' => 13,
                'name' => 'Bentayga',
            ),
            140 => 
            array (
                'id' => 141,
                'car_make_id' => 13,
                'name' => 'Brooklands',
            ),
            141 => 
            array (
                'id' => 142,
                'car_make_id' => 13,
                'name' => 'Continental',
            ),
            142 => 
            array (
                'id' => 143,
                'car_make_id' => 13,
                'name' => 'Continental Flying Spur',
            ),
            143 => 
            array (
                'id' => 144,
                'car_make_id' => 13,
                'name' => 'Continental GT',
            ),
            144 => 
            array (
                'id' => 145,
                'car_make_id' => 13,
                'name' => 'Continental GTC',
            ),
            145 => 
            array (
                'id' => 146,
                'car_make_id' => 13,
                'name' => 'Continental Supersports',
            ),
            146 => 
            array (
                'id' => 147,
                'car_make_id' => 13,
                'name' => 'Eight',
            ),
            147 => 
            array (
                'id' => 148,
                'car_make_id' => 13,
                'name' => 'Flying Spur',
            ),
            148 => 
            array (
                'id' => 149,
                'car_make_id' => 13,
                'name' => 'Mulsanne',
            ),
            149 => 
            array (
                'id' => 150,
                'car_make_id' => 13,
                'name' => 'Turbo R',
            ),
            150 => 
            array (
                'id' => 151,
                'car_make_id' => 13,
                'name' => 'Turbo RT',
            ),
            151 => 
            array (
                'id' => 152,
                'car_make_id' => 13,
                'name' => 'Turbo S',
            ),
            152 => 
            array (
                'id' => 153,
                'car_make_id' => 13,
                'name' => 'Other',
            ),
            153 => 
            array (
                'id' => 154,
                'car_make_id' => 14,
                'name' => '114',
            ),
            154 => 
            array (
                'id' => 155,
                'car_make_id' => 14,
                'name' => '116',
            ),
            155 => 
            array (
                'id' => 156,
                'car_make_id' => 14,
                'name' => '118',
            ),
            156 => 
            array (
                'id' => 157,
                'car_make_id' => 14,
                'name' => '120',
            ),
            157 => 
            array (
                'id' => 158,
                'car_make_id' => 14,
                'name' => '123',
            ),
            158 => 
            array (
                'id' => 159,
                'car_make_id' => 14,
                'name' => '125',
            ),
            159 => 
            array (
                'id' => 160,
                'car_make_id' => 14,
                'name' => '130',
            ),
            160 => 
            array (
                'id' => 161,
                'car_make_id' => 14,
                'name' => '135',
            ),
            161 => 
            array (
                'id' => 162,
                'car_make_id' => 14,
                'name' => '1er M Coupé',
            ),
            162 => 
            array (
                'id' => 163,
                'car_make_id' => 14,
                'name' => '2002',
            ),
            163 => 
            array (
                'id' => 164,
                'car_make_id' => 14,
                'name' => '214 Active Tourer',
            ),
            164 => 
            array (
                'id' => 165,
                'car_make_id' => 14,
                'name' => '214 Gran Tourer',
            ),
            165 => 
            array (
                'id' => 166,
                'car_make_id' => 14,
                'name' => '216',
            ),
            166 => 
            array (
                'id' => 167,
                'car_make_id' => 14,
                'name' => '216 Active Tourer',
            ),
            167 => 
            array (
                'id' => 168,
                'car_make_id' => 14,
                'name' => '216 Gran Tourer',
            ),
            168 => 
            array (
                'id' => 169,
                'car_make_id' => 14,
                'name' => '218',
            ),
            169 => 
            array (
                'id' => 170,
                'car_make_id' => 14,
                'name' => '218 Active Tourer',
            ),
            170 => 
            array (
                'id' => 171,
                'car_make_id' => 14,
                'name' => '218 Gran Tourer',
            ),
            171 => 
            array (
                'id' => 172,
                'car_make_id' => 14,
                'name' => '220',
            ),
            172 => 
            array (
                'id' => 173,
                'car_make_id' => 14,
                'name' => '220 Active Tourer',
            ),
            173 => 
            array (
                'id' => 174,
                'car_make_id' => 14,
                'name' => '220 Gran Tourer',
            ),
            174 => 
            array (
                'id' => 175,
                'car_make_id' => 14,
                'name' => '225',
            ),
            175 => 
            array (
                'id' => 176,
                'car_make_id' => 14,
                'name' => '225 Active Tourer',
            ),
            176 => 
            array (
                'id' => 177,
                'car_make_id' => 14,
                'name' => '228',
            ),
            177 => 
            array (
                'id' => 178,
                'car_make_id' => 14,
                'name' => '230',
            ),
            178 => 
            array (
                'id' => 179,
                'car_make_id' => 14,
                'name' => '315',
            ),
            179 => 
            array (
                'id' => 180,
                'car_make_id' => 14,
                'name' => '316',
            ),
            180 => 
            array (
                'id' => 181,
                'car_make_id' => 14,
                'name' => '318',
            ),
            181 => 
            array (
                'id' => 182,
                'car_make_id' => 14,
                'name' => '318 Gran Turismo',
            ),
            182 => 
            array (
                'id' => 183,
                'car_make_id' => 14,
                'name' => '320',
            ),
            183 => 
            array (
                'id' => 184,
                'car_make_id' => 14,
                'name' => '320 Gran Turismo',
            ),
            184 => 
            array (
                'id' => 185,
                'car_make_id' => 14,
                'name' => '323',
            ),
            185 => 
            array (
                'id' => 186,
                'car_make_id' => 14,
                'name' => '324',
            ),
            186 => 
            array (
                'id' => 187,
                'car_make_id' => 14,
                'name' => '325',
            ),
            187 => 
            array (
                'id' => 188,
                'car_make_id' => 14,
                'name' => '325 Gran Turismo',
            ),
            188 => 
            array (
                'id' => 189,
                'car_make_id' => 14,
                'name' => '328',
            ),
            189 => 
            array (
                'id' => 190,
                'car_make_id' => 14,
                'name' => '328 Gran Turismo',
            ),
            190 => 
            array (
                'id' => 191,
                'car_make_id' => 14,
                'name' => '330',
            ),
            191 => 
            array (
                'id' => 192,
                'car_make_id' => 14,
                'name' => '330 Gran Turismo',
            ),
            192 => 
            array (
                'id' => 193,
                'car_make_id' => 14,
                'name' => '335',
            ),
            193 => 
            array (
                'id' => 194,
                'car_make_id' => 14,
                'name' => '335 Gran Turismo',
            ),
            194 => 
            array (
                'id' => 195,
                'car_make_id' => 14,
                'name' => '340',
            ),
            195 => 
            array (
                'id' => 196,
                'car_make_id' => 14,
                'name' => '340 Gran Turismo',
            ),
            196 => 
            array (
                'id' => 197,
                'car_make_id' => 14,
                'name' => 'ActiveHybrid 3',
            ),
            197 => 
            array (
                'id' => 198,
                'car_make_id' => 14,
                'name' => '418',
            ),
            198 => 
            array (
                'id' => 199,
                'car_make_id' => 14,
                'name' => '418 Gran Coupé',
            ),
            199 => 
            array (
                'id' => 200,
                'car_make_id' => 14,
                'name' => '420',
            ),
            200 => 
            array (
                'id' => 201,
                'car_make_id' => 14,
                'name' => '420 Gran Coupé',
            ),
            201 => 
            array (
                'id' => 202,
                'car_make_id' => 14,
                'name' => '425',
            ),
            202 => 
            array (
                'id' => 203,
                'car_make_id' => 14,
                'name' => '425 Gran Coupé',
            ),
            203 => 
            array (
                'id' => 204,
                'car_make_id' => 14,
                'name' => '428',
            ),
            204 => 
            array (
                'id' => 205,
                'car_make_id' => 14,
                'name' => '428 Gran Coupé',
            ),
            205 => 
            array (
                'id' => 206,
                'car_make_id' => 14,
                'name' => '430',
            ),
            206 => 
            array (
                'id' => 207,
                'car_make_id' => 14,
                'name' => '430 Gran Coupé',
            ),
            207 => 
            array (
                'id' => 208,
                'car_make_id' => 14,
                'name' => '435',
            ),
            208 => 
            array (
                'id' => 209,
                'car_make_id' => 14,
                'name' => '435 Gran Coupé',
            ),
            209 => 
            array (
                'id' => 210,
                'car_make_id' => 14,
                'name' => '440',
            ),
            210 => 
            array (
                'id' => 211,
                'car_make_id' => 14,
                'name' => '440 Gran Coupé',
            ),
            211 => 
            array (
                'id' => 212,
                'car_make_id' => 14,
                'name' => '518',
            ),
            212 => 
            array (
                'id' => 213,
                'car_make_id' => 14,
                'name' => '520',
            ),
            213 => 
            array (
                'id' => 214,
                'car_make_id' => 14,
                'name' => '520 Gran Turismo',
            ),
            214 => 
            array (
                'id' => 215,
                'car_make_id' => 14,
                'name' => '523',
            ),
            215 => 
            array (
                'id' => 216,
                'car_make_id' => 14,
                'name' => '524',
            ),
            216 => 
            array (
                'id' => 217,
                'car_make_id' => 14,
                'name' => '525',
            ),
            217 => 
            array (
                'id' => 218,
                'car_make_id' => 14,
                'name' => '528',
            ),
            218 => 
            array (
                'id' => 219,
                'car_make_id' => 14,
                'name' => '530',
            ),
            219 => 
            array (
                'id' => 220,
                'car_make_id' => 14,
                'name' => '530 Gran Turismo',
            ),
            220 => 
            array (
                'id' => 221,
                'car_make_id' => 14,
                'name' => '535',
            ),
            221 => 
            array (
                'id' => 222,
                'car_make_id' => 14,
                'name' => '535 Gran Turismo',
            ),
            222 => 
            array (
                'id' => 223,
                'car_make_id' => 14,
                'name' => '540',
            ),
            223 => 
            array (
                'id' => 224,
                'car_make_id' => 14,
                'name' => '545',
            ),
            224 => 
            array (
                'id' => 225,
                'car_make_id' => 14,
                'name' => '550',
            ),
            225 => 
            array (
                'id' => 226,
                'car_make_id' => 14,
                'name' => '550 Gran Turismo',
            ),
            226 => 
            array (
                'id' => 227,
                'car_make_id' => 14,
                'name' => 'ActiveHybrid 5',
            ),
            227 => 
            array (
                'id' => 228,
                'car_make_id' => 14,
                'name' => '620 Gran Turismo',
            ),
            228 => 
            array (
                'id' => 229,
                'car_make_id' => 14,
                'name' => '628',
            ),
            229 => 
            array (
                'id' => 230,
                'car_make_id' => 14,
                'name' => '630',
            ),
            230 => 
            array (
                'id' => 231,
                'car_make_id' => 14,
                'name' => '630 Gran Turismo',
            ),
            231 => 
            array (
                'id' => 232,
                'car_make_id' => 14,
                'name' => '633',
            ),
            232 => 
            array (
                'id' => 233,
                'car_make_id' => 14,
                'name' => '635',
            ),
            233 => 
            array (
                'id' => 234,
                'car_make_id' => 14,
                'name' => '640',
            ),
            234 => 
            array (
                'id' => 235,
                'car_make_id' => 14,
                'name' => '640 Gran Coupé',
            ),
            235 => 
            array (
                'id' => 236,
                'car_make_id' => 14,
                'name' => '640 Gran Turismo',
            ),
            236 => 
            array (
                'id' => 237,
                'car_make_id' => 14,
                'name' => '645',
            ),
            237 => 
            array (
                'id' => 238,
                'car_make_id' => 14,
                'name' => '650',
            ),
            238 => 
            array (
                'id' => 239,
                'car_make_id' => 14,
                'name' => '650 Gran Coupé',
            ),
            239 => 
            array (
                'id' => 240,
                'car_make_id' => 14,
                'name' => '725',
            ),
            240 => 
            array (
                'id' => 241,
                'car_make_id' => 14,
                'name' => '728',
            ),
            241 => 
            array (
                'id' => 242,
                'car_make_id' => 14,
                'name' => '730',
            ),
            242 => 
            array (
                'id' => 243,
                'car_make_id' => 14,
                'name' => '732',
            ),
            243 => 
            array (
                'id' => 244,
                'car_make_id' => 14,
                'name' => '735',
            ),
            244 => 
            array (
                'id' => 245,
                'car_make_id' => 14,
                'name' => '740',
            ),
            245 => 
            array (
                'id' => 246,
                'car_make_id' => 14,
                'name' => '745',
            ),
            246 => 
            array (
                'id' => 247,
                'car_make_id' => 14,
                'name' => '750',
            ),
            247 => 
            array (
                'id' => 248,
                'car_make_id' => 14,
                'name' => '760',
            ),
            248 => 
            array (
                'id' => 249,
                'car_make_id' => 14,
                'name' => 'ActiveHybrid 7',
            ),
            249 => 
            array (
                'id' => 250,
                'car_make_id' => 14,
                'name' => '840',
            ),
            250 => 
            array (
                'id' => 251,
                'car_make_id' => 14,
                'name' => '850',
            ),
            251 => 
            array (
                'id' => 252,
                'car_make_id' => 14,
                'name' => 'i3',
            ),
            252 => 
            array (
                'id' => 253,
                'car_make_id' => 14,
                'name' => 'i8',
            ),
            253 => 
            array (
                'id' => 254,
                'car_make_id' => 14,
                'name' => 'M135',
            ),
            254 => 
            array (
                'id' => 255,
                'car_make_id' => 14,
                'name' => 'M140i',
            ),
            255 => 
            array (
                'id' => 256,
                'car_make_id' => 14,
                'name' => 'M2',
            ),
            256 => 
            array (
                'id' => 257,
                'car_make_id' => 14,
                'name' => 'M235',
            ),
            257 => 
            array (
                'id' => 258,
                'car_make_id' => 14,
                'name' => 'M240i',
            ),
            258 => 
            array (
                'id' => 259,
                'car_make_id' => 14,
                'name' => 'M3',
            ),
            259 => 
            array (
                'id' => 260,
                'car_make_id' => 14,
                'name' => 'M340i',
            ),
            260 => 
            array (
                'id' => 261,
                'car_make_id' => 14,
                'name' => 'M4',
            ),
            261 => 
            array (
                'id' => 262,
                'car_make_id' => 14,
                'name' => 'M5',
            ),
            262 => 
            array (
                'id' => 263,
                'car_make_id' => 14,
                'name' => 'M550',
            ),
            263 => 
            array (
                'id' => 264,
                'car_make_id' => 14,
                'name' => 'M6',
            ),
            264 => 
            array (
                'id' => 265,
                'car_make_id' => 14,
                'name' => 'M760',
            ),
            265 => 
            array (
                'id' => 266,
                'car_make_id' => 14,
                'name' => 'M850',
            ),
            266 => 
            array (
                'id' => 267,
                'car_make_id' => 14,
                'name' => 'ActiveHybrid X6',
            ),
            267 => 
            array (
                'id' => 268,
                'car_make_id' => 14,
                'name' => 'X1',
            ),
            268 => 
            array (
                'id' => 269,
                'car_make_id' => 14,
                'name' => 'X2',
            ),
            269 => 
            array (
                'id' => 270,
                'car_make_id' => 14,
                'name' => 'X3',
            ),
            270 => 
            array (
                'id' => 271,
                'car_make_id' => 14,
                'name' => 'X3 M',
            ),
            271 => 
            array (
                'id' => 272,
                'car_make_id' => 14,
                'name' => 'X3 M40',
            ),
            272 => 
            array (
                'id' => 273,
                'car_make_id' => 14,
                'name' => 'X4',
            ),
            273 => 
            array (
                'id' => 274,
                'car_make_id' => 14,
                'name' => 'X4 M',
            ),
            274 => 
            array (
                'id' => 275,
                'car_make_id' => 14,
                'name' => 'X4 M40',
            ),
            275 => 
            array (
                'id' => 276,
                'car_make_id' => 14,
                'name' => 'X5',
            ),
            276 => 
            array (
                'id' => 277,
                'car_make_id' => 14,
                'name' => 'X5 M',
            ),
            277 => 
            array (
                'id' => 278,
                'car_make_id' => 14,
                'name' => 'X5 M50',
            ),
            278 => 
            array (
                'id' => 279,
                'car_make_id' => 14,
                'name' => 'X6',
            ),
            279 => 
            array (
                'id' => 280,
                'car_make_id' => 14,
                'name' => 'X6 M',
            ),
            280 => 
            array (
                'id' => 281,
                'car_make_id' => 14,
                'name' => 'X6 M50',
            ),
            281 => 
            array (
                'id' => 282,
                'car_make_id' => 14,
                'name' => 'X7',
            ),
            282 => 
            array (
                'id' => 283,
                'car_make_id' => 14,
                'name' => 'Z1',
            ),
            283 => 
            array (
                'id' => 284,
                'car_make_id' => 14,
                'name' => 'Z3',
            ),
            284 => 
            array (
                'id' => 285,
                'car_make_id' => 14,
                'name' => 'Z3 M',
            ),
            285 => 
            array (
                'id' => 286,
                'car_make_id' => 14,
                'name' => 'Z4',
            ),
            286 => 
            array (
                'id' => 287,
                'car_make_id' => 14,
                'name' => 'Z4 M',
            ),
            287 => 
            array (
                'id' => 288,
                'car_make_id' => 14,
                'name' => 'Z8',
            ),
            288 => 
            array (
                'id' => 289,
                'car_make_id' => 14,
                'name' => 'Other',
            ),
            289 => 
            array (
                'id' => 290,
                'car_make_id' => 15,
                'name' => 'Other',
            ),
            290 => 
            array (
                'id' => 291,
                'car_make_id' => 16,
                'name' => 'BC3',
            ),
            291 => 
            array (
                'id' => 292,
                'car_make_id' => 16,
                'name' => 'BS2',
            ),
            292 => 
            array (
                'id' => 293,
                'car_make_id' => 16,
                'name' => 'BS4',
            ),
            293 => 
            array (
                'id' => 294,
                'car_make_id' => 16,
                'name' => 'BS6',
            ),
            294 => 
            array (
                'id' => 295,
                'car_make_id' => 16,
                'name' => 'Other',
            ),
            295 => 
            array (
                'id' => 296,
                'car_make_id' => 17,
                'name' => 'Chiron',
            ),
            296 => 
            array (
                'id' => 297,
                'car_make_id' => 17,
                'name' => 'EB 110',
            ),
            297 => 
            array (
                'id' => 298,
                'car_make_id' => 17,
                'name' => 'Veyron',
            ),
            298 => 
            array (
                'id' => 299,
                'car_make_id' => 17,
                'name' => 'Other',
            ),
            299 => 
            array (
                'id' => 300,
                'car_make_id' => 18,
                'name' => 'Century',
            ),
            300 => 
            array (
                'id' => 301,
                'car_make_id' => 18,
                'name' => 'Electra',
            ),
            301 => 
            array (
                'id' => 302,
                'car_make_id' => 18,
                'name' => 'Enclave',
            ),
            302 => 
            array (
                'id' => 303,
                'car_make_id' => 18,
                'name' => 'La Crosse',
            ),
            303 => 
            array (
                'id' => 304,
                'car_make_id' => 18,
                'name' => 'Le Sabre',
            ),
            304 => 
            array (
                'id' => 305,
                'car_make_id' => 18,
                'name' => 'Park Avenue',
            ),
            305 => 
            array (
                'id' => 306,
                'car_make_id' => 18,
                'name' => 'Regal',
            ),
            306 => 
            array (
                'id' => 307,
                'car_make_id' => 18,
                'name' => 'Riviera',
            ),
            307 => 
            array (
                'id' => 308,
                'car_make_id' => 18,
                'name' => 'Roadmaster',
            ),
            308 => 
            array (
                'id' => 309,
                'car_make_id' => 18,
                'name' => 'Skylark',
            ),
            309 => 
            array (
                'id' => 310,
                'car_make_id' => 18,
                'name' => 'Other',
            ),
            310 => 
            array (
                'id' => 311,
                'car_make_id' => 19,
                'name' => 'Allante',
            ),
            311 => 
            array (
                'id' => 312,
                'car_make_id' => 19,
                'name' => 'ATS',
            ),
            312 => 
            array (
                'id' => 313,
                'car_make_id' => 19,
                'name' => 'BLS',
            ),
            313 => 
            array (
                'id' => 314,
                'car_make_id' => 19,
                'name' => 'CT6',
            ),
            314 => 
            array (
                'id' => 315,
                'car_make_id' => 19,
                'name' => 'CTS',
            ),
            315 => 
            array (
                'id' => 316,
                'car_make_id' => 19,
                'name' => 'Deville',
            ),
            316 => 
            array (
                'id' => 317,
                'car_make_id' => 19,
                'name' => 'Eldorado',
            ),
            317 => 
            array (
                'id' => 318,
                'car_make_id' => 19,
                'name' => 'Escalade',
            ),
            318 => 
            array (
                'id' => 319,
                'car_make_id' => 19,
                'name' => 'Fleetwood',
            ),
            319 => 
            array (
                'id' => 320,
                'car_make_id' => 19,
                'name' => 'Seville',
            ),
            320 => 
            array (
                'id' => 321,
                'car_make_id' => 19,
                'name' => 'SRX',
            ),
            321 => 
            array (
                'id' => 322,
                'car_make_id' => 19,
                'name' => 'STS',
            ),
            322 => 
            array (
                'id' => 323,
                'car_make_id' => 19,
                'name' => 'XLR',
            ),
            323 => 
            array (
                'id' => 324,
                'car_make_id' => 19,
                'name' => 'XT5',
            ),
            324 => 
            array (
                'id' => 325,
                'car_make_id' => 19,
                'name' => 'Other',
            ),
            325 => 
            array (
                'id' => 326,
                'car_make_id' => 20,
                'name' => 'Other',
            ),
            326 => 
            array (
                'id' => 327,
                'car_make_id' => 21,
                'name' => 'Other',
            ),
            327 => 
            array (
                'id' => 328,
                'car_make_id' => 22,
                'name' => 'Other',
            ),
            328 => 
            array (
                'id' => 329,
                'car_make_id' => 23,
                'name' => '2500',
            ),
            329 => 
            array (
                'id' => 330,
                'car_make_id' => 23,
                'name' => 'Alero',
            ),
            330 => 
            array (
                'id' => 331,
                'car_make_id' => 23,
                'name' => 'Astro',
            ),
            331 => 
            array (
                'id' => 332,
                'car_make_id' => 23,
                'name' => 'Avalanche',
            ),
            332 => 
            array (
                'id' => 333,
                'car_make_id' => 23,
                'name' => 'Aveo',
            ),
            333 => 
            array (
                'id' => 334,
                'car_make_id' => 23,
                'name' => 'Beretta',
            ),
            334 => 
            array (
                'id' => 335,
                'car_make_id' => 23,
                'name' => 'Blazer',
            ),
            335 => 
            array (
                'id' => 336,
                'car_make_id' => 23,
                'name' => 'C1500',
            ),
            336 => 
            array (
                'id' => 337,
                'car_make_id' => 23,
                'name' => 'Camaro',
            ),
            337 => 
            array (
                'id' => 338,
                'car_make_id' => 23,
                'name' => 'Caprice',
            ),
            338 => 
            array (
                'id' => 339,
                'car_make_id' => 23,
                'name' => 'Captiva',
            ),
            339 => 
            array (
                'id' => 340,
                'car_make_id' => 23,
                'name' => 'Cavalier',
            ),
            340 => 
            array (
                'id' => 341,
                'car_make_id' => 23,
                'name' => 'Chevelle',
            ),
            341 => 
            array (
                'id' => 342,
                'car_make_id' => 23,
                'name' => 'Chevy Van',
            ),
            342 => 
            array (
                'id' => 343,
                'car_make_id' => 23,
                'name' => 'Citation',
            ),
            343 => 
            array (
                'id' => 344,
                'car_make_id' => 23,
                'name' => 'Colorado',
            ),
            344 => 
            array (
                'id' => 345,
                'car_make_id' => 23,
                'name' => 'Corsica',
            ),
            345 => 
            array (
                'id' => 346,
                'car_make_id' => 23,
                'name' => 'Cruze',
            ),
            346 => 
            array (
                'id' => 347,
                'car_make_id' => 23,
                'name' => 'El Camino',
            ),
            347 => 
            array (
                'id' => 348,
                'car_make_id' => 23,
                'name' => 'Epica',
            ),
            348 => 
            array (
                'id' => 349,
                'car_make_id' => 23,
                'name' => 'Evanda',
            ),
            349 => 
            array (
                'id' => 350,
                'car_make_id' => 23,
                'name' => 'Express',
            ),
            350 => 
            array (
                'id' => 351,
                'car_make_id' => 23,
                'name' => 'G',
            ),
            351 => 
            array (
                'id' => 352,
                'car_make_id' => 23,
                'name' => 'HHR',
            ),
            352 => 
            array (
                'id' => 353,
                'car_make_id' => 23,
                'name' => 'Impala',
            ),
            353 => 
            array (
                'id' => 354,
                'car_make_id' => 23,
                'name' => 'K1500',
            ),
            354 => 
            array (
                'id' => 355,
                'car_make_id' => 23,
                'name' => 'K30',
            ),
            355 => 
            array (
                'id' => 356,
                'car_make_id' => 23,
                'name' => 'Kalos',
            ),
            356 => 
            array (
                'id' => 357,
                'car_make_id' => 23,
                'name' => 'Lacetti',
            ),
            357 => 
            array (
                'id' => 358,
                'car_make_id' => 23,
                'name' => 'Lumina',
            ),
            358 => 
            array (
                'id' => 359,
                'car_make_id' => 23,
                'name' => 'Malibu',
            ),
            359 => 
            array (
                'id' => 360,
                'car_make_id' => 23,
                'name' => 'Matiz',
            ),
            360 => 
            array (
                'id' => 361,
                'car_make_id' => 23,
                'name' => 'Niva',
            ),
            361 => 
            array (
                'id' => 362,
                'car_make_id' => 23,
                'name' => 'Nubira',
            ),
            362 => 
            array (
                'id' => 363,
                'car_make_id' => 23,
                'name' => 'Orlando',
            ),
            363 => 
            array (
                'id' => 364,
                'car_make_id' => 23,
                'name' => 'Rezzo',
            ),
            364 => 
            array (
                'id' => 365,
                'car_make_id' => 23,
                'name' => 'S-10',
            ),
            365 => 
            array (
                'id' => 366,
                'car_make_id' => 23,
                'name' => 'Silverado',
            ),
            366 => 
            array (
                'id' => 367,
                'car_make_id' => 23,
                'name' => 'Spark',
            ),
            367 => 
            array (
                'id' => 368,
                'car_make_id' => 23,
                'name' => 'SSR',
            ),
            368 => 
            array (
                'id' => 369,
                'car_make_id' => 23,
                'name' => 'Suburban',
            ),
            369 => 
            array (
                'id' => 370,
                'car_make_id' => 23,
                'name' => 'Tahoe',
            ),
            370 => 
            array (
                'id' => 371,
                'car_make_id' => 23,
                'name' => 'Trailblazer',
            ),
            371 => 
            array (
                'id' => 372,
                'car_make_id' => 23,
                'name' => 'Trans Sport',
            ),
            372 => 
            array (
                'id' => 373,
                'car_make_id' => 23,
                'name' => 'Traverse',
            ),
            373 => 
            array (
                'id' => 374,
                'car_make_id' => 23,
                'name' => 'Trax',
            ),
            374 => 
            array (
                'id' => 375,
                'car_make_id' => 23,
                'name' => 'Venture',
            ),
            375 => 
            array (
                'id' => 376,
                'car_make_id' => 23,
                'name' => 'Volt',
            ),
            376 => 
            array (
                'id' => 377,
                'car_make_id' => 23,
                'name' => 'Other',
            ),
            377 => 
            array (
                'id' => 378,
                'car_make_id' => 24,
                'name' => '200',
            ),
            378 => 
            array (
                'id' => 379,
                'car_make_id' => 24,
                'name' => '300C',
            ),
            379 => 
            array (
                'id' => 380,
                'car_make_id' => 24,
                'name' => '300 M',
            ),
            380 => 
            array (
                'id' => 381,
                'car_make_id' => 24,
                'name' => 'Aspen',
            ),
            381 => 
            array (
                'id' => 382,
                'car_make_id' => 24,
                'name' => 'Crossfire',
            ),
            382 => 
            array (
                'id' => 383,
                'car_make_id' => 24,
                'name' => 'Daytona',
            ),
            383 => 
            array (
                'id' => 384,
                'car_make_id' => 24,
                'name' => 'ES',
            ),
            384 => 
            array (
                'id' => 385,
                'car_make_id' => 24,
                'name' => 'Grand Voyager',
            ),
            385 => 
            array (
                'id' => 386,
                'car_make_id' => 24,
                'name' => 'GS',
            ),
            386 => 
            array (
                'id' => 387,
                'car_make_id' => 24,
                'name' => 'GTS',
            ),
            387 => 
            array (
                'id' => 388,
                'car_make_id' => 24,
                'name' => 'Imperial',
            ),
            388 => 
            array (
                'id' => 389,
                'car_make_id' => 24,
                'name' => 'Le Baron',
            ),
            389 => 
            array (
                'id' => 390,
                'car_make_id' => 24,
                'name' => 'Neon',
            ),
            390 => 
            array (
                'id' => 391,
                'car_make_id' => 24,
                'name' => 'New Yorker',
            ),
            391 => 
            array (
                'id' => 392,
                'car_make_id' => 24,
                'name' => 'Pacifica',
            ),
            392 => 
            array (
                'id' => 393,
                'car_make_id' => 24,
                'name' => 'PT Cruiser',
            ),
            393 => 
            array (
                'id' => 394,
                'car_make_id' => 24,
                'name' => 'Saratoga',
            ),
            394 => 
            array (
                'id' => 395,
                'car_make_id' => 24,
                'name' => 'Sebring',
            ),
            395 => 
            array (
                'id' => 396,
                'car_make_id' => 24,
                'name' => 'Stratus',
            ),
            396 => 
            array (
                'id' => 397,
                'car_make_id' => 24,
                'name' => 'Valiant',
            ),
            397 => 
            array (
                'id' => 398,
                'car_make_id' => 24,
                'name' => 'Viper',
            ),
            398 => 
            array (
                'id' => 399,
                'car_make_id' => 24,
                'name' => 'Vision',
            ),
            399 => 
            array (
                'id' => 400,
                'car_make_id' => 24,
                'name' => 'Voyager',
            ),
            400 => 
            array (
                'id' => 401,
                'car_make_id' => 24,
                'name' => 'Other',
            ),
            401 => 
            array (
                'id' => 402,
                'car_make_id' => 25,
                'name' => '2 CV',
            ),
            402 => 
            array (
                'id' => 403,
                'car_make_id' => 25,
                'name' => 'AX',
            ),
            403 => 
            array (
                'id' => 404,
                'car_make_id' => 25,
                'name' => 'Berlingo',
            ),
            404 => 
            array (
                'id' => 405,
                'car_make_id' => 25,
                'name' => 'BX',
            ),
            405 => 
            array (
                'id' => 406,
                'car_make_id' => 25,
                'name' => 'C1',
            ),
            406 => 
            array (
                'id' => 407,
                'car_make_id' => 25,
                'name' => 'C2',
            ),
            407 => 
            array (
                'id' => 408,
                'car_make_id' => 25,
                'name' => 'C3',
            ),
            408 => 
            array (
                'id' => 409,
                'car_make_id' => 25,
                'name' => 'C3 Aircross',
            ),
            409 => 
            array (
                'id' => 410,
                'car_make_id' => 25,
                'name' => 'C3 Picasso',
            ),
            410 => 
            array (
                'id' => 411,
                'car_make_id' => 25,
                'name' => 'C4',
            ),
            411 => 
            array (
                'id' => 412,
                'car_make_id' => 25,
                'name' => 'C4 Aircross',
            ),
            412 => 
            array (
                'id' => 413,
                'car_make_id' => 25,
                'name' => 'C4 Cactus',
            ),
            413 => 
            array (
                'id' => 414,
                'car_make_id' => 25,
                'name' => 'C4 Picasso',
            ),
            414 => 
            array (
                'id' => 415,
                'car_make_id' => 25,
                'name' => 'C4 SpaceTourer',
            ),
            415 => 
            array (
                'id' => 416,
                'car_make_id' => 25,
                'name' => 'C5',
            ),
            416 => 
            array (
                'id' => 417,
                'car_make_id' => 25,
                'name' => 'C5 Aircross',
            ),
            417 => 
            array (
                'id' => 418,
                'car_make_id' => 25,
                'name' => 'C6',
            ),
            418 => 
            array (
                'id' => 419,
                'car_make_id' => 25,
                'name' => 'C8',
            ),
            419 => 
            array (
                'id' => 420,
                'car_make_id' => 25,
                'name' => 'C-Crosser',
            ),
            420 => 
            array (
                'id' => 421,
                'car_make_id' => 25,
                'name' => 'C-Elysée',
            ),
            421 => 
            array (
                'id' => 422,
                'car_make_id' => 25,
                'name' => 'CX',
            ),
            422 => 
            array (
                'id' => 423,
                'car_make_id' => 25,
                'name' => 'C-Zero',
            ),
            423 => 
            array (
                'id' => 424,
                'car_make_id' => 25,
                'name' => 'DS',
            ),
            424 => 
            array (
                'id' => 425,
                'car_make_id' => 25,
                'name' => 'DS3',
            ),
            425 => 
            array (
                'id' => 426,
                'car_make_id' => 25,
                'name' => 'DS4',
            ),
            426 => 
            array (
                'id' => 427,
                'car_make_id' => 25,
                'name' => 'DS4 Crossback',
            ),
            427 => 
            array (
                'id' => 428,
                'car_make_id' => 25,
                'name' => 'DS5',
            ),
            428 => 
            array (
                'id' => 429,
                'car_make_id' => 25,
                'name' => 'E-MEHARI',
            ),
            429 => 
            array (
                'id' => 430,
                'car_make_id' => 25,
                'name' => 'Evasion',
            ),
            430 => 
            array (
                'id' => 431,
                'car_make_id' => 25,
                'name' => 'Grand C4 Picasso / SpaceTourer',
            ),
            431 => 
            array (
                'id' => 432,
                'car_make_id' => 25,
                'name' => 'GSA',
            ),
            432 => 
            array (
                'id' => 433,
                'car_make_id' => 25,
                'name' => 'Jumper',
            ),
            433 => 
            array (
                'id' => 434,
                'car_make_id' => 25,
                'name' => 'Jumpy',
            ),
            434 => 
            array (
                'id' => 435,
                'car_make_id' => 25,
                'name' => 'Nemo',
            ),
            435 => 
            array (
                'id' => 436,
                'car_make_id' => 25,
                'name' => 'SAXO',
            ),
            436 => 
            array (
                'id' => 437,
                'car_make_id' => 25,
                'name' => 'SM',
            ),
            437 => 
            array (
                'id' => 438,
                'car_make_id' => 25,
                'name' => 'SpaceTourer',
            ),
            438 => 
            array (
                'id' => 439,
                'car_make_id' => 25,
                'name' => 'Visa',
            ),
            439 => 
            array (
                'id' => 440,
                'car_make_id' => 25,
                'name' => 'Xantia',
            ),
            440 => 
            array (
                'id' => 441,
                'car_make_id' => 25,
                'name' => 'XM',
            ),
            441 => 
            array (
                'id' => 442,
                'car_make_id' => 25,
                'name' => 'Xsara',
            ),
            442 => 
            array (
                'id' => 443,
                'car_make_id' => 25,
                'name' => 'Xsara Picasso',
            ),
            443 => 
            array (
                'id' => 444,
                'car_make_id' => 25,
                'name' => 'ZX',
            ),
            444 => 
            array (
                'id' => 445,
                'car_make_id' => 25,
                'name' => 'Other',
            ),
            445 => 
            array (
                'id' => 446,
                'car_make_id' => 26,
                'name' => 'Other',
            ),
            446 => 
            array (
                'id' => 447,
                'car_make_id' => 27,
                'name' => 'C1',
            ),
            447 => 
            array (
                'id' => 448,
                'car_make_id' => 27,
                'name' => 'C2',
            ),
            448 => 
            array (
                'id' => 449,
                'car_make_id' => 27,
                'name' => 'C3',
            ),
            449 => 
            array (
                'id' => 450,
                'car_make_id' => 27,
                'name' => 'C4',
            ),
            450 => 
            array (
                'id' => 451,
                'car_make_id' => 27,
                'name' => 'C5',
            ),
            451 => 
            array (
                'id' => 452,
                'car_make_id' => 27,
                'name' => 'C6',
            ),
            452 => 
            array (
                'id' => 453,
                'car_make_id' => 27,
                'name' => 'C7',
            ),
            453 => 
            array (
                'id' => 454,
                'car_make_id' => 27,
                'name' => 'C8',
            ),
            454 => 
            array (
                'id' => 455,
                'car_make_id' => 27,
                'name' => 'Z06',
            ),
            455 => 
            array (
                'id' => 456,
                'car_make_id' => 27,
                'name' => 'ZR 1',
            ),
            456 => 
            array (
                'id' => 457,
                'car_make_id' => 27,
                'name' => 'Other',
            ),
            457 => 
            array (
                'id' => 458,
                'car_make_id' => 28,
                'name' => 'Arona',
            ),
            458 => 
            array (
                'id' => 459,
                'car_make_id' => 28,
                'name' => 'Ateca',
            ),
            459 => 
            array (
                'id' => 460,
                'car_make_id' => 28,
                'name' => 'Ibiza',
            ),
            460 => 
            array (
                'id' => 461,
                'car_make_id' => 28,
                'name' => 'Other',
            ),
            461 => 
            array (
                'id' => 462,
                'car_make_id' => 29,
                'name' => 'Dokker',
            ),
            462 => 
            array (
                'id' => 463,
                'car_make_id' => 29,
                'name' => 'Duster',
            ),
            463 => 
            array (
                'id' => 464,
                'car_make_id' => 29,
                'name' => 'Lodgy',
            ),
            464 => 
            array (
                'id' => 465,
                'car_make_id' => 29,
                'name' => 'Logan',
            ),
            465 => 
            array (
                'id' => 466,
                'car_make_id' => 29,
                'name' => 'Logan Pick-Up',
            ),
            466 => 
            array (
                'id' => 467,
                'car_make_id' => 29,
                'name' => 'Pick Up',
            ),
            467 => 
            array (
                'id' => 468,
                'car_make_id' => 29,
                'name' => 'Sandero',
            ),
            468 => 
            array (
                'id' => 469,
                'car_make_id' => 29,
                'name' => 'Other',
            ),
            469 => 
            array (
                'id' => 470,
                'car_make_id' => 30,
                'name' => 'Espero',
            ),
            470 => 
            array (
                'id' => 471,
                'car_make_id' => 30,
                'name' => 'Evanda',
            ),
            471 => 
            array (
                'id' => 472,
                'car_make_id' => 30,
                'name' => 'Kalos',
            ),
            472 => 
            array (
                'id' => 473,
                'car_make_id' => 30,
                'name' => 'Korando',
            ),
            473 => 
            array (
                'id' => 474,
                'car_make_id' => 30,
                'name' => 'Lacetti',
            ),
            474 => 
            array (
                'id' => 475,
                'car_make_id' => 30,
                'name' => 'Lanos',
            ),
            475 => 
            array (
                'id' => 476,
                'car_make_id' => 30,
                'name' => 'Leganza',
            ),
            476 => 
            array (
                'id' => 477,
                'car_make_id' => 30,
                'name' => 'Matiz',
            ),
            477 => 
            array (
                'id' => 478,
                'car_make_id' => 30,
                'name' => 'Musso',
            ),
            478 => 
            array (
                'id' => 479,
                'car_make_id' => 30,
                'name' => 'Nexia',
            ),
            479 => 
            array (
                'id' => 480,
                'car_make_id' => 30,
                'name' => 'Nubira',
            ),
            480 => 
            array (
                'id' => 481,
                'car_make_id' => 30,
                'name' => 'Rezzo',
            ),
            481 => 
            array (
                'id' => 482,
                'car_make_id' => 30,
                'name' => 'Tacuma',
            ),
            482 => 
            array (
                'id' => 483,
                'car_make_id' => 30,
                'name' => 'Other',
            ),
            483 => 
            array (
                'id' => 484,
                'car_make_id' => 31,
                'name' => 'Applause',
            ),
            484 => 
            array (
                'id' => 485,
                'car_make_id' => 31,
                'name' => 'Charade',
            ),
            485 => 
            array (
                'id' => 486,
                'car_make_id' => 31,
                'name' => 'Charmant',
            ),
            486 => 
            array (
                'id' => 487,
                'car_make_id' => 31,
                'name' => 'Copen',
            ),
            487 => 
            array (
                'id' => 488,
                'car_make_id' => 31,
                'name' => 'Cuore',
            ),
            488 => 
            array (
                'id' => 489,
                'car_make_id' => 31,
                'name' => 'Feroza/Sportrak',
            ),
            489 => 
            array (
                'id' => 490,
                'car_make_id' => 31,
                'name' => 'Freeclimber',
            ),
            490 => 
            array (
                'id' => 491,
                'car_make_id' => 31,
                'name' => 'Gran Move',
            ),
            491 => 
            array (
                'id' => 492,
                'car_make_id' => 31,
                'name' => 'Hijet',
            ),
            492 => 
            array (
                'id' => 493,
                'car_make_id' => 31,
                'name' => 'MATERIA',
            ),
            493 => 
            array (
                'id' => 494,
                'car_make_id' => 31,
                'name' => 'Move',
            ),
            494 => 
            array (
                'id' => 495,
                'car_make_id' => 31,
                'name' => 'Rocky/Fourtrak',
            ),
            495 => 
            array (
                'id' => 496,
                'car_make_id' => 31,
                'name' => 'Sirion',
            ),
            496 => 
            array (
                'id' => 497,
                'car_make_id' => 31,
                'name' => 'Terios',
            ),
            497 => 
            array (
                'id' => 498,
                'car_make_id' => 31,
                'name' => 'TREVIS',
            ),
            498 => 
            array (
                'id' => 499,
                'car_make_id' => 31,
                'name' => 'YRV',
            ),
            499 => 
            array (
                'id' => 500,
                'car_make_id' => 31,
                'name' => 'Other',
            ),
        ));
        \DB::table('car_models')->insert(array (
            0 => 
            array (
                'id' => 501,
                'car_make_id' => 32,
                'name' => 'Guarà',
            ),
            1 => 
            array (
                'id' => 502,
                'car_make_id' => 32,
                'name' => 'Pantera',
            ),
            2 => 
            array (
                'id' => 503,
                'car_make_id' => 32,
                'name' => 'Other',
            ),
            3 => 
            array (
                'id' => 504,
                'car_make_id' => 33,
                'name' => 'Avenger',
            ),
            4 => 
            array (
                'id' => 505,
                'car_make_id' => 33,
                'name' => 'Caliber',
            ),
            5 => 
            array (
                'id' => 506,
                'car_make_id' => 33,
                'name' => 'Challenger',
            ),
            6 => 
            array (
                'id' => 507,
                'car_make_id' => 33,
                'name' => 'Charger',
            ),
            7 => 
            array (
                'id' => 508,
                'car_make_id' => 33,
                'name' => 'Dakota',
            ),
            8 => 
            array (
                'id' => 509,
                'car_make_id' => 33,
                'name' => 'Dart',
            ),
            9 => 
            array (
                'id' => 510,
                'car_make_id' => 33,
                'name' => 'Demon',
            ),
            10 => 
            array (
                'id' => 511,
                'car_make_id' => 33,
                'name' => 'Durango',
            ),
            11 => 
            array (
                'id' => 512,
                'car_make_id' => 33,
                'name' => 'Grand Caravan',
            ),
            12 => 
            array (
                'id' => 513,
                'car_make_id' => 33,
                'name' => 'Hornet',
            ),
            13 => 
            array (
                'id' => 514,
                'car_make_id' => 33,
                'name' => 'Journey',
            ),
            14 => 
            array (
                'id' => 515,
                'car_make_id' => 33,
                'name' => 'Magnum',
            ),
            15 => 
            array (
                'id' => 516,
                'car_make_id' => 33,
                'name' => 'Neon',
            ),
            16 => 
            array (
                'id' => 517,
                'car_make_id' => 33,
                'name' => 'Nitro',
            ),
            17 => 
            array (
                'id' => 518,
                'car_make_id' => 33,
                'name' => 'RAM',
            ),
            18 => 
            array (
                'id' => 519,
                'car_make_id' => 33,
                'name' => 'Stealth',
            ),
            19 => 
            array (
                'id' => 520,
                'car_make_id' => 33,
                'name' => 'Viper',
            ),
            20 => 
            array (
                'id' => 521,
                'car_make_id' => 33,
                'name' => 'Other',
            ),
            21 => 
            array (
                'id' => 522,
                'car_make_id' => 34,
                'name' => 'D8',
            ),
            22 => 
            array (
                'id' => 523,
                'car_make_id' => 34,
                'name' => 'S7',
            ),
            23 => 
            array (
                'id' => 524,
                'car_make_id' => 34,
                'name' => 'S8',
            ),
            24 => 
            array (
                'id' => 525,
                'car_make_id' => 34,
                'name' => 'Other',
            ),
            25 => 
            array (
                'id' => 526,
                'car_make_id' => 35,
                'name' => 'DS3',
            ),
            26 => 
            array (
                'id' => 527,
                'car_make_id' => 35,
                'name' => 'DS3 Crossback',
            ),
            27 => 
            array (
                'id' => 528,
                'car_make_id' => 35,
                'name' => 'DS4',
            ),
            28 => 
            array (
                'id' => 529,
                'car_make_id' => 35,
                'name' => 'DS4 Crossback',
            ),
            29 => 
            array (
                'id' => 530,
                'car_make_id' => 35,
                'name' => 'DS5',
            ),
            30 => 
            array (
                'id' => 531,
                'car_make_id' => 35,
                'name' => 'DS7 Crossback',
            ),
            31 => 
            array (
                'id' => 532,
                'car_make_id' => 35,
                'name' => 'Other',
            ),
            32 => 
            array (
                'id' => 533,
                'car_make_id' => 36,
                'name' => '208',
            ),
            33 => 
            array (
                'id' => 534,
                'car_make_id' => 36,
                'name' => '246',
            ),
            34 => 
            array (
                'id' => 535,
                'car_make_id' => 36,
                'name' => '250',
            ),
            35 => 
            array (
                'id' => 536,
                'car_make_id' => 36,
                'name' => '275',
            ),
            36 => 
            array (
                'id' => 537,
                'car_make_id' => 36,
                'name' => '288',
            ),
            37 => 
            array (
                'id' => 538,
                'car_make_id' => 36,
                'name' => '308',
            ),
            38 => 
            array (
                'id' => 539,
                'car_make_id' => 36,
                'name' => '328',
            ),
            39 => 
            array (
                'id' => 540,
                'car_make_id' => 36,
                'name' => '330',
            ),
            40 => 
            array (
                'id' => 541,
                'car_make_id' => 36,
                'name' => '348',
            ),
            41 => 
            array (
                'id' => 542,
                'car_make_id' => 36,
                'name' => '360',
            ),
            42 => 
            array (
                'id' => 543,
                'car_make_id' => 36,
                'name' => '365',
            ),
            43 => 
            array (
                'id' => 544,
                'car_make_id' => 36,
                'name' => '400',
            ),
            44 => 
            array (
                'id' => 545,
                'car_make_id' => 36,
                'name' => '412',
            ),
            45 => 
            array (
                'id' => 546,
                'car_make_id' => 36,
                'name' => '456',
            ),
            46 => 
            array (
                'id' => 547,
                'car_make_id' => 36,
                'name' => '458',
            ),
            47 => 
            array (
                'id' => 548,
                'car_make_id' => 36,
                'name' => '488 GTB',
            ),
            48 => 
            array (
                'id' => 549,
                'car_make_id' => 36,
                'name' => '488 Pista',
            ),
            49 => 
            array (
                'id' => 550,
                'car_make_id' => 36,
                'name' => '488 Spider',
            ),
            50 => 
            array (
                'id' => 551,
                'car_make_id' => 36,
                'name' => '512',
            ),
            51 => 
            array (
                'id' => 552,
                'car_make_id' => 36,
                'name' => '550',
            ),
            52 => 
            array (
                'id' => 553,
                'car_make_id' => 36,
                'name' => '575',
            ),
            53 => 
            array (
                'id' => 554,
                'car_make_id' => 36,
                'name' => '599 GTB',
            ),
            54 => 
            array (
                'id' => 555,
                'car_make_id' => 36,
                'name' => '599 GTO',
            ),
            55 => 
            array (
                'id' => 556,
                'car_make_id' => 36,
                'name' => '599 SA Aperta',
            ),
            56 => 
            array (
                'id' => 557,
                'car_make_id' => 36,
                'name' => '612',
            ),
            57 => 
            array (
                'id' => 558,
                'car_make_id' => 36,
                'name' => '750',
            ),
            58 => 
            array (
                'id' => 559,
                'car_make_id' => 36,
                'name' => '812',
            ),
            59 => 
            array (
                'id' => 560,
                'car_make_id' => 36,
                'name' => 'California',
            ),
            60 => 
            array (
                'id' => 561,
                'car_make_id' => 36,
                'name' => 'Daytona',
            ),
            61 => 
            array (
                'id' => 562,
                'car_make_id' => 36,
                'name' => 'Dino GT4',
            ),
            62 => 
            array (
                'id' => 563,
                'car_make_id' => 36,
                'name' => 'Enzo Ferrari',
            ),
            63 => 
            array (
                'id' => 564,
                'car_make_id' => 36,
                'name' => 'F12',
            ),
            64 => 
            array (
                'id' => 565,
                'car_make_id' => 36,
                'name' => 'F355',
            ),
            65 => 
            array (
                'id' => 566,
                'car_make_id' => 36,
                'name' => 'F40',
            ),
            66 => 
            array (
                'id' => 567,
                'car_make_id' => 36,
                'name' => 'F430',
            ),
            67 => 
            array (
                'id' => 568,
                'car_make_id' => 36,
                'name' => 'F50',
            ),
            68 => 
            array (
                'id' => 569,
                'car_make_id' => 36,
                'name' => 'FF',
            ),
            69 => 
            array (
                'id' => 570,
                'car_make_id' => 36,
                'name' => 'GTC4Lusso',
            ),
            70 => 
            array (
                'id' => 571,
                'car_make_id' => 36,
                'name' => 'LaFerrari',
            ),
            71 => 
            array (
                'id' => 572,
                'car_make_id' => 36,
                'name' => 'Mondial',
            ),
            72 => 
            array (
                'id' => 573,
                'car_make_id' => 36,
                'name' => 'Portofino',
            ),
            73 => 
            array (
                'id' => 574,
                'car_make_id' => 36,
                'name' => 'Superamerica',
            ),
            74 => 
            array (
                'id' => 575,
                'car_make_id' => 36,
                'name' => 'Testarossa',
            ),
            75 => 
            array (
                'id' => 576,
                'car_make_id' => 36,
                'name' => 'Other',
            ),
            76 => 
            array (
                'id' => 577,
                'car_make_id' => 37,
                'name' => '124',
            ),
            77 => 
            array (
                'id' => 578,
                'car_make_id' => 37,
                'name' => '124 Spider',
            ),
            78 => 
            array (
                'id' => 579,
                'car_make_id' => 37,
                'name' => '126',
            ),
            79 => 
            array (
                'id' => 580,
                'car_make_id' => 37,
                'name' => '127',
            ),
            80 => 
            array (
                'id' => 581,
                'car_make_id' => 37,
                'name' => '130',
            ),
            81 => 
            array (
                'id' => 582,
                'car_make_id' => 37,
                'name' => '131',
            ),
            82 => 
            array (
                'id' => 583,
                'car_make_id' => 37,
                'name' => '500',
            ),
            83 => 
            array (
                'id' => 584,
                'car_make_id' => 37,
                'name' => '500C',
            ),
            84 => 
            array (
                'id' => 585,
                'car_make_id' => 37,
                'name' => '500L',
            ),
            85 => 
            array (
                'id' => 586,
                'car_make_id' => 37,
                'name' => '500L Cross',
            ),
            86 => 
            array (
                'id' => 587,
                'car_make_id' => 37,
                'name' => '500L Living',
            ),
            87 => 
            array (
                'id' => 588,
                'car_make_id' => 37,
                'name' => '500L Trekking',
            ),
            88 => 
            array (
                'id' => 589,
                'car_make_id' => 37,
                'name' => '500L Urban',
            ),
            89 => 
            array (
                'id' => 590,
                'car_make_id' => 37,
                'name' => '500L Wagon',
            ),
            90 => 
            array (
                'id' => 591,
                'car_make_id' => 37,
                'name' => '500S',
            ),
            91 => 
            array (
                'id' => 592,
                'car_make_id' => 37,
                'name' => '500X',
            ),
            92 => 
            array (
                'id' => 593,
                'car_make_id' => 37,
                'name' => 'Albea',
            ),
            93 => 
            array (
                'id' => 594,
                'car_make_id' => 37,
                'name' => 'Barchetta',
            ),
            94 => 
            array (
                'id' => 595,
                'car_make_id' => 37,
                'name' => 'Brava',
            ),
            95 => 
            array (
                'id' => 596,
                'car_make_id' => 37,
                'name' => 'Bravo',
            ),
            96 => 
            array (
                'id' => 597,
                'car_make_id' => 37,
                'name' => 'Cinquecento',
            ),
            97 => 
            array (
                'id' => 598,
                'car_make_id' => 37,
                'name' => 'Coupe',
            ),
            98 => 
            array (
                'id' => 599,
                'car_make_id' => 37,
                'name' => 'Croma',
            ),
            99 => 
            array (
                'id' => 600,
                'car_make_id' => 37,
                'name' => 'Dino',
            ),
            100 => 
            array (
                'id' => 601,
                'car_make_id' => 37,
                'name' => 'Doblo',
            ),
            101 => 
            array (
                'id' => 602,
                'car_make_id' => 37,
                'name' => 'Ducato',
            ),
            102 => 
            array (
                'id' => 603,
                'car_make_id' => 37,
                'name' => 'Fiorino',
            ),
            103 => 
            array (
                'id' => 604,
                'car_make_id' => 37,
                'name' => 'Freemont',
            ),
            104 => 
            array (
                'id' => 605,
                'car_make_id' => 37,
                'name' => 'Fullback',
            ),
            105 => 
            array (
                'id' => 606,
                'car_make_id' => 37,
                'name' => 'Grande Punto',
            ),
            106 => 
            array (
                'id' => 607,
                'car_make_id' => 37,
                'name' => 'Idea',
            ),
            107 => 
            array (
                'id' => 608,
                'car_make_id' => 37,
                'name' => 'Linea',
            ),
            108 => 
            array (
                'id' => 609,
                'car_make_id' => 37,
                'name' => 'Marea',
            ),
            109 => 
            array (
                'id' => 610,
                'car_make_id' => 37,
                'name' => 'Marengo',
            ),
            110 => 
            array (
                'id' => 611,
                'car_make_id' => 37,
                'name' => 'Multipla',
            ),
            111 => 
            array (
                'id' => 612,
                'car_make_id' => 37,
                'name' => 'New Panda',
            ),
            112 => 
            array (
                'id' => 613,
                'car_make_id' => 37,
                'name' => 'Palio',
            ),
            113 => 
            array (
                'id' => 614,
                'car_make_id' => 37,
                'name' => 'Panda',
            ),
            114 => 
            array (
                'id' => 615,
                'car_make_id' => 37,
                'name' => 'Punto',
            ),
            115 => 
            array (
                'id' => 616,
                'car_make_id' => 37,
                'name' => 'Punto Evo',
            ),
            116 => 
            array (
                'id' => 617,
                'car_make_id' => 37,
                'name' => 'Qubo',
            ),
            117 => 
            array (
                'id' => 618,
                'car_make_id' => 37,
                'name' => 'Regata',
            ),
            118 => 
            array (
                'id' => 619,
                'car_make_id' => 37,
                'name' => 'Ritmo',
            ),
            119 => 
            array (
                'id' => 620,
                'car_make_id' => 37,
                'name' => 'Scudo',
            ),
            120 => 
            array (
                'id' => 621,
                'car_make_id' => 37,
                'name' => 'Sedici',
            ),
            121 => 
            array (
                'id' => 622,
                'car_make_id' => 37,
                'name' => 'Seicento',
            ),
            122 => 
            array (
                'id' => 623,
                'car_make_id' => 37,
                'name' => 'Siena',
            ),
            123 => 
            array (
                'id' => 624,
                'car_make_id' => 37,
                'name' => 'Spider Europa',
            ),
            124 => 
            array (
                'id' => 625,
                'car_make_id' => 37,
                'name' => 'Stilo',
            ),
            125 => 
            array (
                'id' => 626,
                'car_make_id' => 37,
                'name' => 'Strada',
            ),
            126 => 
            array (
                'id' => 627,
                'car_make_id' => 37,
                'name' => 'Talento',
            ),
            127 => 
            array (
                'id' => 628,
                'car_make_id' => 37,
                'name' => 'Tempra',
            ),
            128 => 
            array (
                'id' => 629,
                'car_make_id' => 37,
                'name' => 'Tipo',
            ),
            129 => 
            array (
                'id' => 630,
                'car_make_id' => 37,
                'name' => 'Ulysse',
            ),
            130 => 
            array (
                'id' => 631,
                'car_make_id' => 37,
                'name' => 'Uno',
            ),
            131 => 
            array (
                'id' => 632,
                'car_make_id' => 37,
                'name' => 'X 1/9',
            ),
            132 => 
            array (
                'id' => 633,
                'car_make_id' => 37,
                'name' => 'Other',
            ),
            133 => 
            array (
                'id' => 634,
                'car_make_id' => 38,
                'name' => 'Karma',
            ),
            134 => 
            array (
                'id' => 635,
                'car_make_id' => 38,
                'name' => 'Other',
            ),
            135 => 
            array (
                'id' => 636,
                'car_make_id' => 39,
                'name' => 'Aerostar',
            ),
            136 => 
            array (
                'id' => 637,
                'car_make_id' => 39,
                'name' => 'B-Max',
            ),
            137 => 
            array (
                'id' => 638,
                'car_make_id' => 39,
                'name' => 'Bronco',
            ),
            138 => 
            array (
                'id' => 639,
                'car_make_id' => 39,
                'name' => 'Capri',
            ),
            139 => 
            array (
                'id' => 640,
                'car_make_id' => 39,
                'name' => 'C-Max',
            ),
            140 => 
            array (
                'id' => 641,
                'car_make_id' => 39,
                'name' => 'Cougar',
            ),
            141 => 
            array (
                'id' => 642,
                'car_make_id' => 39,
                'name' => 'Courier',
            ),
            142 => 
            array (
                'id' => 643,
                'car_make_id' => 39,
                'name' => 'Crown',
            ),
            143 => 
            array (
                'id' => 644,
                'car_make_id' => 39,
                'name' => 'Econoline',
            ),
            144 => 
            array (
                'id' => 645,
                'car_make_id' => 39,
                'name' => 'Econovan',
            ),
            145 => 
            array (
                'id' => 646,
                'car_make_id' => 39,
                'name' => 'EcoSport',
            ),
            146 => 
            array (
                'id' => 647,
                'car_make_id' => 39,
                'name' => 'Edge',
            ),
            147 => 
            array (
                'id' => 648,
                'car_make_id' => 39,
                'name' => 'Escape',
            ),
            148 => 
            array (
                'id' => 649,
                'car_make_id' => 39,
                'name' => 'Escort',
            ),
            149 => 
            array (
                'id' => 650,
                'car_make_id' => 39,
                'name' => 'Excursion',
            ),
            150 => 
            array (
                'id' => 651,
                'car_make_id' => 39,
                'name' => 'Expedition',
            ),
            151 => 
            array (
                'id' => 652,
                'car_make_id' => 39,
                'name' => 'Explorer',
            ),
            152 => 
            array (
                'id' => 653,
                'car_make_id' => 39,
                'name' => 'Express',
            ),
            153 => 
            array (
                'id' => 654,
                'car_make_id' => 39,
                'name' => 'F 100',
            ),
            154 => 
            array (
                'id' => 655,
                'car_make_id' => 39,
                'name' => 'F 150',
            ),
            155 => 
            array (
                'id' => 656,
                'car_make_id' => 39,
                'name' => 'F 250',
            ),
            156 => 
            array (
                'id' => 657,
                'car_make_id' => 39,
                'name' => 'F 350',
            ),
            157 => 
            array (
                'id' => 658,
                'car_make_id' => 39,
                'name' => 'Fairlane',
            ),
            158 => 
            array (
                'id' => 659,
                'car_make_id' => 39,
                'name' => 'Falcon',
            ),
            159 => 
            array (
                'id' => 660,
                'car_make_id' => 39,
                'name' => 'Fiesta',
            ),
            160 => 
            array (
                'id' => 661,
                'car_make_id' => 39,
                'name' => 'Flex',
            ),
            161 => 
            array (
                'id' => 662,
                'car_make_id' => 39,
                'name' => 'Focus',
            ),
            162 => 
            array (
                'id' => 663,
                'car_make_id' => 39,
                'name' => 'Fusion',
            ),
            163 => 
            array (
                'id' => 664,
                'car_make_id' => 39,
                'name' => 'Galaxy',
            ),
            164 => 
            array (
                'id' => 665,
                'car_make_id' => 39,
                'name' => 'Granada',
            ),
            165 => 
            array (
                'id' => 666,
                'car_make_id' => 39,
                'name' => 'Grand C-Max',
            ),
            166 => 
            array (
                'id' => 667,
                'car_make_id' => 39,
                'name' => 'Grand Tourneo',
            ),
            167 => 
            array (
                'id' => 668,
                'car_make_id' => 39,
                'name' => 'GT',
            ),
            168 => 
            array (
                'id' => 669,
                'car_make_id' => 39,
                'name' => 'Ka/Ka+',
            ),
            169 => 
            array (
                'id' => 670,
                'car_make_id' => 39,
                'name' => 'Kuga',
            ),
            170 => 
            array (
                'id' => 671,
                'car_make_id' => 39,
                'name' => 'Maverick',
            ),
            171 => 
            array (
                'id' => 672,
                'car_make_id' => 39,
                'name' => 'Mercury',
            ),
            172 => 
            array (
                'id' => 673,
                'car_make_id' => 39,
                'name' => 'Mondeo',
            ),
            173 => 
            array (
                'id' => 674,
                'car_make_id' => 39,
                'name' => 'Mustang',
            ),
            174 => 
            array (
                'id' => 675,
                'car_make_id' => 39,
                'name' => 'Orion',
            ),
            175 => 
            array (
                'id' => 676,
                'car_make_id' => 39,
                'name' => 'Probe',
            ),
            176 => 
            array (
                'id' => 677,
                'car_make_id' => 39,
                'name' => 'Puma',
            ),
            177 => 
            array (
                'id' => 678,
                'car_make_id' => 39,
                'name' => 'Ranger',
            ),
            178 => 
            array (
                'id' => 679,
                'car_make_id' => 39,
                'name' => 'Raptor',
            ),
            179 => 
            array (
                'id' => 680,
                'car_make_id' => 39,
                'name' => 'Scorpio',
            ),
            180 => 
            array (
                'id' => 681,
                'car_make_id' => 39,
                'name' => 'Sierra',
            ),
            181 => 
            array (
                'id' => 682,
                'car_make_id' => 39,
                'name' => 'S-Max',
            ),
            182 => 
            array (
                'id' => 683,
                'car_make_id' => 39,
                'name' => 'Sportka',
            ),
            183 => 
            array (
                'id' => 684,
                'car_make_id' => 39,
                'name' => 'Streetka',
            ),
            184 => 
            array (
                'id' => 685,
                'car_make_id' => 39,
                'name' => 'Taunus',
            ),
            185 => 
            array (
                'id' => 686,
                'car_make_id' => 39,
                'name' => 'Taurus',
            ),
            186 => 
            array (
                'id' => 687,
                'car_make_id' => 39,
                'name' => 'Thunderbird',
            ),
            187 => 
            array (
                'id' => 688,
                'car_make_id' => 39,
                'name' => 'Tourneo',
            ),
            188 => 
            array (
                'id' => 689,
                'car_make_id' => 39,
                'name' => 'Tourneo Connect',
            ),
            189 => 
            array (
                'id' => 690,
                'car_make_id' => 39,
                'name' => 'Tourneo Courier',
            ),
            190 => 
            array (
                'id' => 691,
                'car_make_id' => 39,
                'name' => 'Tourneo Custom',
            ),
            191 => 
            array (
                'id' => 692,
                'car_make_id' => 39,
                'name' => 'Transit',
            ),
            192 => 
            array (
                'id' => 693,
                'car_make_id' => 39,
                'name' => 'Transit Connect',
            ),
            193 => 
            array (
                'id' => 694,
                'car_make_id' => 39,
                'name' => 'Transit Courier',
            ),
            194 => 
            array (
                'id' => 695,
                'car_make_id' => 39,
                'name' => 'Transit Custom',
            ),
            195 => 
            array (
                'id' => 696,
                'car_make_id' => 39,
                'name' => 'Windstar',
            ),
            196 => 
            array (
                'id' => 697,
                'car_make_id' => 39,
                'name' => 'Other',
            ),
            197 => 
            array (
                'id' => 698,
                'car_make_id' => 40,
                'name' => 'Other',
            ),
            198 => 
            array (
                'id' => 699,
                'car_make_id' => 41,
                'name' => 'Other',
            ),
            199 => 
            array (
                'id' => 700,
                'car_make_id' => 42,
                'name' => 'Acadia',
            ),
            200 => 
            array (
                'id' => 701,
                'car_make_id' => 42,
                'name' => 'Envoy',
            ),
            201 => 
            array (
                'id' => 702,
                'car_make_id' => 42,
                'name' => 'Safari',
            ),
            202 => 
            array (
                'id' => 703,
                'car_make_id' => 42,
                'name' => 'Savana',
            ),
            203 => 
            array (
                'id' => 704,
                'car_make_id' => 42,
                'name' => 'Sierra',
            ),
            204 => 
            array (
                'id' => 705,
                'car_make_id' => 42,
                'name' => 'Sonoma',
            ),
            205 => 
            array (
                'id' => 706,
                'car_make_id' => 42,
                'name' => 'Syclone',
            ),
            206 => 
            array (
                'id' => 707,
                'car_make_id' => 42,
                'name' => 'Terrain',
            ),
            207 => 
            array (
                'id' => 708,
                'car_make_id' => 42,
                'name' => 'Typhoon',
            ),
            208 => 
            array (
                'id' => 709,
                'car_make_id' => 42,
                'name' => 'Vandura',
            ),
            209 => 
            array (
                'id' => 710,
                'car_make_id' => 42,
                'name' => 'Yukon',
            ),
            210 => 
            array (
                'id' => 711,
                'car_make_id' => 42,
                'name' => 'Other',
            ),
            211 => 
            array (
                'id' => 712,
                'car_make_id' => 43,
                'name' => 'Sonique',
            ),
            212 => 
            array (
                'id' => 713,
                'car_make_id' => 43,
                'name' => 'Other',
            ),
            213 => 
            array (
                'id' => 714,
                'car_make_id' => 44,
                'name' => 'Other',
            ),
            214 => 
            array (
                'id' => 715,
                'car_make_id' => 45,
                'name' => 'Other',
            ),
            215 => 
            array (
                'id' => 716,
                'car_make_id' => 46,
                'name' => 'Accord',
            ),
            216 => 
            array (
                'id' => 717,
                'car_make_id' => 46,
                'name' => 'Aerodeck',
            ),
            217 => 
            array (
                'id' => 718,
                'car_make_id' => 46,
                'name' => 'City',
            ),
            218 => 
            array (
                'id' => 719,
                'car_make_id' => 46,
                'name' => 'Civic',
            ),
            219 => 
            array (
                'id' => 720,
                'car_make_id' => 46,
                'name' => 'Clarity',
            ),
            220 => 
            array (
                'id' => 721,
                'car_make_id' => 46,
                'name' => 'Concerto',
            ),
            221 => 
            array (
                'id' => 722,
                'car_make_id' => 46,
                'name' => 'CR-V',
            ),
            222 => 
            array (
                'id' => 723,
                'car_make_id' => 46,
                'name' => 'CRX',
            ),
            223 => 
            array (
                'id' => 724,
                'car_make_id' => 46,
                'name' => 'CR-Z',
            ),
            224 => 
            array (
                'id' => 725,
                'car_make_id' => 46,
                'name' => 'e',
            ),
            225 => 
            array (
                'id' => 726,
                'car_make_id' => 46,
                'name' => 'Element',
            ),
            226 => 
            array (
                'id' => 727,
                'car_make_id' => 46,
                'name' => 'FR-V',
            ),
            227 => 
            array (
                'id' => 728,
                'car_make_id' => 46,
                'name' => 'HR-V',
            ),
            228 => 
            array (
                'id' => 729,
                'car_make_id' => 46,
                'name' => 'Insight',
            ),
            229 => 
            array (
                'id' => 730,
                'car_make_id' => 46,
                'name' => 'Integra',
            ),
            230 => 
            array (
                'id' => 731,
                'car_make_id' => 46,
                'name' => 'Jazz',
            ),
            231 => 
            array (
                'id' => 732,
                'car_make_id' => 46,
                'name' => 'Legend',
            ),
            232 => 
            array (
                'id' => 733,
                'car_make_id' => 46,
                'name' => 'Logo',
            ),
            233 => 
            array (
                'id' => 734,
                'car_make_id' => 46,
                'name' => 'NSX',
            ),
            234 => 
            array (
                'id' => 735,
                'car_make_id' => 46,
                'name' => 'Odyssey',
            ),
            235 => 
            array (
                'id' => 736,
                'car_make_id' => 46,
                'name' => 'Pilot',
            ),
            236 => 
            array (
                'id' => 737,
                'car_make_id' => 46,
                'name' => 'Prelude',
            ),
            237 => 
            array (
                'id' => 738,
                'car_make_id' => 46,
                'name' => 'Ridgeline',
            ),
            238 => 
            array (
                'id' => 739,
                'car_make_id' => 46,
                'name' => 'S2000',
            ),
            239 => 
            array (
                'id' => 740,
                'car_make_id' => 46,
                'name' => 'Shuttle',
            ),
            240 => 
            array (
                'id' => 741,
                'car_make_id' => 46,
                'name' => 'Stream',
            ),
            241 => 
            array (
                'id' => 742,
                'car_make_id' => 46,
                'name' => 'Other',
            ),
            242 => 
            array (
                'id' => 743,
                'car_make_id' => 47,
                'name' => 'H1',
            ),
            243 => 
            array (
                'id' => 744,
                'car_make_id' => 47,
                'name' => 'H2',
            ),
            244 => 
            array (
                'id' => 745,
                'car_make_id' => 47,
                'name' => 'H3',
            ),
            245 => 
            array (
                'id' => 746,
                'car_make_id' => 47,
                'name' => 'Other',
            ),
            246 => 
            array (
                'id' => 747,
                'car_make_id' => 48,
                'name' => 'Accent',
            ),
            247 => 
            array (
                'id' => 748,
                'car_make_id' => 48,
                'name' => 'Atos',
            ),
            248 => 
            array (
                'id' => 749,
                'car_make_id' => 48,
                'name' => 'Azera',
            ),
            249 => 
            array (
                'id' => 750,
                'car_make_id' => 48,
                'name' => 'Coupe',
            ),
            250 => 
            array (
                'id' => 751,
                'car_make_id' => 48,
                'name' => 'Elantra',
            ),
            251 => 
            array (
                'id' => 752,
                'car_make_id' => 48,
                'name' => 'Excel',
            ),
            252 => 
            array (
                'id' => 753,
                'car_make_id' => 48,
                'name' => 'Galloper',
            ),
            253 => 
            array (
                'id' => 754,
                'car_make_id' => 48,
                'name' => 'Genesis',
            ),
            254 => 
            array (
                'id' => 755,
                'car_make_id' => 48,
                'name' => 'Getz',
            ),
            255 => 
            array (
                'id' => 756,
                'car_make_id' => 48,
                'name' => 'Grandeur',
            ),
            256 => 
            array (
                'id' => 757,
                'car_make_id' => 48,
                'name' => 'Grand Santa Fe',
            ),
            257 => 
            array (
                'id' => 758,
                'car_make_id' => 48,
                'name' => 'H-1',
            ),
            258 => 
            array (
                'id' => 759,
                'car_make_id' => 48,
                'name' => 'H 100',
            ),
            259 => 
            array (
                'id' => 760,
                'car_make_id' => 48,
                'name' => 'H-1 Starex',
            ),
            260 => 
            array (
                'id' => 761,
                'car_make_id' => 48,
                'name' => 'H 200',
            ),
            261 => 
            array (
                'id' => 762,
                'car_make_id' => 48,
                'name' => 'H350',
            ),
            262 => 
            array (
                'id' => 763,
                'car_make_id' => 48,
                'name' => 'i10',
            ),
            263 => 
            array (
                'id' => 764,
                'car_make_id' => 48,
                'name' => 'i20',
            ),
            264 => 
            array (
                'id' => 765,
                'car_make_id' => 48,
                'name' => 'i30',
            ),
            265 => 
            array (
                'id' => 766,
                'car_make_id' => 48,
                'name' => 'i40',
            ),
            266 => 
            array (
                'id' => 767,
                'car_make_id' => 48,
                'name' => 'i50',
            ),
            267 => 
            array (
                'id' => 768,
                'car_make_id' => 48,
                'name' => 'IONIQ',
            ),
            268 => 
            array (
                'id' => 769,
                'car_make_id' => 48,
                'name' => 'ix20',
            ),
            269 => 
            array (
                'id' => 770,
                'car_make_id' => 48,
                'name' => 'ix35',
            ),
            270 => 
            array (
                'id' => 771,
                'car_make_id' => 48,
                'name' => 'ix55',
            ),
            271 => 
            array (
                'id' => 772,
                'car_make_id' => 48,
                'name' => 'Kona',
            ),
            272 => 
            array (
                'id' => 773,
                'car_make_id' => 48,
                'name' => 'Lantra',
            ),
            273 => 
            array (
                'id' => 774,
                'car_make_id' => 48,
                'name' => 'Matrix',
            ),
            274 => 
            array (
                'id' => 775,
                'car_make_id' => 48,
                'name' => 'Nexo',
            ),
            275 => 
            array (
                'id' => 776,
                'car_make_id' => 48,
                'name' => 'Pony',
            ),
            276 => 
            array (
                'id' => 777,
                'car_make_id' => 48,
                'name' => 'Santa Fe',
            ),
            277 => 
            array (
                'id' => 778,
                'car_make_id' => 48,
                'name' => 'Santamo',
            ),
            278 => 
            array (
                'id' => 779,
                'car_make_id' => 48,
                'name' => 'S-Coupe',
            ),
            279 => 
            array (
                'id' => 780,
                'car_make_id' => 48,
                'name' => 'Sonata',
            ),
            280 => 
            array (
                'id' => 781,
                'car_make_id' => 48,
                'name' => 'Terracan',
            ),
            281 => 
            array (
                'id' => 782,
                'car_make_id' => 48,
                'name' => 'Trajet',
            ),
            282 => 
            array (
                'id' => 783,
                'car_make_id' => 48,
                'name' => 'Tucson',
            ),
            283 => 
            array (
                'id' => 784,
                'car_make_id' => 48,
                'name' => 'Veloster',
            ),
            284 => 
            array (
                'id' => 785,
                'car_make_id' => 48,
                'name' => 'Veracruz',
            ),
            285 => 
            array (
                'id' => 786,
                'car_make_id' => 48,
                'name' => 'XG 30',
            ),
            286 => 
            array (
                'id' => 787,
                'car_make_id' => 48,
                'name' => 'XG 350',
            ),
            287 => 
            array (
                'id' => 788,
                'car_make_id' => 48,
                'name' => 'Other',
            ),
            288 => 
            array (
                'id' => 789,
                'car_make_id' => 49,
                'name' => 'EX30',
            ),
            289 => 
            array (
                'id' => 790,
                'car_make_id' => 49,
                'name' => 'EX35',
            ),
            290 => 
            array (
                'id' => 791,
                'car_make_id' => 49,
                'name' => 'EX37',
            ),
            291 => 
            array (
                'id' => 792,
                'car_make_id' => 49,
                'name' => 'FX',
            ),
            292 => 
            array (
                'id' => 793,
                'car_make_id' => 49,
                'name' => 'G35',
            ),
            293 => 
            array (
                'id' => 794,
                'car_make_id' => 49,
                'name' => 'G37',
            ),
            294 => 
            array (
                'id' => 795,
                'car_make_id' => 49,
                'name' => 'M30',
            ),
            295 => 
            array (
                'id' => 796,
                'car_make_id' => 49,
                'name' => 'M35',
            ),
            296 => 
            array (
                'id' => 797,
                'car_make_id' => 49,
                'name' => 'M37',
            ),
            297 => 
            array (
                'id' => 798,
                'car_make_id' => 49,
                'name' => 'Q30',
            ),
            298 => 
            array (
                'id' => 799,
                'car_make_id' => 49,
                'name' => 'Q45',
            ),
            299 => 
            array (
                'id' => 800,
                'car_make_id' => 49,
                'name' => 'Q50',
            ),
            300 => 
            array (
                'id' => 801,
                'car_make_id' => 49,
                'name' => 'Q60',
            ),
            301 => 
            array (
                'id' => 802,
                'car_make_id' => 49,
                'name' => 'Q70',
            ),
            302 => 
            array (
                'id' => 803,
                'car_make_id' => 49,
                'name' => 'QX30',
            ),
            303 => 
            array (
                'id' => 804,
                'car_make_id' => 49,
                'name' => 'QX50',
            ),
            304 => 
            array (
                'id' => 805,
                'car_make_id' => 49,
                'name' => 'QX56',
            ),
            305 => 
            array (
                'id' => 806,
                'car_make_id' => 49,
                'name' => 'QX70',
            ),
            306 => 
            array (
                'id' => 807,
                'car_make_id' => 49,
                'name' => 'Other',
            ),
            307 => 
            array (
                'id' => 808,
                'car_make_id' => 50,
                'name' => 'Campo',
            ),
            308 => 
            array (
                'id' => 809,
                'car_make_id' => 50,
                'name' => 'D-Max',
            ),
            309 => 
            array (
                'id' => 810,
                'car_make_id' => 50,
                'name' => 'Gemini',
            ),
            310 => 
            array (
                'id' => 811,
                'car_make_id' => 50,
                'name' => 'Midi',
            ),
            311 => 
            array (
                'id' => 812,
                'car_make_id' => 50,
                'name' => 'PICK UP',
            ),
            312 => 
            array (
                'id' => 813,
                'car_make_id' => 50,
                'name' => 'Trooper',
            ),
            313 => 
            array (
                'id' => 814,
                'car_make_id' => 50,
                'name' => 'Other',
            ),
            314 => 
            array (
                'id' => 815,
                'car_make_id' => 51,
                'name' => 'Massif',
            ),
            315 => 
            array (
                'id' => 816,
                'car_make_id' => 51,
                'name' => 'Other',
            ),
            316 => 
            array (
                'id' => 817,
                'car_make_id' => 52,
                'name' => 'Daimler',
            ),
            317 => 
            array (
                'id' => 818,
                'car_make_id' => 52,
                'name' => 'E-Pace',
            ),
            318 => 
            array (
                'id' => 819,
                'car_make_id' => 52,
                'name' => 'E-Type',
            ),
            319 => 
            array (
                'id' => 820,
                'car_make_id' => 52,
                'name' => 'F-Pace',
            ),
            320 => 
            array (
                'id' => 821,
                'car_make_id' => 52,
                'name' => 'F-Type',
            ),
            321 => 
            array (
                'id' => 822,
                'car_make_id' => 52,
                'name' => 'I-Pace',
            ),
            322 => 
            array (
                'id' => 823,
                'car_make_id' => 52,
                'name' => 'MK II',
            ),
            323 => 
            array (
                'id' => 824,
                'car_make_id' => 52,
                'name' => 'S-Type',
            ),
            324 => 
            array (
                'id' => 825,
                'car_make_id' => 52,
                'name' => 'XE',
            ),
            325 => 
            array (
                'id' => 826,
                'car_make_id' => 52,
                'name' => 'XF',
            ),
            326 => 
            array (
                'id' => 827,
                'car_make_id' => 52,
                'name' => 'XJ',
            ),
            327 => 
            array (
                'id' => 828,
                'car_make_id' => 52,
                'name' => 'XJ12',
            ),
            328 => 
            array (
                'id' => 829,
                'car_make_id' => 52,
                'name' => 'XJ40',
            ),
            329 => 
            array (
                'id' => 830,
                'car_make_id' => 52,
                'name' => 'XJ6',
            ),
            330 => 
            array (
                'id' => 831,
                'car_make_id' => 52,
                'name' => 'XJ8',
            ),
            331 => 
            array (
                'id' => 832,
                'car_make_id' => 52,
                'name' => 'XJR',
            ),
            332 => 
            array (
                'id' => 833,
                'car_make_id' => 52,
                'name' => 'XJS',
            ),
            333 => 
            array (
                'id' => 834,
                'car_make_id' => 52,
                'name' => 'XJSC',
            ),
            334 => 
            array (
                'id' => 835,
                'car_make_id' => 52,
                'name' => 'XK',
            ),
            335 => 
            array (
                'id' => 836,
                'car_make_id' => 52,
                'name' => 'XK8',
            ),
            336 => 
            array (
                'id' => 837,
                'car_make_id' => 52,
                'name' => 'XKR',
            ),
            337 => 
            array (
                'id' => 838,
                'car_make_id' => 52,
                'name' => 'X-Type',
            ),
            338 => 
            array (
                'id' => 839,
                'car_make_id' => 52,
                'name' => 'Other',
            ),
            339 => 
            array (
                'id' => 840,
                'car_make_id' => 53,
                'name' => 'Cherokee',
            ),
            340 => 
            array (
                'id' => 841,
                'car_make_id' => 53,
                'name' => 'CJ',
            ),
            341 => 
            array (
                'id' => 842,
                'car_make_id' => 53,
                'name' => 'Comanche',
            ),
            342 => 
            array (
                'id' => 843,
                'car_make_id' => 53,
                'name' => 'Commander',
            ),
            343 => 
            array (
                'id' => 844,
                'car_make_id' => 53,
                'name' => 'Compass',
            ),
            344 => 
            array (
                'id' => 845,
                'car_make_id' => 53,
                'name' => 'Grand Cherokee',
            ),
            345 => 
            array (
                'id' => 846,
                'car_make_id' => 53,
                'name' => 'Patriot',
            ),
            346 => 
            array (
                'id' => 847,
                'car_make_id' => 53,
                'name' => 'Renegade',
            ),
            347 => 
            array (
                'id' => 848,
                'car_make_id' => 53,
                'name' => 'Wagoneer',
            ),
            348 => 
            array (
                'id' => 849,
                'car_make_id' => 53,
                'name' => 'Willys',
            ),
            349 => 
            array (
                'id' => 850,
                'car_make_id' => 53,
                'name' => 'Wrangler',
            ),
            350 => 
            array (
                'id' => 851,
                'car_make_id' => 53,
                'name' => 'Other',
            ),
            351 => 
            array (
                'id' => 852,
                'car_make_id' => 54,
                'name' => 'Besta',
            ),
            352 => 
            array (
                'id' => 853,
                'car_make_id' => 54,
                'name' => 'Borrego',
            ),
            353 => 
            array (
                'id' => 854,
                'car_make_id' => 54,
                'name' => 'Carens',
            ),
            354 => 
            array (
                'id' => 855,
                'car_make_id' => 54,
                'name' => 'Carnival',
            ),
            355 => 
            array (
                'id' => 856,
                'car_make_id' => 54,
                'name' => 'cee\'d / Ceed',
            ),
            356 => 
            array (
                'id' => 857,
                'car_make_id' => 54,
                'name' => 'cee\'d Sportswagon',
            ),
            357 => 
            array (
                'id' => 858,
                'car_make_id' => 54,
                'name' => 'Cerato',
            ),
            358 => 
            array (
                'id' => 859,
                'car_make_id' => 54,
                'name' => 'Clarus',
            ),
            359 => 
            array (
                'id' => 860,
                'car_make_id' => 54,
                'name' => 'Elan',
            ),
            360 => 
            array (
                'id' => 861,
                'car_make_id' => 54,
                'name' => 'Joice',
            ),
            361 => 
            array (
                'id' => 862,
                'car_make_id' => 54,
                'name' => 'K2500',
            ),
            362 => 
            array (
                'id' => 863,
                'car_make_id' => 54,
                'name' => 'K2700',
            ),
            363 => 
            array (
                'id' => 864,
                'car_make_id' => 54,
                'name' => 'Leo',
            ),
            364 => 
            array (
                'id' => 865,
                'car_make_id' => 54,
                'name' => 'Magentis',
            ),
            365 => 
            array (
                'id' => 866,
                'car_make_id' => 54,
                'name' => 'Mentor',
            ),
            366 => 
            array (
                'id' => 867,
                'car_make_id' => 54,
                'name' => 'Mini',
            ),
            367 => 
            array (
                'id' => 868,
                'car_make_id' => 54,
                'name' => 'Niro',
            ),
            368 => 
            array (
                'id' => 869,
                'car_make_id' => 54,
                'name' => 'Opirus',
            ),
            369 => 
            array (
                'id' => 870,
                'car_make_id' => 54,
                'name' => 'Optima',
            ),
            370 => 
            array (
                'id' => 871,
                'car_make_id' => 54,
                'name' => 'Picanto',
            ),
            371 => 
            array (
                'id' => 872,
                'car_make_id' => 54,
                'name' => 'Pregio',
            ),
            372 => 
            array (
                'id' => 873,
                'car_make_id' => 54,
                'name' => 'Pride',
            ),
            373 => 
            array (
                'id' => 874,
                'car_make_id' => 54,
                'name' => 'pro_cee\'d / ProCeed',
            ),
            374 => 
            array (
                'id' => 875,
                'car_make_id' => 54,
                'name' => 'Retona',
            ),
            375 => 
            array (
                'id' => 876,
                'car_make_id' => 54,
                'name' => 'Rio',
            ),
            376 => 
            array (
                'id' => 877,
                'car_make_id' => 54,
                'name' => 'Roadster',
            ),
            377 => 
            array (
                'id' => 878,
                'car_make_id' => 54,
                'name' => 'Rocsta',
            ),
            378 => 
            array (
                'id' => 879,
                'car_make_id' => 54,
                'name' => 'Sephia',
            ),
            379 => 
            array (
                'id' => 880,
                'car_make_id' => 54,
                'name' => 'Shuma',
            ),
            380 => 
            array (
                'id' => 881,
                'car_make_id' => 54,
                'name' => 'Sorento',
            ),
            381 => 
            array (
                'id' => 882,
                'car_make_id' => 54,
                'name' => 'Soul',
            ),
            382 => 
            array (
                'id' => 883,
                'car_make_id' => 54,
                'name' => 'Sportage',
            ),
            383 => 
            array (
                'id' => 884,
                'car_make_id' => 54,
                'name' => 'Stinger',
            ),
            384 => 
            array (
                'id' => 885,
                'car_make_id' => 54,
                'name' => 'Stonic',
            ),
            385 => 
            array (
                'id' => 886,
                'car_make_id' => 54,
                'name' => 'Venga',
            ),
            386 => 
            array (
                'id' => 887,
                'car_make_id' => 54,
                'name' => 'XCeed',
            ),
            387 => 
            array (
                'id' => 888,
                'car_make_id' => 54,
                'name' => 'Other',
            ),
            388 => 
            array (
                'id' => 889,
                'car_make_id' => 55,
                'name' => 'Agera',
            ),
            389 => 
            array (
                'id' => 890,
                'car_make_id' => 55,
                'name' => 'CCR',
            ),
            390 => 
            array (
                'id' => 891,
                'car_make_id' => 55,
                'name' => 'CCXR',
            ),
            391 => 
            array (
                'id' => 892,
                'car_make_id' => 55,
                'name' => 'Other',
            ),
            392 => 
            array (
                'id' => 893,
                'car_make_id' => 56,
                'name' => 'X-BOW',
            ),
            393 => 
            array (
                'id' => 894,
                'car_make_id' => 56,
                'name' => 'Other',
            ),
            394 => 
            array (
                'id' => 895,
                'car_make_id' => 57,
                'name' => '110',
            ),
            395 => 
            array (
                'id' => 896,
                'car_make_id' => 57,
                'name' => '111',
            ),
            396 => 
            array (
                'id' => 897,
                'car_make_id' => 57,
                'name' => '112',
            ),
            397 => 
            array (
                'id' => 898,
                'car_make_id' => 57,
                'name' => '1200',
            ),
            398 => 
            array (
                'id' => 899,
                'car_make_id' => 57,
                'name' => '2107',
            ),
            399 => 
            array (
                'id' => 900,
                'car_make_id' => 57,
                'name' => '2110',
            ),
            400 => 
            array (
                'id' => 901,
                'car_make_id' => 57,
                'name' => '2111',
            ),
            401 => 
            array (
                'id' => 902,
                'car_make_id' => 57,
                'name' => '2112',
            ),
            402 => 
            array (
                'id' => 903,
                'car_make_id' => 57,
                'name' => 'Aleko',
            ),
            403 => 
            array (
                'id' => 904,
                'car_make_id' => 57,
                'name' => 'Forma',
            ),
            404 => 
            array (
                'id' => 905,
                'car_make_id' => 57,
                'name' => 'Granta',
            ),
            405 => 
            array (
                'id' => 906,
                'car_make_id' => 57,
                'name' => 'Kalina',
            ),
            406 => 
            array (
                'id' => 907,
                'car_make_id' => 57,
                'name' => 'Niva',
            ),
            407 => 
            array (
                'id' => 908,
                'car_make_id' => 57,
                'name' => 'Nova',
            ),
            408 => 
            array (
                'id' => 909,
                'car_make_id' => 57,
                'name' => 'Priora',
            ),
            409 => 
            array (
                'id' => 910,
                'car_make_id' => 57,
                'name' => 'Samara',
            ),
            410 => 
            array (
                'id' => 911,
                'car_make_id' => 57,
                'name' => 'Taiga',
            ),
            411 => 
            array (
                'id' => 912,
                'car_make_id' => 57,
                'name' => 'Urban',
            ),
            412 => 
            array (
                'id' => 913,
                'car_make_id' => 57,
                'name' => 'Vesta',
            ),
            413 => 
            array (
                'id' => 914,
                'car_make_id' => 57,
                'name' => 'X-Ray',
            ),
            414 => 
            array (
                'id' => 915,
                'car_make_id' => 57,
                'name' => 'Other',
            ),
            415 => 
            array (
                'id' => 916,
                'car_make_id' => 58,
                'name' => 'Aventador',
            ),
            416 => 
            array (
                'id' => 917,
                'car_make_id' => 58,
                'name' => 'Countach',
            ),
            417 => 
            array (
                'id' => 918,
                'car_make_id' => 58,
                'name' => 'Diablo',
            ),
            418 => 
            array (
                'id' => 919,
                'car_make_id' => 58,
                'name' => 'Espada',
            ),
            419 => 
            array (
                'id' => 920,
                'car_make_id' => 58,
                'name' => 'Gallardo',
            ),
            420 => 
            array (
                'id' => 921,
                'car_make_id' => 58,
                'name' => 'Huracán',
            ),
            421 => 
            array (
                'id' => 922,
                'car_make_id' => 58,
                'name' => 'Jalpa',
            ),
            422 => 
            array (
                'id' => 923,
                'car_make_id' => 58,
                'name' => 'LM',
            ),
            423 => 
            array (
                'id' => 924,
                'car_make_id' => 58,
                'name' => 'Miura',
            ),
            424 => 
            array (
                'id' => 925,
                'car_make_id' => 58,
                'name' => 'Murciélago',
            ),
            425 => 
            array (
                'id' => 926,
                'car_make_id' => 58,
                'name' => 'Urraco',
            ),
            426 => 
            array (
                'id' => 927,
                'car_make_id' => 58,
                'name' => 'Urus',
            ),
            427 => 
            array (
                'id' => 928,
                'car_make_id' => 58,
                'name' => 'Other',
            ),
            428 => 
            array (
                'id' => 929,
                'car_make_id' => 59,
                'name' => 'Beta',
            ),
            429 => 
            array (
                'id' => 930,
                'car_make_id' => 59,
                'name' => 'Dedra',
            ),
            430 => 
            array (
                'id' => 931,
                'car_make_id' => 59,
                'name' => 'Delta',
            ),
            431 => 
            array (
                'id' => 932,
                'car_make_id' => 59,
                'name' => 'Flaminia',
            ),
            432 => 
            array (
                'id' => 933,
                'car_make_id' => 59,
                'name' => 'Flavia',
            ),
            433 => 
            array (
                'id' => 934,
                'car_make_id' => 59,
                'name' => 'Fulvia',
            ),
            434 => 
            array (
                'id' => 935,
                'car_make_id' => 59,
                'name' => 'Gamma',
            ),
            435 => 
            array (
                'id' => 936,
                'car_make_id' => 59,
                'name' => 'Kappa',
            ),
            436 => 
            array (
                'id' => 937,
                'car_make_id' => 59,
                'name' => 'Lybra',
            ),
            437 => 
            array (
                'id' => 938,
                'car_make_id' => 59,
                'name' => 'MUSA',
            ),
            438 => 
            array (
                'id' => 939,
                'car_make_id' => 59,
                'name' => 'Phedra',
            ),
            439 => 
            array (
                'id' => 940,
                'car_make_id' => 59,
                'name' => 'Prisma',
            ),
            440 => 
            array (
                'id' => 941,
                'car_make_id' => 59,
                'name' => 'Stratos',
            ),
            441 => 
            array (
                'id' => 942,
                'car_make_id' => 59,
                'name' => 'Thema',
            ),
            442 => 
            array (
                'id' => 943,
                'car_make_id' => 59,
                'name' => 'Thesis',
            ),
            443 => 
            array (
                'id' => 944,
                'car_make_id' => 59,
                'name' => 'Voyager',
            ),
            444 => 
            array (
                'id' => 945,
                'car_make_id' => 59,
                'name' => 'Ypsilon',
            ),
            445 => 
            array (
                'id' => 946,
                'car_make_id' => 59,
                'name' => 'Zeta',
            ),
            446 => 
            array (
                'id' => 947,
                'car_make_id' => 59,
                'name' => 'Other',
            ),
            447 => 
            array (
                'id' => 948,
                'car_make_id' => 60,
                'name' => 'Defender',
            ),
            448 => 
            array (
                'id' => 949,
                'car_make_id' => 60,
                'name' => 'Discovery',
            ),
            449 => 
            array (
                'id' => 950,
                'car_make_id' => 60,
                'name' => 'Discovery Sport',
            ),
            450 => 
            array (
                'id' => 951,
                'car_make_id' => 60,
                'name' => 'Freelander',
            ),
            451 => 
            array (
                'id' => 952,
                'car_make_id' => 60,
                'name' => 'Range Rover',
            ),
            452 => 
            array (
                'id' => 953,
                'car_make_id' => 60,
                'name' => 'Range Rover Evoque',
            ),
            453 => 
            array (
                'id' => 954,
                'car_make_id' => 60,
                'name' => 'Range Rover Sport',
            ),
            454 => 
            array (
                'id' => 955,
                'car_make_id' => 60,
                'name' => 'Range Rover Velar',
            ),
            455 => 
            array (
                'id' => 956,
                'car_make_id' => 60,
                'name' => 'Serie I',
            ),
            456 => 
            array (
                'id' => 957,
                'car_make_id' => 60,
                'name' => 'Serie II',
            ),
            457 => 
            array (
                'id' => 958,
                'car_make_id' => 60,
                'name' => 'Serie III',
            ),
            458 => 
            array (
                'id' => 959,
                'car_make_id' => 60,
                'name' => 'Other',
            ),
            459 => 
            array (
                'id' => 960,
                'car_make_id' => 61,
                'name' => 'CV-9',
            ),
            460 => 
            array (
                'id' => 961,
                'car_make_id' => 61,
                'name' => 'S',
            ),
            461 => 
            array (
                'id' => 962,
                'car_make_id' => 61,
                'name' => 'SC2',
            ),
            462 => 
            array (
                'id' => 963,
                'car_make_id' => 61,
                'name' => 'SC4',
            ),
            463 => 
            array (
                'id' => 964,
                'car_make_id' => 61,
                'name' => 'Other',
            ),
            464 => 
            array (
                'id' => 965,
                'car_make_id' => 62,
                'name' => 'CT 200h',
            ),
            465 => 
            array (
                'id' => 966,
                'car_make_id' => 62,
                'name' => 'ES 300',
            ),
            466 => 
            array (
                'id' => 967,
                'car_make_id' => 62,
                'name' => 'ES 330',
            ),
            467 => 
            array (
                'id' => 968,
                'car_make_id' => 62,
                'name' => 'ES 350',
            ),
            468 => 
            array (
                'id' => 969,
                'car_make_id' => 62,
                'name' => 'GS 250',
            ),
            469 => 
            array (
                'id' => 970,
                'car_make_id' => 62,
                'name' => 'GS 300',
            ),
            470 => 
            array (
                'id' => 971,
                'car_make_id' => 62,
                'name' => 'GS 350',
            ),
            471 => 
            array (
                'id' => 972,
                'car_make_id' => 62,
                'name' => 'GS 430',
            ),
            472 => 
            array (
                'id' => 973,
                'car_make_id' => 62,
                'name' => 'GS 450',
            ),
            473 => 
            array (
                'id' => 974,
                'car_make_id' => 62,
                'name' => 'GS 460',
            ),
            474 => 
            array (
                'id' => 975,
                'car_make_id' => 62,
                'name' => 'GS F',
            ),
            475 => 
            array (
                'id' => 976,
                'car_make_id' => 62,
                'name' => 'GX 470',
            ),
            476 => 
            array (
                'id' => 977,
                'car_make_id' => 62,
                'name' => 'IS 200',
            ),
            477 => 
            array (
                'id' => 978,
                'car_make_id' => 62,
                'name' => 'IS 220',
            ),
            478 => 
            array (
                'id' => 979,
                'car_make_id' => 62,
                'name' => 'IS 250',
            ),
            479 => 
            array (
                'id' => 980,
                'car_make_id' => 62,
                'name' => 'IS 300',
            ),
            480 => 
            array (
                'id' => 981,
                'car_make_id' => 62,
                'name' => 'IS 350',
            ),
            481 => 
            array (
                'id' => 982,
                'car_make_id' => 62,
                'name' => 'IS-F',
            ),
            482 => 
            array (
                'id' => 983,
                'car_make_id' => 62,
                'name' => 'LC 500',
            ),
            483 => 
            array (
                'id' => 984,
                'car_make_id' => 62,
                'name' => 'LC 500h',
            ),
            484 => 
            array (
                'id' => 985,
                'car_make_id' => 62,
                'name' => 'LFA',
            ),
            485 => 
            array (
                'id' => 986,
                'car_make_id' => 62,
                'name' => 'LS 400',
            ),
            486 => 
            array (
                'id' => 987,
                'car_make_id' => 62,
                'name' => 'LS 430',
            ),
            487 => 
            array (
                'id' => 988,
                'car_make_id' => 62,
                'name' => 'LS 460',
            ),
            488 => 
            array (
                'id' => 989,
                'car_make_id' => 62,
                'name' => 'LS 500',
            ),
            489 => 
            array (
                'id' => 990,
                'car_make_id' => 62,
                'name' => 'LS 600',
            ),
            490 => 
            array (
                'id' => 991,
                'car_make_id' => 62,
                'name' => 'LX 470',
            ),
            491 => 
            array (
                'id' => 992,
                'car_make_id' => 62,
                'name' => 'LX 570',
            ),
            492 => 
            array (
                'id' => 993,
                'car_make_id' => 62,
                'name' => 'NX 200',
            ),
            493 => 
            array (
                'id' => 994,
                'car_make_id' => 62,
                'name' => 'NX 300',
            ),
            494 => 
            array (
                'id' => 995,
                'car_make_id' => 62,
                'name' => 'RC 200',
            ),
            495 => 
            array (
                'id' => 996,
                'car_make_id' => 62,
                'name' => 'RC 300',
            ),
            496 => 
            array (
                'id' => 997,
                'car_make_id' => 62,
                'name' => 'RC 350',
            ),
            497 => 
            array (
                'id' => 998,
                'car_make_id' => 62,
                'name' => 'RC F',
            ),
            498 => 
            array (
                'id' => 999,
                'car_make_id' => 62,
                'name' => 'RX 200',
            ),
            499 => 
            array (
                'id' => 1000,
                'car_make_id' => 62,
                'name' => 'RX 300',
            ),
        ));
        \DB::table('car_models')->insert(array (
            0 => 
            array (
                'id' => 1001,
                'car_make_id' => 62,
                'name' => 'RX 330',
            ),
            1 => 
            array (
                'id' => 1002,
                'car_make_id' => 62,
                'name' => 'RX 350',
            ),
            2 => 
            array (
                'id' => 1003,
                'car_make_id' => 62,
                'name' => 'RX 400',
            ),
            3 => 
            array (
                'id' => 1004,
                'car_make_id' => 62,
                'name' => 'RX 450',
            ),
            4 => 
            array (
                'id' => 1005,
                'car_make_id' => 62,
                'name' => 'SC 400',
            ),
            5 => 
            array (
                'id' => 1006,
                'car_make_id' => 62,
                'name' => 'SC 430',
            ),
            6 => 
            array (
                'id' => 1007,
                'car_make_id' => 62,
                'name' => 'UX',
            ),
            7 => 
            array (
                'id' => 1008,
                'car_make_id' => 62,
                'name' => 'Other',
            ),
            8 => 
            array (
                'id' => 1009,
                'car_make_id' => 63,
                'name' => 'Ambra',
            ),
            9 => 
            array (
                'id' => 1010,
                'car_make_id' => 63,
                'name' => 'Be Sun',
            ),
            10 => 
            array (
                'id' => 1011,
                'car_make_id' => 63,
                'name' => 'JS 50',
            ),
            11 => 
            array (
                'id' => 1012,
                'car_make_id' => 63,
                'name' => 'JS 50 L',
            ),
            12 => 
            array (
                'id' => 1013,
                'car_make_id' => 63,
                'name' => 'JS RC',
            ),
            13 => 
            array (
                'id' => 1014,
                'car_make_id' => 63,
                'name' => 'Nova',
            ),
            14 => 
            array (
                'id' => 1015,
                'car_make_id' => 63,
                'name' => 'Optima',
            ),
            15 => 
            array (
                'id' => 1016,
                'car_make_id' => 63,
                'name' => 'X - Too',
            ),
            16 => 
            array (
                'id' => 1017,
                'car_make_id' => 63,
                'name' => 'Other',
            ),
            17 => 
            array (
                'id' => 1018,
                'car_make_id' => 64,
                'name' => 'Aviator',
            ),
            18 => 
            array (
                'id' => 1019,
                'car_make_id' => 64,
                'name' => 'Continental',
            ),
            19 => 
            array (
                'id' => 1020,
                'car_make_id' => 64,
                'name' => 'LS',
            ),
            20 => 
            array (
                'id' => 1021,
                'car_make_id' => 64,
                'name' => 'Mark',
            ),
            21 => 
            array (
                'id' => 1022,
                'car_make_id' => 64,
                'name' => 'Navigator',
            ),
            22 => 
            array (
                'id' => 1023,
                'car_make_id' => 64,
                'name' => 'Town Car',
            ),
            23 => 
            array (
                'id' => 1024,
                'car_make_id' => 64,
                'name' => 'Other',
            ),
            24 => 
            array (
                'id' => 1025,
                'car_make_id' => 65,
                'name' => '340 R',
            ),
            25 => 
            array (
                'id' => 1026,
                'car_make_id' => 65,
                'name' => 'Cortina',
            ),
            26 => 
            array (
                'id' => 1027,
                'car_make_id' => 65,
                'name' => 'Elan',
            ),
            27 => 
            array (
                'id' => 1028,
                'car_make_id' => 65,
                'name' => 'Elise',
            ),
            28 => 
            array (
                'id' => 1029,
                'car_make_id' => 65,
                'name' => 'Elite',
            ),
            29 => 
            array (
                'id' => 1030,
                'car_make_id' => 65,
                'name' => 'Esprit',
            ),
            30 => 
            array (
                'id' => 1031,
                'car_make_id' => 65,
                'name' => 'Europa',
            ),
            31 => 
            array (
                'id' => 1032,
                'car_make_id' => 65,
                'name' => 'Evora',
            ),
            32 => 
            array (
                'id' => 1033,
                'car_make_id' => 65,
                'name' => 'Excel',
            ),
            33 => 
            array (
                'id' => 1034,
                'car_make_id' => 65,
                'name' => 'Exige',
            ),
            34 => 
            array (
                'id' => 1035,
                'car_make_id' => 65,
                'name' => 'Super Seven',
            ),
            35 => 
            array (
                'id' => 1036,
                'car_make_id' => 65,
                'name' => 'Other',
            ),
            36 => 
            array (
                'id' => 1037,
                'car_make_id' => 66,
                'name' => 'Other',
            ),
            37 => 
            array (
                'id' => 1038,
                'car_make_id' => 67,
                'name' => '222',
            ),
            38 => 
            array (
                'id' => 1039,
                'car_make_id' => 67,
                'name' => '224',
            ),
            39 => 
            array (
                'id' => 1040,
                'car_make_id' => 67,
                'name' => '228',
            ),
            40 => 
            array (
                'id' => 1041,
                'car_make_id' => 67,
                'name' => '3200',
            ),
            41 => 
            array (
                'id' => 1042,
                'car_make_id' => 67,
                'name' => '418',
            ),
            42 => 
            array (
                'id' => 1043,
                'car_make_id' => 67,
                'name' => '420',
            ),
            43 => 
            array (
                'id' => 1044,
                'car_make_id' => 67,
                'name' => '4200',
            ),
            44 => 
            array (
                'id' => 1045,
                'car_make_id' => 67,
                'name' => '422',
            ),
            45 => 
            array (
                'id' => 1046,
                'car_make_id' => 67,
                'name' => '424',
            ),
            46 => 
            array (
                'id' => 1047,
                'car_make_id' => 67,
                'name' => '430',
            ),
            47 => 
            array (
                'id' => 1048,
                'car_make_id' => 67,
                'name' => 'Biturbo',
            ),
            48 => 
            array (
                'id' => 1049,
                'car_make_id' => 67,
                'name' => 'Ghibli',
            ),
            49 => 
            array (
                'id' => 1050,
                'car_make_id' => 67,
                'name' => 'GranCabrio',
            ),
            50 => 
            array (
                'id' => 1051,
                'car_make_id' => 67,
                'name' => 'Gransport',
            ),
            51 => 
            array (
                'id' => 1052,
                'car_make_id' => 67,
                'name' => 'Granturismo',
            ),
            52 => 
            array (
                'id' => 1053,
                'car_make_id' => 67,
                'name' => 'Indy',
            ),
            53 => 
            array (
                'id' => 1054,
                'car_make_id' => 67,
                'name' => 'Karif',
            ),
            54 => 
            array (
                'id' => 1055,
                'car_make_id' => 67,
                'name' => 'Levante',
            ),
            55 => 
            array (
                'id' => 1056,
                'car_make_id' => 67,
                'name' => 'MC12',
            ),
            56 => 
            array (
                'id' => 1057,
                'car_make_id' => 67,
                'name' => 'Merak',
            ),
            57 => 
            array (
                'id' => 1058,
                'car_make_id' => 67,
                'name' => 'Quattroporte',
            ),
            58 => 
            array (
                'id' => 1059,
                'car_make_id' => 67,
                'name' => 'Shamal',
            ),
            59 => 
            array (
                'id' => 1060,
                'car_make_id' => 67,
                'name' => 'Spyder',
            ),
            60 => 
            array (
                'id' => 1061,
                'car_make_id' => 67,
                'name' => 'Other',
            ),
            61 => 
            array (
                'id' => 1062,
                'car_make_id' => 68,
                'name' => '57',
            ),
            62 => 
            array (
                'id' => 1063,
                'car_make_id' => 68,
                'name' => '62',
            ),
            63 => 
            array (
                'id' => 1064,
                'car_make_id' => 68,
                'name' => 'Pullman',
            ),
            64 => 
            array (
                'id' => 1065,
                'car_make_id' => 68,
                'name' => 'Other',
            ),
            65 => 
            array (
                'id' => 1066,
                'car_make_id' => 69,
                'name' => '121',
            ),
            66 => 
            array (
                'id' => 1067,
                'car_make_id' => 69,
                'name' => '2',
            ),
            67 => 
            array (
                'id' => 1068,
                'car_make_id' => 69,
                'name' => '3',
            ),
            68 => 
            array (
                'id' => 1069,
                'car_make_id' => 69,
                'name' => '323',
            ),
            69 => 
            array (
                'id' => 1070,
                'car_make_id' => 69,
                'name' => '5',
            ),
            70 => 
            array (
                'id' => 1071,
                'car_make_id' => 69,
                'name' => '6',
            ),
            71 => 
            array (
                'id' => 1072,
                'car_make_id' => 69,
                'name' => '626',
            ),
            72 => 
            array (
                'id' => 1073,
                'car_make_id' => 69,
                'name' => '929',
            ),
            73 => 
            array (
                'id' => 1074,
                'car_make_id' => 69,
                'name' => 'Bongo',
            ),
            74 => 
            array (
                'id' => 1075,
                'car_make_id' => 69,
                'name' => 'B series',
            ),
            75 => 
            array (
                'id' => 1076,
                'car_make_id' => 69,
                'name' => 'BT-50',
            ),
            76 => 
            array (
                'id' => 1077,
                'car_make_id' => 69,
                'name' => 'CX-3',
            ),
            77 => 
            array (
                'id' => 1078,
                'car_make_id' => 69,
                'name' => 'CX-30',
            ),
            78 => 
            array (
                'id' => 1079,
                'car_make_id' => 69,
                'name' => 'CX-5',
            ),
            79 => 
            array (
                'id' => 1080,
                'car_make_id' => 69,
                'name' => 'CX-7',
            ),
            80 => 
            array (
                'id' => 1081,
                'car_make_id' => 69,
                'name' => 'CX-9',
            ),
            81 => 
            array (
                'id' => 1082,
                'car_make_id' => 69,
                'name' => 'Demio',
            ),
            82 => 
            array (
                'id' => 1083,
                'car_make_id' => 69,
                'name' => 'E series',
            ),
            83 => 
            array (
                'id' => 1084,
                'car_make_id' => 69,
                'name' => 'Millenia',
            ),
            84 => 
            array (
                'id' => 1085,
                'car_make_id' => 69,
                'name' => 'MPV',
            ),
            85 => 
            array (
                'id' => 1086,
                'car_make_id' => 69,
                'name' => 'MX-3',
            ),
            86 => 
            array (
                'id' => 1087,
                'car_make_id' => 69,
                'name' => 'MX-5',
            ),
            87 => 
            array (
                'id' => 1088,
                'car_make_id' => 69,
                'name' => 'MX-6',
            ),
            88 => 
            array (
                'id' => 1089,
                'car_make_id' => 69,
                'name' => 'Premacy',
            ),
            89 => 
            array (
                'id' => 1090,
                'car_make_id' => 69,
                'name' => 'Protege',
            ),
            90 => 
            array (
                'id' => 1091,
                'car_make_id' => 69,
                'name' => 'RX-6',
            ),
            91 => 
            array (
                'id' => 1092,
                'car_make_id' => 69,
                'name' => 'RX-7',
            ),
            92 => 
            array (
                'id' => 1093,
                'car_make_id' => 69,
                'name' => 'RX-8',
            ),
            93 => 
            array (
                'id' => 1094,
                'car_make_id' => 69,
                'name' => 'Tribute',
            ),
            94 => 
            array (
                'id' => 1095,
                'car_make_id' => 69,
                'name' => 'Xedos',
            ),
            95 => 
            array (
                'id' => 1096,
                'car_make_id' => 69,
                'name' => 'Other',
            ),
            96 => 
            array (
                'id' => 1097,
                'car_make_id' => 70,
                'name' => '540C',
            ),
            97 => 
            array (
                'id' => 1098,
                'car_make_id' => 70,
                'name' => '570GT',
            ),
            98 => 
            array (
                'id' => 1099,
                'car_make_id' => 70,
                'name' => '570S',
            ),
            99 => 
            array (
                'id' => 1100,
                'car_make_id' => 70,
                'name' => '650S',
            ),
            100 => 
            array (
                'id' => 1101,
                'car_make_id' => 70,
                'name' => '650S Coupé',
            ),
            101 => 
            array (
                'id' => 1102,
                'car_make_id' => 70,
                'name' => '650S Spider',
            ),
            102 => 
            array (
                'id' => 1103,
                'car_make_id' => 70,
                'name' => '675LT',
            ),
            103 => 
            array (
                'id' => 1104,
                'car_make_id' => 70,
                'name' => '675LT Spider',
            ),
            104 => 
            array (
                'id' => 1105,
                'car_make_id' => 70,
                'name' => '720S',
            ),
            105 => 
            array (
                'id' => 1106,
                'car_make_id' => 70,
                'name' => 'GT',
            ),
            106 => 
            array (
                'id' => 1107,
                'car_make_id' => 70,
                'name' => 'MP4-12C',
            ),
            107 => 
            array (
                'id' => 1108,
                'car_make_id' => 70,
                'name' => 'P1',
            ),
            108 => 
            array (
                'id' => 1109,
                'car_make_id' => 70,
                'name' => 'Other',
            ),
            109 => 
            array (
                'id' => 1110,
                'car_make_id' => 71,
                'name' => '190',
            ),
            110 => 
            array (
                'id' => 1111,
                'car_make_id' => 71,
                'name' => '200',
            ),
            111 => 
            array (
                'id' => 1112,
                'car_make_id' => 71,
                'name' => '220',
            ),
            112 => 
            array (
                'id' => 1113,
                'car_make_id' => 71,
                'name' => '230',
            ),
            113 => 
            array (
                'id' => 1114,
                'car_make_id' => 71,
                'name' => '240',
            ),
            114 => 
            array (
                'id' => 1115,
                'car_make_id' => 71,
                'name' => '250',
            ),
            115 => 
            array (
                'id' => 1116,
                'car_make_id' => 71,
                'name' => '260',
            ),
            116 => 
            array (
                'id' => 1117,
                'car_make_id' => 71,
                'name' => '270',
            ),
            117 => 
            array (
                'id' => 1118,
                'car_make_id' => 71,
                'name' => '280',
            ),
            118 => 
            array (
                'id' => 1119,
                'car_make_id' => 71,
                'name' => '290',
            ),
            119 => 
            array (
                'id' => 1120,
                'car_make_id' => 71,
                'name' => '300',
            ),
            120 => 
            array (
                'id' => 1121,
                'car_make_id' => 71,
                'name' => '320',
            ),
            121 => 
            array (
                'id' => 1122,
                'car_make_id' => 71,
                'name' => '350',
            ),
            122 => 
            array (
                'id' => 1123,
                'car_make_id' => 71,
                'name' => '380',
            ),
            123 => 
            array (
                'id' => 1124,
                'car_make_id' => 71,
                'name' => '400',
            ),
            124 => 
            array (
                'id' => 1125,
                'car_make_id' => 71,
                'name' => '416',
            ),
            125 => 
            array (
                'id' => 1126,
                'car_make_id' => 71,
                'name' => '420',
            ),
            126 => 
            array (
                'id' => 1127,
                'car_make_id' => 71,
                'name' => '450',
            ),
            127 => 
            array (
                'id' => 1128,
                'car_make_id' => 71,
                'name' => '500',
            ),
            128 => 
            array (
                'id' => 1129,
                'car_make_id' => 71,
                'name' => '560',
            ),
            129 => 
            array (
                'id' => 1130,
                'car_make_id' => 71,
                'name' => '600',
            ),
            130 => 
            array (
                'id' => 1131,
                'car_make_id' => 71,
                'name' => 'A 140',
            ),
            131 => 
            array (
                'id' => 1132,
                'car_make_id' => 71,
                'name' => 'A 150',
            ),
            132 => 
            array (
                'id' => 1133,
                'car_make_id' => 71,
                'name' => 'A 160',
            ),
            133 => 
            array (
                'id' => 1134,
                'car_make_id' => 71,
                'name' => 'A 170',
            ),
            134 => 
            array (
                'id' => 1135,
                'car_make_id' => 71,
                'name' => 'A 180',
            ),
            135 => 
            array (
                'id' => 1136,
                'car_make_id' => 71,
                'name' => 'A 190',
            ),
            136 => 
            array (
                'id' => 1137,
                'car_make_id' => 71,
                'name' => 'A 200',
            ),
            137 => 
            array (
                'id' => 1138,
                'car_make_id' => 71,
                'name' => 'A 210',
            ),
            138 => 
            array (
                'id' => 1139,
                'car_make_id' => 71,
                'name' => 'A 220',
            ),
            139 => 
            array (
                'id' => 1140,
                'car_make_id' => 71,
                'name' => 'A 250',
            ),
            140 => 
            array (
                'id' => 1141,
                'car_make_id' => 71,
                'name' => 'A 35 AMG',
            ),
            141 => 
            array (
                'id' => 1142,
                'car_make_id' => 71,
                'name' => 'A 45 AMG',
            ),
            142 => 
            array (
                'id' => 1143,
                'car_make_id' => 71,
                'name' => 'B 150',
            ),
            143 => 
            array (
                'id' => 1144,
                'car_make_id' => 71,
                'name' => 'B 160',
            ),
            144 => 
            array (
                'id' => 1145,
                'car_make_id' => 71,
                'name' => 'B 170',
            ),
            145 => 
            array (
                'id' => 1146,
                'car_make_id' => 71,
                'name' => 'B 180',
            ),
            146 => 
            array (
                'id' => 1147,
                'car_make_id' => 71,
                'name' => 'B 200',
            ),
            147 => 
            array (
                'id' => 1148,
                'car_make_id' => 71,
                'name' => 'B 220',
            ),
            148 => 
            array (
                'id' => 1149,
                'car_make_id' => 71,
                'name' => 'B 250',
            ),
            149 => 
            array (
                'id' => 1150,
                'car_make_id' => 71,
                'name' => 'B Electric Drive',
            ),
            150 => 
            array (
                'id' => 1151,
                'car_make_id' => 71,
                'name' => 'C 160',
            ),
            151 => 
            array (
                'id' => 1152,
                'car_make_id' => 71,
                'name' => 'C 180',
            ),
            152 => 
            array (
                'id' => 1153,
                'car_make_id' => 71,
                'name' => 'C 200',
            ),
            153 => 
            array (
                'id' => 1154,
                'car_make_id' => 71,
                'name' => 'C 220',
            ),
            154 => 
            array (
                'id' => 1155,
                'car_make_id' => 71,
                'name' => 'C 230',
            ),
            155 => 
            array (
                'id' => 1156,
                'car_make_id' => 71,
                'name' => 'C 240',
            ),
            156 => 
            array (
                'id' => 1157,
                'car_make_id' => 71,
                'name' => 'C 250',
            ),
            157 => 
            array (
                'id' => 1158,
                'car_make_id' => 71,
                'name' => 'C 270',
            ),
            158 => 
            array (
                'id' => 1159,
                'car_make_id' => 71,
                'name' => 'C 280',
            ),
            159 => 
            array (
                'id' => 1160,
                'car_make_id' => 71,
                'name' => 'C 300',
            ),
            160 => 
            array (
                'id' => 1161,
                'car_make_id' => 71,
                'name' => 'C 30 AMG',
            ),
            161 => 
            array (
                'id' => 1162,
                'car_make_id' => 71,
                'name' => 'C 320',
            ),
            162 => 
            array (
                'id' => 1163,
                'car_make_id' => 71,
                'name' => 'C 32 AMG',
            ),
            163 => 
            array (
                'id' => 1164,
                'car_make_id' => 71,
                'name' => 'C 350',
            ),
            164 => 
            array (
                'id' => 1165,
                'car_make_id' => 71,
                'name' => 'C 36 AMG',
            ),
            165 => 
            array (
                'id' => 1166,
                'car_make_id' => 71,
                'name' => 'C 400',
            ),
            166 => 
            array (
                'id' => 1167,
                'car_make_id' => 71,
                'name' => 'C 43 AMG',
            ),
            167 => 
            array (
                'id' => 1168,
                'car_make_id' => 71,
                'name' => 'C 450 AMG',
            ),
            168 => 
            array (
                'id' => 1169,
                'car_make_id' => 71,
                'name' => 'C 55 AMG',
            ),
            169 => 
            array (
                'id' => 1170,
                'car_make_id' => 71,
                'name' => 'C 63 AMG',
            ),
            170 => 
            array (
                'id' => 1171,
                'car_make_id' => 71,
                'name' => 'CE 200',
            ),
            171 => 
            array (
                'id' => 1172,
                'car_make_id' => 71,
                'name' => 'CE 220',
            ),
            172 => 
            array (
                'id' => 1173,
                'car_make_id' => 71,
                'name' => 'CE 230',
            ),
            173 => 
            array (
                'id' => 1174,
                'car_make_id' => 71,
                'name' => 'CE 280',
            ),
            174 => 
            array (
                'id' => 1175,
                'car_make_id' => 71,
                'name' => 'CE 300',
            ),
            175 => 
            array (
                'id' => 1176,
                'car_make_id' => 71,
                'name' => 'CE 320',
            ),
            176 => 
            array (
                'id' => 1177,
                'car_make_id' => 71,
                'name' => 'Citan',
            ),
            177 => 
            array (
                'id' => 1178,
                'car_make_id' => 71,
                'name' => 'CLA 180',
            ),
            178 => 
            array (
                'id' => 1179,
                'car_make_id' => 71,
                'name' => 'CLA 180 Shooting Brake',
            ),
            179 => 
            array (
                'id' => 1180,
                'car_make_id' => 71,
                'name' => 'CLA 200',
            ),
            180 => 
            array (
                'id' => 1181,
                'car_make_id' => 71,
                'name' => 'CLA 200 Shooting Brake',
            ),
            181 => 
            array (
                'id' => 1182,
                'car_make_id' => 71,
                'name' => 'CLA 220',
            ),
            182 => 
            array (
                'id' => 1183,
                'car_make_id' => 71,
                'name' => 'CLA 220 Shooting Brake',
            ),
            183 => 
            array (
                'id' => 1184,
                'car_make_id' => 71,
                'name' => 'CLA 250',
            ),
            184 => 
            array (
                'id' => 1185,
                'car_make_id' => 71,
                'name' => 'CLA 250 Shooting Brake',
            ),
            185 => 
            array (
                'id' => 1186,
                'car_make_id' => 71,
                'name' => 'CLA 35 AMG',
            ),
            186 => 
            array (
                'id' => 1187,
                'car_make_id' => 71,
                'name' => 'CLA 45 AMG',
            ),
            187 => 
            array (
                'id' => 1188,
                'car_make_id' => 71,
                'name' => 'CLA 45 AMG Shooting Brake',
            ),
            188 => 
            array (
                'id' => 1189,
                'car_make_id' => 71,
                'name' => 'CLA Shooting Brake',
            ),
            189 => 
            array (
                'id' => 1190,
                'car_make_id' => 71,
                'name' => 'CLC 160',
            ),
            190 => 
            array (
                'id' => 1191,
                'car_make_id' => 71,
                'name' => 'CLC 180',
            ),
            191 => 
            array (
                'id' => 1192,
                'car_make_id' => 71,
                'name' => 'CLC 200',
            ),
            192 => 
            array (
                'id' => 1193,
                'car_make_id' => 71,
                'name' => 'CLC 220',
            ),
            193 => 
            array (
                'id' => 1194,
                'car_make_id' => 71,
                'name' => 'CLC 230',
            ),
            194 => 
            array (
                'id' => 1195,
                'car_make_id' => 71,
                'name' => 'CLC 250',
            ),
            195 => 
            array (
                'id' => 1196,
                'car_make_id' => 71,
                'name' => 'CLC 350',
            ),
            196 => 
            array (
                'id' => 1197,
                'car_make_id' => 71,
                'name' => 'CL 160',
            ),
            197 => 
            array (
                'id' => 1198,
                'car_make_id' => 71,
                'name' => 'CL 180',
            ),
            198 => 
            array (
                'id' => 1199,
                'car_make_id' => 71,
                'name' => 'CL 200',
            ),
            199 => 
            array (
                'id' => 1200,
                'car_make_id' => 71,
                'name' => 'CL 220',
            ),
            200 => 
            array (
                'id' => 1201,
                'car_make_id' => 71,
                'name' => 'CL 230',
            ),
            201 => 
            array (
                'id' => 1202,
                'car_make_id' => 71,
                'name' => 'CL 320',
            ),
            202 => 
            array (
                'id' => 1203,
                'car_make_id' => 71,
                'name' => 'CL 420',
            ),
            203 => 
            array (
                'id' => 1204,
                'car_make_id' => 71,
                'name' => 'CL 500',
            ),
            204 => 
            array (
                'id' => 1205,
                'car_make_id' => 71,
                'name' => 'CL 55 AMG',
            ),
            205 => 
            array (
                'id' => 1206,
                'car_make_id' => 71,
                'name' => 'CL 600',
            ),
            206 => 
            array (
                'id' => 1207,
                'car_make_id' => 71,
                'name' => 'CL 63 AMG',
            ),
            207 => 
            array (
                'id' => 1208,
                'car_make_id' => 71,
                'name' => 'CL 65 AMG',
            ),
            208 => 
            array (
                'id' => 1209,
                'car_make_id' => 71,
                'name' => 'CLK 200',
            ),
            209 => 
            array (
                'id' => 1210,
                'car_make_id' => 71,
                'name' => 'CLK 220',
            ),
            210 => 
            array (
                'id' => 1211,
                'car_make_id' => 71,
                'name' => 'CLK 230',
            ),
            211 => 
            array (
                'id' => 1212,
                'car_make_id' => 71,
                'name' => 'CLK 240',
            ),
            212 => 
            array (
                'id' => 1213,
                'car_make_id' => 71,
                'name' => 'CLK 270',
            ),
            213 => 
            array (
                'id' => 1214,
                'car_make_id' => 71,
                'name' => 'CLK 280',
            ),
            214 => 
            array (
                'id' => 1215,
                'car_make_id' => 71,
                'name' => 'CLK 320',
            ),
            215 => 
            array (
                'id' => 1216,
                'car_make_id' => 71,
                'name' => 'CLK 350',
            ),
            216 => 
            array (
                'id' => 1217,
                'car_make_id' => 71,
                'name' => 'CLK 430',
            ),
            217 => 
            array (
                'id' => 1218,
                'car_make_id' => 71,
                'name' => 'CLK 500',
            ),
            218 => 
            array (
                'id' => 1219,
                'car_make_id' => 71,
                'name' => 'CLK 55 AMG',
            ),
            219 => 
            array (
                'id' => 1220,
                'car_make_id' => 71,
                'name' => 'CLK 63 AMG',
            ),
            220 => 
            array (
                'id' => 1221,
                'car_make_id' => 71,
                'name' => 'CLS 220',
            ),
            221 => 
            array (
                'id' => 1222,
                'car_make_id' => 71,
                'name' => 'CLS 220 Shooting Brake',
            ),
            222 => 
            array (
                'id' => 1223,
                'car_make_id' => 71,
                'name' => 'CLS 250',
            ),
            223 => 
            array (
                'id' => 1224,
                'car_make_id' => 71,
                'name' => 'CLS 250 Shooting Brake',
            ),
            224 => 
            array (
                'id' => 1225,
                'car_make_id' => 71,
                'name' => 'CLS 280',
            ),
            225 => 
            array (
                'id' => 1226,
                'car_make_id' => 71,
                'name' => 'CLS 300',
            ),
            226 => 
            array (
                'id' => 1227,
                'car_make_id' => 71,
                'name' => 'CLS 320',
            ),
            227 => 
            array (
                'id' => 1228,
                'car_make_id' => 71,
                'name' => 'CLS 350',
            ),
            228 => 
            array (
                'id' => 1229,
                'car_make_id' => 71,
                'name' => 'CLS 350 Shooting Brake',
            ),
            229 => 
            array (
                'id' => 1230,
                'car_make_id' => 71,
                'name' => 'CLS 400',
            ),
            230 => 
            array (
                'id' => 1231,
                'car_make_id' => 71,
                'name' => 'CLS 400 Shooting Brake',
            ),
            231 => 
            array (
                'id' => 1232,
                'car_make_id' => 71,
                'name' => 'CLS 450',
            ),
            232 => 
            array (
                'id' => 1233,
                'car_make_id' => 71,
                'name' => 'CLS 500',
            ),
            233 => 
            array (
                'id' => 1234,
                'car_make_id' => 71,
                'name' => 'CLS 500 Shooting Brake',
            ),
            234 => 
            array (
                'id' => 1235,
                'car_make_id' => 71,
                'name' => 'CLS 53 AMG',
            ),
            235 => 
            array (
                'id' => 1236,
                'car_make_id' => 71,
                'name' => 'CLS 55 AMG',
            ),
            236 => 
            array (
                'id' => 1237,
                'car_make_id' => 71,
                'name' => 'CLS 63 AMG',
            ),
            237 => 
            array (
                'id' => 1238,
                'car_make_id' => 71,
                'name' => 'CLS 63 AMG Shooting Brake',
            ),
            238 => 
            array (
                'id' => 1239,
                'car_make_id' => 71,
                'name' => 'CLS Shooting Brake',
            ),
            239 => 
            array (
                'id' => 1240,
                'car_make_id' => 71,
                'name' => 'E 200',
            ),
            240 => 
            array (
                'id' => 1241,
                'car_make_id' => 71,
                'name' => 'E 220',
            ),
            241 => 
            array (
                'id' => 1242,
                'car_make_id' => 71,
                'name' => 'E 230',
            ),
            242 => 
            array (
                'id' => 1243,
                'car_make_id' => 71,
                'name' => 'E 240',
            ),
            243 => 
            array (
                'id' => 1244,
                'car_make_id' => 71,
                'name' => 'E 250',
            ),
            244 => 
            array (
                'id' => 1245,
                'car_make_id' => 71,
                'name' => 'E 260',
            ),
            245 => 
            array (
                'id' => 1246,
                'car_make_id' => 71,
                'name' => 'E 270',
            ),
            246 => 
            array (
                'id' => 1247,
                'car_make_id' => 71,
                'name' => 'E 280',
            ),
            247 => 
            array (
                'id' => 1248,
                'car_make_id' => 71,
                'name' => 'E 290',
            ),
            248 => 
            array (
                'id' => 1249,
                'car_make_id' => 71,
                'name' => 'E 300',
            ),
            249 => 
            array (
                'id' => 1250,
                'car_make_id' => 71,
                'name' => 'E 320',
            ),
            250 => 
            array (
                'id' => 1251,
                'car_make_id' => 71,
                'name' => 'E 350',
            ),
            251 => 
            array (
                'id' => 1252,
                'car_make_id' => 71,
                'name' => 'E 36 AMG',
            ),
            252 => 
            array (
                'id' => 1253,
                'car_make_id' => 71,
                'name' => 'E 400',
            ),
            253 => 
            array (
                'id' => 1254,
                'car_make_id' => 71,
                'name' => 'E 420',
            ),
            254 => 
            array (
                'id' => 1255,
                'car_make_id' => 71,
                'name' => 'E 430',
            ),
            255 => 
            array (
                'id' => 1256,
                'car_make_id' => 71,
                'name' => 'E 43 AMG',
            ),
            256 => 
            array (
                'id' => 1257,
                'car_make_id' => 71,
                'name' => 'E 450',
            ),
            257 => 
            array (
                'id' => 1258,
                'car_make_id' => 71,
                'name' => 'E 50',
            ),
            258 => 
            array (
                'id' => 1259,
                'car_make_id' => 71,
                'name' => 'E 500',
            ),
            259 => 
            array (
                'id' => 1260,
                'car_make_id' => 71,
                'name' => 'E 53 AMG',
            ),
            260 => 
            array (
                'id' => 1261,
                'car_make_id' => 71,
                'name' => 'E 55 AMG',
            ),
            261 => 
            array (
                'id' => 1262,
                'car_make_id' => 71,
                'name' => 'E 60 AMG',
            ),
            262 => 
            array (
                'id' => 1263,
                'car_make_id' => 71,
                'name' => 'E 63 AMG',
            ),
            263 => 
            array (
                'id' => 1264,
                'car_make_id' => 71,
                'name' => 'EQC',
            ),
            264 => 
            array (
                'id' => 1265,
                'car_make_id' => 71,
                'name' => 'G 230',
            ),
            265 => 
            array (
                'id' => 1266,
                'car_make_id' => 71,
                'name' => 'G 240',
            ),
            266 => 
            array (
                'id' => 1267,
                'car_make_id' => 71,
                'name' => 'G 250',
            ),
            267 => 
            array (
                'id' => 1268,
                'car_make_id' => 71,
                'name' => 'G 270',
            ),
            268 => 
            array (
                'id' => 1269,
                'car_make_id' => 71,
                'name' => 'G 280',
            ),
            269 => 
            array (
                'id' => 1270,
                'car_make_id' => 71,
                'name' => 'G 290',
            ),
            270 => 
            array (
                'id' => 1271,
                'car_make_id' => 71,
                'name' => 'G 300',
            ),
            271 => 
            array (
                'id' => 1272,
                'car_make_id' => 71,
                'name' => 'G 320',
            ),
            272 => 
            array (
                'id' => 1273,
                'car_make_id' => 71,
                'name' => 'G 350',
            ),
            273 => 
            array (
                'id' => 1274,
                'car_make_id' => 71,
                'name' => 'G 400',
            ),
            274 => 
            array (
                'id' => 1275,
                'car_make_id' => 71,
                'name' => 'G 500',
            ),
            275 => 
            array (
                'id' => 1276,
                'car_make_id' => 71,
                'name' => 'G 55 AMG',
            ),
            276 => 
            array (
                'id' => 1277,
                'car_make_id' => 71,
                'name' => 'G 63 AMG',
            ),
            277 => 
            array (
                'id' => 1278,
                'car_make_id' => 71,
                'name' => 'G 65 AMG',
            ),
            278 => 
            array (
                'id' => 1279,
                'car_make_id' => 71,
                'name' => 'GLA 180',
            ),
            279 => 
            array (
                'id' => 1280,
                'car_make_id' => 71,
                'name' => 'GLA 200',
            ),
            280 => 
            array (
                'id' => 1281,
                'car_make_id' => 71,
                'name' => 'GLA 220',
            ),
            281 => 
            array (
                'id' => 1282,
                'car_make_id' => 71,
                'name' => 'GLA 250',
            ),
            282 => 
            array (
                'id' => 1283,
                'car_make_id' => 71,
                'name' => 'GLA 45 AMG',
            ),
            283 => 
            array (
                'id' => 1284,
                'car_make_id' => 71,
                'name' => 'GLB 180',
            ),
            284 => 
            array (
                'id' => 1285,
                'car_make_id' => 71,
                'name' => 'GLB 200',
            ),
            285 => 
            array (
                'id' => 1286,
                'car_make_id' => 71,
                'name' => 'GLB 220',
            ),
            286 => 
            array (
                'id' => 1287,
                'car_make_id' => 71,
                'name' => 'GLB 250',
            ),
            287 => 
            array (
                'id' => 1288,
                'car_make_id' => 71,
                'name' => 'GLC 200',
            ),
            288 => 
            array (
                'id' => 1289,
                'car_make_id' => 71,
                'name' => 'GLC 220',
            ),
            289 => 
            array (
                'id' => 1290,
                'car_make_id' => 71,
                'name' => 'GLC 250',
            ),
            290 => 
            array (
                'id' => 1291,
                'car_make_id' => 71,
                'name' => 'GLC 300',
            ),
            291 => 
            array (
                'id' => 1292,
                'car_make_id' => 71,
                'name' => 'GLC 350',
            ),
            292 => 
            array (
                'id' => 1293,
                'car_make_id' => 71,
                'name' => 'GLC 400',
            ),
            293 => 
            array (
                'id' => 1294,
                'car_make_id' => 71,
                'name' => 'GLC 43 AMG',
            ),
            294 => 
            array (
                'id' => 1295,
                'car_make_id' => 71,
                'name' => 'GLC 63 AMG',
            ),
            295 => 
            array (
                'id' => 1296,
                'car_make_id' => 71,
                'name' => 'GL 320',
            ),
            296 => 
            array (
                'id' => 1297,
                'car_make_id' => 71,
                'name' => 'GL 350',
            ),
            297 => 
            array (
                'id' => 1298,
                'car_make_id' => 71,
                'name' => 'GL 400',
            ),
            298 => 
            array (
                'id' => 1299,
                'car_make_id' => 71,
                'name' => 'GL 420',
            ),
            299 => 
            array (
                'id' => 1300,
                'car_make_id' => 71,
                'name' => 'GL 450',
            ),
            300 => 
            array (
                'id' => 1301,
                'car_make_id' => 71,
                'name' => 'GL 500',
            ),
            301 => 
            array (
                'id' => 1302,
                'car_make_id' => 71,
                'name' => 'GL 55 AMG',
            ),
            302 => 
            array (
                'id' => 1303,
                'car_make_id' => 71,
                'name' => 'GL 63 AMG',
            ),
            303 => 
            array (
                'id' => 1304,
                'car_make_id' => 71,
                'name' => 'GLE 250',
            ),
            304 => 
            array (
                'id' => 1305,
                'car_make_id' => 71,
                'name' => 'GLE 300',
            ),
            305 => 
            array (
                'id' => 1306,
                'car_make_id' => 71,
                'name' => 'GLE 350',
            ),
            306 => 
            array (
                'id' => 1307,
                'car_make_id' => 71,
                'name' => 'GLE 400',
            ),
            307 => 
            array (
                'id' => 1308,
                'car_make_id' => 71,
                'name' => 'GLE 43 AMG',
            ),
            308 => 
            array (
                'id' => 1309,
                'car_make_id' => 71,
                'name' => 'GLE 450',
            ),
            309 => 
            array (
                'id' => 1310,
                'car_make_id' => 71,
                'name' => 'GLE 500',
            ),
            310 => 
            array (
                'id' => 1311,
                'car_make_id' => 71,
                'name' => 'GLE 53 AMG',
            ),
            311 => 
            array (
                'id' => 1312,
                'car_make_id' => 71,
                'name' => 'GLE 63 AMG',
            ),
            312 => 
            array (
                'id' => 1313,
                'car_make_id' => 71,
                'name' => 'GLK 200',
            ),
            313 => 
            array (
                'id' => 1314,
                'car_make_id' => 71,
                'name' => 'GLK 220',
            ),
            314 => 
            array (
                'id' => 1315,
                'car_make_id' => 71,
                'name' => 'GLK 250',
            ),
            315 => 
            array (
                'id' => 1316,
                'car_make_id' => 71,
                'name' => 'GLK 280',
            ),
            316 => 
            array (
                'id' => 1317,
                'car_make_id' => 71,
                'name' => 'GLK 300',
            ),
            317 => 
            array (
                'id' => 1318,
                'car_make_id' => 71,
                'name' => 'GLK 320',
            ),
            318 => 
            array (
                'id' => 1319,
                'car_make_id' => 71,
                'name' => 'GLK 350',
            ),
            319 => 
            array (
                'id' => 1320,
                'car_make_id' => 71,
                'name' => 'GLS 350',
            ),
            320 => 
            array (
                'id' => 1321,
                'car_make_id' => 71,
                'name' => 'GLS 400',
            ),
            321 => 
            array (
                'id' => 1322,
                'car_make_id' => 71,
                'name' => 'GLS 500',
            ),
            322 => 
            array (
                'id' => 1323,
                'car_make_id' => 71,
                'name' => 'GLS 63',
            ),
            323 => 
            array (
                'id' => 1324,
                'car_make_id' => 71,
                'name' => 'AMG GT',
            ),
            324 => 
            array (
                'id' => 1325,
                'car_make_id' => 71,
                'name' => 'AMG GT C',
            ),
            325 => 
            array (
                'id' => 1326,
                'car_make_id' => 71,
                'name' => 'AMG GT R',
            ),
            326 => 
            array (
                'id' => 1327,
                'car_make_id' => 71,
                'name' => 'AMG GT S',
            ),
            327 => 
            array (
                'id' => 1328,
                'car_make_id' => 71,
                'name' => 'MB 100',
            ),
            328 => 
            array (
                'id' => 1329,
                'car_make_id' => 71,
                'name' => 'ML 230',
            ),
            329 => 
            array (
                'id' => 1330,
                'car_make_id' => 71,
                'name' => 'ML 250',
            ),
            330 => 
            array (
                'id' => 1331,
                'car_make_id' => 71,
                'name' => 'ML 270',
            ),
            331 => 
            array (
                'id' => 1332,
                'car_make_id' => 71,
                'name' => 'ML 280',
            ),
            332 => 
            array (
                'id' => 1333,
                'car_make_id' => 71,
                'name' => 'ML 300',
            ),
            333 => 
            array (
                'id' => 1334,
                'car_make_id' => 71,
                'name' => 'ML 320',
            ),
            334 => 
            array (
                'id' => 1335,
                'car_make_id' => 71,
                'name' => 'ML 350',
            ),
            335 => 
            array (
                'id' => 1336,
                'car_make_id' => 71,
                'name' => 'ML 400',
            ),
            336 => 
            array (
                'id' => 1337,
                'car_make_id' => 71,
                'name' => 'ML 420',
            ),
            337 => 
            array (
                'id' => 1338,
                'car_make_id' => 71,
                'name' => 'ML 430',
            ),
            338 => 
            array (
                'id' => 1339,
                'car_make_id' => 71,
                'name' => 'ML 450',
            ),
            339 => 
            array (
                'id' => 1340,
                'car_make_id' => 71,
                'name' => 'ML 500',
            ),
            340 => 
            array (
                'id' => 1341,
                'car_make_id' => 71,
                'name' => 'ML 55 AMG',
            ),
            341 => 
            array (
                'id' => 1342,
                'car_make_id' => 71,
                'name' => 'ML 63 AMG',
            ),
            342 => 
            array (
                'id' => 1343,
                'car_make_id' => 71,
                'name' => 'R 280',
            ),
            343 => 
            array (
                'id' => 1344,
                'car_make_id' => 71,
                'name' => 'R 300',
            ),
            344 => 
            array (
                'id' => 1345,
                'car_make_id' => 71,
                'name' => 'R 320',
            ),
            345 => 
            array (
                'id' => 1346,
                'car_make_id' => 71,
                'name' => 'R 350',
            ),
            346 => 
            array (
                'id' => 1347,
                'car_make_id' => 71,
                'name' => 'R 500',
            ),
            347 => 
            array (
                'id' => 1348,
                'car_make_id' => 71,
                'name' => 'R 63 AMG',
            ),
            348 => 
            array (
                'id' => 1349,
                'car_make_id' => 71,
                'name' => 'S 250',
            ),
            349 => 
            array (
                'id' => 1350,
                'car_make_id' => 71,
                'name' => 'S 260',
            ),
            350 => 
            array (
                'id' => 1351,
                'car_make_id' => 71,
                'name' => 'S 280',
            ),
            351 => 
            array (
                'id' => 1352,
                'car_make_id' => 71,
                'name' => 'S 300',
            ),
            352 => 
            array (
                'id' => 1353,
                'car_make_id' => 71,
                'name' => 'S 320',
            ),
            353 => 
            array (
                'id' => 1354,
                'car_make_id' => 71,
                'name' => 'S 350',
            ),
            354 => 
            array (
                'id' => 1355,
                'car_make_id' => 71,
                'name' => 'S 400',
            ),
            355 => 
            array (
                'id' => 1356,
                'car_make_id' => 71,
                'name' => 'S 420',
            ),
            356 => 
            array (
                'id' => 1357,
                'car_make_id' => 71,
                'name' => 'S 430',
            ),
            357 => 
            array (
                'id' => 1358,
                'car_make_id' => 71,
                'name' => 'S 450',
            ),
            358 => 
            array (
                'id' => 1359,
                'car_make_id' => 71,
                'name' => 'S 500',
            ),
            359 => 
            array (
                'id' => 1360,
                'car_make_id' => 71,
                'name' => 'S 55',
            ),
            360 => 
            array (
                'id' => 1361,
                'car_make_id' => 71,
                'name' => 'S 550',
            ),
            361 => 
            array (
                'id' => 1362,
                'car_make_id' => 71,
                'name' => 'S 560',
            ),
            362 => 
            array (
                'id' => 1363,
                'car_make_id' => 71,
                'name' => 'S 600',
            ),
            363 => 
            array (
                'id' => 1364,
                'car_make_id' => 71,
                'name' => 'S 63 AMG',
            ),
            364 => 
            array (
                'id' => 1365,
                'car_make_id' => 71,
                'name' => 'S 650',
            ),
            365 => 
            array (
                'id' => 1366,
                'car_make_id' => 71,
                'name' => 'S 65 AMG',
            ),
            366 => 
            array (
                'id' => 1367,
                'car_make_id' => 71,
                'name' => 'SLC 180',
            ),
            367 => 
            array (
                'id' => 1368,
                'car_make_id' => 71,
                'name' => 'SLC 200',
            ),
            368 => 
            array (
                'id' => 1369,
                'car_make_id' => 71,
                'name' => 'SLC 250',
            ),
            369 => 
            array (
                'id' => 1370,
                'car_make_id' => 71,
                'name' => 'SLC 280',
            ),
            370 => 
            array (
                'id' => 1371,
                'car_make_id' => 71,
                'name' => 'SLC 300',
            ),
            371 => 
            array (
                'id' => 1372,
                'car_make_id' => 71,
                'name' => 'SLC 43 AMG',
            ),
            372 => 
            array (
                'id' => 1373,
                'car_make_id' => 71,
                'name' => 'SL 280',
            ),
            373 => 
            array (
                'id' => 1374,
                'car_make_id' => 71,
                'name' => 'SL 300',
            ),
            374 => 
            array (
                'id' => 1375,
                'car_make_id' => 71,
                'name' => 'SL 320',
            ),
            375 => 
            array (
                'id' => 1376,
                'car_make_id' => 71,
                'name' => 'SL 350',
            ),
            376 => 
            array (
                'id' => 1377,
                'car_make_id' => 71,
                'name' => 'SL 380',
            ),
            377 => 
            array (
                'id' => 1378,
                'car_make_id' => 71,
                'name' => 'SL 400',
            ),
            378 => 
            array (
                'id' => 1379,
                'car_make_id' => 71,
                'name' => 'SL 420',
            ),
            379 => 
            array (
                'id' => 1380,
                'car_make_id' => 71,
                'name' => 'SL 450',
            ),
            380 => 
            array (
                'id' => 1381,
                'car_make_id' => 71,
                'name' => 'SL 500',
            ),
            381 => 
            array (
                'id' => 1382,
                'car_make_id' => 71,
                'name' => 'SL 55 AMG',
            ),
            382 => 
            array (
                'id' => 1383,
                'car_make_id' => 71,
                'name' => 'SL 560',
            ),
            383 => 
            array (
                'id' => 1384,
                'car_make_id' => 71,
                'name' => 'SL 600',
            ),
            384 => 
            array (
                'id' => 1385,
                'car_make_id' => 71,
                'name' => 'SL 60 AMG',
            ),
            385 => 
            array (
                'id' => 1386,
                'car_make_id' => 71,
                'name' => 'SL 63 AMG',
            ),
            386 => 
            array (
                'id' => 1387,
                'car_make_id' => 71,
                'name' => 'SL 65 AMG',
            ),
            387 => 
            array (
                'id' => 1388,
                'car_make_id' => 71,
                'name' => 'SL 70 AMG',
            ),
            388 => 
            array (
                'id' => 1389,
                'car_make_id' => 71,
                'name' => 'SL 73 AMG',
            ),
            389 => 
            array (
                'id' => 1390,
                'car_make_id' => 71,
                'name' => 'SLK 200',
            ),
            390 => 
            array (
                'id' => 1391,
                'car_make_id' => 71,
                'name' => 'SLK 230',
            ),
            391 => 
            array (
                'id' => 1392,
                'car_make_id' => 71,
                'name' => 'SLK 250',
            ),
            392 => 
            array (
                'id' => 1393,
                'car_make_id' => 71,
                'name' => 'SLK 280',
            ),
            393 => 
            array (
                'id' => 1394,
                'car_make_id' => 71,
                'name' => 'SLK 300',
            ),
            394 => 
            array (
                'id' => 1395,
                'car_make_id' => 71,
                'name' => 'SLK 320',
            ),
            395 => 
            array (
                'id' => 1396,
                'car_make_id' => 71,
                'name' => 'SLK 32 AMG',
            ),
            396 => 
            array (
                'id' => 1397,
                'car_make_id' => 71,
                'name' => 'SLK 350',
            ),
            397 => 
            array (
                'id' => 1398,
                'car_make_id' => 71,
                'name' => 'SLK 55 AMG',
            ),
            398 => 
            array (
                'id' => 1399,
                'car_make_id' => 71,
                'name' => 'SLR',
            ),
            399 => 
            array (
                'id' => 1400,
                'car_make_id' => 71,
                'name' => 'SLS AMG',
            ),
            400 => 
            array (
                'id' => 1401,
                'car_make_id' => 71,
                'name' => 'Sprinter',
            ),
            401 => 
            array (
                'id' => 1402,
                'car_make_id' => 71,
                'name' => 'Vaneo',
            ),
            402 => 
            array (
                'id' => 1403,
                'car_make_id' => 71,
                'name' => 'Vario',
            ),
            403 => 
            array (
                'id' => 1404,
                'car_make_id' => 71,
                'name' => 'V 200',
            ),
            404 => 
            array (
                'id' => 1405,
                'car_make_id' => 71,
                'name' => 'V 220',
            ),
            405 => 
            array (
                'id' => 1406,
                'car_make_id' => 71,
                'name' => 'V 230',
            ),
            406 => 
            array (
                'id' => 1407,
                'car_make_id' => 71,
                'name' => 'V 250',
            ),
            407 => 
            array (
                'id' => 1408,
                'car_make_id' => 71,
                'name' => 'V 280',
            ),
            408 => 
            array (
                'id' => 1409,
                'car_make_id' => 71,
                'name' => 'V 300',
            ),
            409 => 
            array (
                'id' => 1410,
                'car_make_id' => 71,
                'name' => 'Viano',
            ),
            410 => 
            array (
                'id' => 1411,
                'car_make_id' => 71,
                'name' => 'Vito',
            ),
            411 => 
            array (
                'id' => 1412,
                'car_make_id' => 71,
                'name' => 'X 220',
            ),
            412 => 
            array (
                'id' => 1413,
                'car_make_id' => 71,
                'name' => 'X 250',
            ),
            413 => 
            array (
                'id' => 1414,
                'car_make_id' => 71,
                'name' => 'X 350',
            ),
            414 => 
            array (
                'id' => 1415,
                'car_make_id' => 71,
                'name' => 'Other',
            ),
            415 => 
            array (
                'id' => 1416,
                'car_make_id' => 72,
                'name' => 'MGA',
            ),
            416 => 
            array (
                'id' => 1417,
                'car_make_id' => 72,
                'name' => 'MGB',
            ),
            417 => 
            array (
                'id' => 1418,
                'car_make_id' => 72,
                'name' => 'MGF',
            ),
            418 => 
            array (
                'id' => 1419,
                'car_make_id' => 72,
                'name' => 'Midget',
            ),
            419 => 
            array (
                'id' => 1420,
                'car_make_id' => 72,
                'name' => 'Montego',
            ),
            420 => 
            array (
                'id' => 1421,
                'car_make_id' => 72,
                'name' => 'TD',
            ),
            421 => 
            array (
                'id' => 1422,
                'car_make_id' => 72,
                'name' => 'TF',
            ),
            422 => 
            array (
                'id' => 1423,
                'car_make_id' => 72,
                'name' => 'ZR',
            ),
            423 => 
            array (
                'id' => 1424,
                'car_make_id' => 72,
                'name' => 'ZS',
            ),
            424 => 
            array (
                'id' => 1425,
                'car_make_id' => 72,
                'name' => 'ZT',
            ),
            425 => 
            array (
                'id' => 1426,
                'car_make_id' => 72,
                'name' => 'Other',
            ),
            426 => 
            array (
                'id' => 1427,
                'car_make_id' => 73,
                'name' => 'DUÈ',
            ),
            427 => 
            array (
                'id' => 1428,
                'car_make_id' => 73,
                'name' => 'Flex',
            ),
            428 => 
            array (
                'id' => 1429,
                'car_make_id' => 73,
                'name' => 'M.Go',
            ),
            429 => 
            array (
                'id' => 1430,
                'car_make_id' => 73,
                'name' => 'M-8',
            ),
            430 => 
            array (
                'id' => 1431,
                'car_make_id' => 73,
                'name' => 'MC1',
            ),
            431 => 
            array (
                'id' => 1432,
                'car_make_id' => 73,
                'name' => 'MC2',
            ),
            432 => 
            array (
                'id' => 1433,
                'car_make_id' => 73,
                'name' => 'Virgo',
            ),
            433 => 
            array (
                'id' => 1434,
                'car_make_id' => 73,
                'name' => 'Other',
            ),
            434 => 
            array (
                'id' => 1435,
                'car_make_id' => 74,
                'name' => 'Cooper Cabrio',
            ),
            435 => 
            array (
                'id' => 1436,
                'car_make_id' => 74,
                'name' => 'Cooper D Cabrio',
            ),
            436 => 
            array (
                'id' => 1437,
                'car_make_id' => 74,
                'name' => 'Cooper S Cabrio',
            ),
            437 => 
            array (
                'id' => 1438,
                'car_make_id' => 74,
                'name' => 'Cooper SD Cabrio',
            ),
            438 => 
            array (
                'id' => 1439,
                'car_make_id' => 74,
                'name' => 'John Cooper Works Cabrio',
            ),
            439 => 
            array (
                'id' => 1440,
                'car_make_id' => 74,
                'name' => 'One Cabrio',
            ),
            440 => 
            array (
                'id' => 1441,
                'car_make_id' => 74,
                'name' => 'Cooper Clubman',
            ),
            441 => 
            array (
                'id' => 1442,
                'car_make_id' => 74,
                'name' => 'Cooper D Clubman',
            ),
            442 => 
            array (
                'id' => 1443,
                'car_make_id' => 74,
                'name' => 'Cooper S Clubman',
            ),
            443 => 
            array (
                'id' => 1444,
                'car_make_id' => 74,
                'name' => 'Cooper SD Clubman',
            ),
            444 => 
            array (
                'id' => 1445,
                'car_make_id' => 74,
                'name' => 'John Cooper Works Clubman',
            ),
            445 => 
            array (
                'id' => 1446,
                'car_make_id' => 74,
                'name' => 'One Clubman',
            ),
            446 => 
            array (
                'id' => 1447,
                'car_make_id' => 74,
                'name' => 'One D Clubman',
            ),
            447 => 
            array (
                'id' => 1448,
                'car_make_id' => 74,
                'name' => 'Clubvan',
            ),
            448 => 
            array (
                'id' => 1449,
                'car_make_id' => 74,
                'name' => 'Cooper Countryman',
            ),
            449 => 
            array (
                'id' => 1450,
                'car_make_id' => 74,
                'name' => 'Cooper D Countryman',
            ),
            450 => 
            array (
                'id' => 1451,
                'car_make_id' => 74,
                'name' => 'Cooper S Countryman',
            ),
            451 => 
            array (
                'id' => 1452,
                'car_make_id' => 74,
                'name' => 'Cooper SD Countryman',
            ),
            452 => 
            array (
                'id' => 1453,
                'car_make_id' => 74,
                'name' => 'John Cooper Works Countryman',
            ),
            453 => 
            array (
                'id' => 1454,
                'car_make_id' => 74,
                'name' => 'One Countryman',
            ),
            454 => 
            array (
                'id' => 1455,
                'car_make_id' => 74,
                'name' => 'One D Countryman',
            ),
            455 => 
            array (
                'id' => 1456,
                'car_make_id' => 74,
                'name' => 'Cooper Coupé',
            ),
            456 => 
            array (
                'id' => 1457,
                'car_make_id' => 74,
                'name' => 'Cooper S Coupé',
            ),
            457 => 
            array (
                'id' => 1458,
                'car_make_id' => 74,
                'name' => 'Cooper SD Coupé',
            ),
            458 => 
            array (
                'id' => 1459,
                'car_make_id' => 74,
                'name' => 'John Cooper Works Coupé',
            ),
            459 => 
            array (
                'id' => 1460,
                'car_make_id' => 74,
                'name' => '1000',
            ),
            460 => 
            array (
                'id' => 1461,
                'car_make_id' => 74,
                'name' => '1300',
            ),
            461 => 
            array (
                'id' => 1462,
                'car_make_id' => 74,
                'name' => 'Cooper',
            ),
            462 => 
            array (
                'id' => 1463,
                'car_make_id' => 74,
                'name' => 'Cooper D',
            ),
            463 => 
            array (
                'id' => 1464,
                'car_make_id' => 74,
                'name' => 'Cooper S',
            ),
            464 => 
            array (
                'id' => 1465,
                'car_make_id' => 74,
                'name' => 'Cooper SD',
            ),
            465 => 
            array (
                'id' => 1466,
                'car_make_id' => 74,
                'name' => 'John Cooper Works',
            ),
            466 => 
            array (
                'id' => 1467,
                'car_make_id' => 74,
                'name' => 'ONE',
            ),
            467 => 
            array (
                'id' => 1468,
                'car_make_id' => 74,
                'name' => 'One D',
            ),
            468 => 
            array (
                'id' => 1469,
                'car_make_id' => 74,
                'name' => 'One First',
            ),
            469 => 
            array (
                'id' => 1470,
                'car_make_id' => 74,
                'name' => 'Cooper D Paceman',
            ),
            470 => 
            array (
                'id' => 1471,
                'car_make_id' => 74,
                'name' => 'Cooper Paceman',
            ),
            471 => 
            array (
                'id' => 1472,
                'car_make_id' => 74,
                'name' => 'Cooper SD Paceman',
            ),
            472 => 
            array (
                'id' => 1473,
                'car_make_id' => 74,
                'name' => 'Cooper S Paceman',
            ),
            473 => 
            array (
                'id' => 1474,
                'car_make_id' => 74,
                'name' => 'John Cooper Works Paceman',
            ),
            474 => 
            array (
                'id' => 1475,
                'car_make_id' => 74,
                'name' => 'Cooper Roadster',
            ),
            475 => 
            array (
                'id' => 1476,
                'car_make_id' => 74,
                'name' => 'Cooper SD Roadster',
            ),
            476 => 
            array (
                'id' => 1477,
                'car_make_id' => 74,
                'name' => 'Cooper S Roadster',
            ),
            477 => 
            array (
                'id' => 1478,
                'car_make_id' => 74,
                'name' => 'John Cooper Works Roadster',
            ),
            478 => 
            array (
                'id' => 1479,
                'car_make_id' => 74,
                'name' => 'Other',
            ),
            479 => 
            array (
                'id' => 1480,
                'car_make_id' => 75,
                'name' => '3000 GT',
            ),
            480 => 
            array (
                'id' => 1481,
                'car_make_id' => 75,
                'name' => 'ASX',
            ),
            481 => 
            array (
                'id' => 1482,
                'car_make_id' => 75,
                'name' => 'Canter',
            ),
            482 => 
            array (
                'id' => 1483,
                'car_make_id' => 75,
                'name' => 'Carisma',
            ),
            483 => 
            array (
                'id' => 1484,
                'car_make_id' => 75,
                'name' => 'Colt',
            ),
            484 => 
            array (
                'id' => 1485,
                'car_make_id' => 75,
                'name' => 'Cordia',
            ),
            485 => 
            array (
                'id' => 1486,
                'car_make_id' => 75,
                'name' => 'Cosmos',
            ),
            486 => 
            array (
                'id' => 1487,
                'car_make_id' => 75,
                'name' => 'Diamante',
            ),
            487 => 
            array (
                'id' => 1488,
                'car_make_id' => 75,
                'name' => 'Eclipse',
            ),
            488 => 
            array (
                'id' => 1489,
                'car_make_id' => 75,
                'name' => 'Eclipse Cross',
            ),
            489 => 
            array (
                'id' => 1490,
                'car_make_id' => 75,
                'name' => 'Galant',
            ),
            490 => 
            array (
                'id' => 1491,
                'car_make_id' => 75,
                'name' => 'Galloper',
            ),
            491 => 
            array (
                'id' => 1492,
                'car_make_id' => 75,
                'name' => 'Grandis',
            ),
            492 => 
            array (
                'id' => 1493,
                'car_make_id' => 75,
                'name' => 'i-MiEV',
            ),
            493 => 
            array (
                'id' => 1494,
                'car_make_id' => 75,
                'name' => 'L200',
            ),
            494 => 
            array (
                'id' => 1495,
                'car_make_id' => 75,
                'name' => 'L300',
            ),
            495 => 
            array (
                'id' => 1496,
                'car_make_id' => 75,
                'name' => 'L400',
            ),
            496 => 
            array (
                'id' => 1497,
                'car_make_id' => 75,
                'name' => 'Lancer',
            ),
            497 => 
            array (
                'id' => 1498,
                'car_make_id' => 75,
                'name' => 'Mirage',
            ),
            498 => 
            array (
                'id' => 1499,
                'car_make_id' => 75,
                'name' => 'Montero',
            ),
            499 => 
            array (
                'id' => 1500,
                'car_make_id' => 75,
                'name' => 'Outlander',
            ),
        ));
        \DB::table('car_models')->insert(array (
            0 => 
            array (
                'id' => 1501,
                'car_make_id' => 75,
                'name' => 'Pajero',
            ),
            1 => 
            array (
                'id' => 1502,
                'car_make_id' => 75,
                'name' => 'Pajero Pinin',
            ),
            2 => 
            array (
                'id' => 1503,
                'car_make_id' => 75,
                'name' => 'Pick-up',
            ),
            3 => 
            array (
                'id' => 1504,
                'car_make_id' => 75,
                'name' => 'Plug-in Hybrid Outlander',
            ),
            4 => 
            array (
                'id' => 1505,
                'car_make_id' => 75,
                'name' => 'Santamo',
            ),
            5 => 
            array (
                'id' => 1506,
                'car_make_id' => 75,
                'name' => 'Sapporo',
            ),
            6 => 
            array (
                'id' => 1507,
                'car_make_id' => 75,
                'name' => 'Sigma',
            ),
            7 => 
            array (
                'id' => 1508,
                'car_make_id' => 75,
                'name' => 'Space Gear',
            ),
            8 => 
            array (
                'id' => 1509,
                'car_make_id' => 75,
                'name' => 'Space Runner',
            ),
            9 => 
            array (
                'id' => 1510,
                'car_make_id' => 75,
                'name' => 'Space Star',
            ),
            10 => 
            array (
                'id' => 1511,
                'car_make_id' => 75,
                'name' => 'Space Wagon',
            ),
            11 => 
            array (
                'id' => 1512,
                'car_make_id' => 75,
                'name' => 'Starion',
            ),
            12 => 
            array (
                'id' => 1513,
                'car_make_id' => 75,
                'name' => 'Tredia',
            ),
            13 => 
            array (
                'id' => 1514,
                'car_make_id' => 75,
                'name' => 'Other',
            ),
            14 => 
            array (
                'id' => 1515,
                'car_make_id' => 76,
                'name' => '3 Wheeler',
            ),
            15 => 
            array (
                'id' => 1516,
                'car_make_id' => 76,
                'name' => '4/4',
            ),
            16 => 
            array (
                'id' => 1517,
                'car_make_id' => 76,
                'name' => 'Aero 8',
            ),
            17 => 
            array (
                'id' => 1518,
                'car_make_id' => 76,
                'name' => 'Plus 4',
            ),
            18 => 
            array (
                'id' => 1519,
                'car_make_id' => 76,
                'name' => 'Plus 8',
            ),
            19 => 
            array (
                'id' => 1520,
                'car_make_id' => 76,
                'name' => 'Roadster',
            ),
            20 => 
            array (
                'id' => 1521,
                'car_make_id' => 76,
                'name' => 'Other',
            ),
            21 => 
            array (
                'id' => 1522,
                'car_make_id' => 77,
                'name' => '100 NX',
            ),
            22 => 
            array (
                'id' => 1523,
                'car_make_id' => 77,
                'name' => '200 SX',
            ),
            23 => 
            array (
                'id' => 1524,
                'car_make_id' => 77,
                'name' => '240 SX',
            ),
            24 => 
            array (
                'id' => 1525,
                'car_make_id' => 77,
                'name' => '280 ZX',
            ),
            25 => 
            array (
                'id' => 1526,
                'car_make_id' => 77,
                'name' => '300 ZX',
            ),
            26 => 
            array (
                'id' => 1527,
                'car_make_id' => 77,
                'name' => '350Z',
            ),
            27 => 
            array (
                'id' => 1528,
                'car_make_id' => 77,
                'name' => '370Z',
            ),
            28 => 
            array (
                'id' => 1529,
                'car_make_id' => 77,
                'name' => 'Almera',
            ),
            29 => 
            array (
                'id' => 1530,
                'car_make_id' => 77,
                'name' => 'Almera Tino',
            ),
            30 => 
            array (
                'id' => 1531,
                'car_make_id' => 77,
                'name' => 'Altima',
            ),
            31 => 
            array (
                'id' => 1532,
                'car_make_id' => 77,
                'name' => 'Armada',
            ),
            32 => 
            array (
                'id' => 1533,
                'car_make_id' => 77,
                'name' => 'Bluebird',
            ),
            33 => 
            array (
                'id' => 1534,
                'car_make_id' => 77,
                'name' => 'Cabstar',
            ),
            34 => 
            array (
                'id' => 1535,
                'car_make_id' => 77,
                'name' => 'Cargo',
            ),
            35 => 
            array (
                'id' => 1536,
                'car_make_id' => 77,
                'name' => 'Cherry',
            ),
            36 => 
            array (
                'id' => 1537,
                'car_make_id' => 77,
                'name' => 'Cube',
            ),
            37 => 
            array (
                'id' => 1538,
                'car_make_id' => 77,
                'name' => 'e-NV200',
            ),
            38 => 
            array (
                'id' => 1539,
                'car_make_id' => 77,
                'name' => 'Evalia',
            ),
            39 => 
            array (
                'id' => 1540,
                'car_make_id' => 77,
                'name' => 'Frontier',
            ),
            40 => 
            array (
                'id' => 1541,
                'car_make_id' => 77,
                'name' => 'GT-R',
            ),
            41 => 
            array (
                'id' => 1542,
                'car_make_id' => 77,
                'name' => 'Interstar',
            ),
            42 => 
            array (
                'id' => 1543,
                'car_make_id' => 77,
                'name' => 'Juke',
            ),
            43 => 
            array (
                'id' => 1544,
                'car_make_id' => 77,
                'name' => 'King Cab',
            ),
            44 => 
            array (
                'id' => 1545,
                'car_make_id' => 77,
                'name' => 'Kubistar',
            ),
            45 => 
            array (
                'id' => 1546,
                'car_make_id' => 77,
                'name' => 'Laurel',
            ),
            46 => 
            array (
                'id' => 1547,
                'car_make_id' => 77,
                'name' => 'Leaf',
            ),
            47 => 
            array (
                'id' => 1548,
                'car_make_id' => 77,
                'name' => 'Maxima',
            ),
            48 => 
            array (
                'id' => 1549,
                'car_make_id' => 77,
                'name' => 'Micra',
            ),
            49 => 
            array (
                'id' => 1550,
                'car_make_id' => 77,
                'name' => 'Murano',
            ),
            50 => 
            array (
                'id' => 1551,
                'car_make_id' => 77,
                'name' => 'Navara',
            ),
            51 => 
            array (
                'id' => 1552,
                'car_make_id' => 77,
                'name' => 'Note',
            ),
            52 => 
            array (
                'id' => 1553,
                'car_make_id' => 77,
                'name' => 'NP 300',
            ),
            53 => 
            array (
                'id' => 1554,
                'car_make_id' => 77,
                'name' => 'NV200',
            ),
            54 => 
            array (
                'id' => 1555,
                'car_make_id' => 77,
                'name' => 'NV250',
            ),
            55 => 
            array (
                'id' => 1556,
                'car_make_id' => 77,
                'name' => 'NV300',
            ),
            56 => 
            array (
                'id' => 1557,
                'car_make_id' => 77,
                'name' => 'NV400',
            ),
            57 => 
            array (
                'id' => 1558,
                'car_make_id' => 77,
                'name' => 'Pathfinder',
            ),
            58 => 
            array (
                'id' => 1559,
                'car_make_id' => 77,
                'name' => 'Patrol',
            ),
            59 => 
            array (
                'id' => 1560,
                'car_make_id' => 77,
                'name' => 'PickUp',
            ),
            60 => 
            array (
                'id' => 1561,
                'car_make_id' => 77,
                'name' => 'Pixo',
            ),
            61 => 
            array (
                'id' => 1562,
                'car_make_id' => 77,
                'name' => 'Prairie',
            ),
            62 => 
            array (
                'id' => 1563,
                'car_make_id' => 77,
                'name' => 'Primastar',
            ),
            63 => 
            array (
                'id' => 1564,
                'car_make_id' => 77,
                'name' => 'Primera',
            ),
            64 => 
            array (
                'id' => 1565,
                'car_make_id' => 77,
                'name' => 'Pulsar',
            ),
            65 => 
            array (
                'id' => 1566,
                'car_make_id' => 77,
                'name' => 'Qashqai',
            ),
            66 => 
            array (
                'id' => 1567,
                'car_make_id' => 77,
                'name' => 'Qashqai+2',
            ),
            67 => 
            array (
                'id' => 1568,
                'car_make_id' => 77,
                'name' => 'Quest',
            ),
            68 => 
            array (
                'id' => 1569,
                'car_make_id' => 77,
                'name' => 'Sentra',
            ),
            69 => 
            array (
                'id' => 1570,
                'car_make_id' => 77,
                'name' => 'Serena',
            ),
            70 => 
            array (
                'id' => 1571,
                'car_make_id' => 77,
                'name' => 'Silvia',
            ),
            71 => 
            array (
                'id' => 1572,
                'car_make_id' => 77,
                'name' => 'Skyline',
            ),
            72 => 
            array (
                'id' => 1573,
                'car_make_id' => 77,
                'name' => 'Sunny',
            ),
            73 => 
            array (
                'id' => 1574,
                'car_make_id' => 77,
                'name' => 'Terrano',
            ),
            74 => 
            array (
                'id' => 1575,
                'car_make_id' => 77,
                'name' => 'Tiida',
            ),
            75 => 
            array (
                'id' => 1576,
                'car_make_id' => 77,
                'name' => 'Titan',
            ),
            76 => 
            array (
                'id' => 1577,
                'car_make_id' => 77,
                'name' => 'Trade',
            ),
            77 => 
            array (
                'id' => 1578,
                'car_make_id' => 77,
                'name' => 'Urvan',
            ),
            78 => 
            array (
                'id' => 1579,
                'car_make_id' => 77,
                'name' => 'Vanette',
            ),
            79 => 
            array (
                'id' => 1580,
                'car_make_id' => 77,
                'name' => 'X-Trail',
            ),
            80 => 
            array (
                'id' => 1581,
                'car_make_id' => 77,
                'name' => 'Other',
            ),
            81 => 
            array (
                'id' => 1582,
                'car_make_id' => 78,
                'name' => 'Other',
            ),
            82 => 
            array (
                'id' => 1583,
                'car_make_id' => 79,
                'name' => 'Bravada',
            ),
            83 => 
            array (
                'id' => 1584,
                'car_make_id' => 79,
                'name' => 'Custom Cruiser',
            ),
            84 => 
            array (
                'id' => 1585,
                'car_make_id' => 79,
                'name' => 'Cutlass',
            ),
            85 => 
            array (
                'id' => 1586,
                'car_make_id' => 79,
                'name' => 'Delta 88',
            ),
            86 => 
            array (
                'id' => 1587,
                'car_make_id' => 79,
                'name' => 'Silhouette',
            ),
            87 => 
            array (
                'id' => 1588,
                'car_make_id' => 79,
                'name' => 'Supreme',
            ),
            88 => 
            array (
                'id' => 1589,
                'car_make_id' => 79,
                'name' => 'Toronado',
            ),
            89 => 
            array (
                'id' => 1590,
                'car_make_id' => 79,
                'name' => 'Other',
            ),
            90 => 
            array (
                'id' => 1591,
                'car_make_id' => 80,
                'name' => 'Adam',
            ),
            91 => 
            array (
                'id' => 1592,
                'car_make_id' => 80,
                'name' => 'Agila',
            ),
            92 => 
            array (
                'id' => 1593,
                'car_make_id' => 80,
                'name' => 'Ampera',
            ),
            93 => 
            array (
                'id' => 1594,
                'car_make_id' => 80,
                'name' => 'Ampera-e',
            ),
            94 => 
            array (
                'id' => 1595,
                'car_make_id' => 80,
                'name' => 'Antara',
            ),
            95 => 
            array (
                'id' => 1596,
                'car_make_id' => 80,
                'name' => 'Arena',
            ),
            96 => 
            array (
                'id' => 1597,
                'car_make_id' => 80,
                'name' => 'Ascona',
            ),
            97 => 
            array (
                'id' => 1598,
                'car_make_id' => 80,
                'name' => 'Astra',
            ),
            98 => 
            array (
                'id' => 1599,
                'car_make_id' => 80,
                'name' => 'Calibra',
            ),
            99 => 
            array (
                'id' => 1600,
                'car_make_id' => 80,
                'name' => 'Campo',
            ),
            100 => 
            array (
                'id' => 1601,
                'car_make_id' => 80,
                'name' => 'Cascada',
            ),
            101 => 
            array (
                'id' => 1602,
                'car_make_id' => 80,
                'name' => 'Cavalier',
            ),
            102 => 
            array (
                'id' => 1603,
                'car_make_id' => 80,
                'name' => 'Combo',
            ),
            103 => 
            array (
                'id' => 1604,
                'car_make_id' => 80,
                'name' => 'Commodore',
            ),
            104 => 
            array (
                'id' => 1605,
                'car_make_id' => 80,
                'name' => 'Corsa',
            ),
            105 => 
            array (
                'id' => 1606,
                'car_make_id' => 80,
                'name' => 'Crossland X',
            ),
            106 => 
            array (
                'id' => 1607,
                'car_make_id' => 80,
                'name' => 'Diplomat',
            ),
            107 => 
            array (
                'id' => 1608,
                'car_make_id' => 80,
                'name' => 'Frontera',
            ),
            108 => 
            array (
                'id' => 1609,
                'car_make_id' => 80,
                'name' => 'Grandland X',
            ),
            109 => 
            array (
                'id' => 1610,
                'car_make_id' => 80,
                'name' => 'GT',
            ),
            110 => 
            array (
                'id' => 1611,
                'car_make_id' => 80,
                'name' => 'Insignia',
            ),
            111 => 
            array (
                'id' => 1612,
                'car_make_id' => 80,
                'name' => 'Insignia CT',
            ),
            112 => 
            array (
                'id' => 1613,
                'car_make_id' => 80,
                'name' => 'Kadett',
            ),
            113 => 
            array (
                'id' => 1614,
                'car_make_id' => 80,
                'name' => 'Karl',
            ),
            114 => 
            array (
                'id' => 1615,
                'car_make_id' => 80,
                'name' => 'Manta',
            ),
            115 => 
            array (
                'id' => 1616,
                'car_make_id' => 80,
                'name' => 'Meriva',
            ),
            116 => 
            array (
                'id' => 1617,
                'car_make_id' => 80,
                'name' => 'Mokka',
            ),
            117 => 
            array (
                'id' => 1618,
                'car_make_id' => 80,
                'name' => 'Mokka X',
            ),
            118 => 
            array (
                'id' => 1619,
                'car_make_id' => 80,
                'name' => 'Monterey',
            ),
            119 => 
            array (
                'id' => 1620,
                'car_make_id' => 80,
                'name' => 'Monza',
            ),
            120 => 
            array (
                'id' => 1621,
                'car_make_id' => 80,
                'name' => 'Movano',
            ),
            121 => 
            array (
                'id' => 1622,
                'car_make_id' => 80,
                'name' => 'Nova',
            ),
            122 => 
            array (
                'id' => 1623,
                'car_make_id' => 80,
                'name' => 'Omega',
            ),
            123 => 
            array (
                'id' => 1624,
                'car_make_id' => 80,
                'name' => 'Pick Up Sportscap',
            ),
            124 => 
            array (
                'id' => 1625,
                'car_make_id' => 80,
                'name' => 'Rekord',
            ),
            125 => 
            array (
                'id' => 1626,
                'car_make_id' => 80,
                'name' => 'Senator',
            ),
            126 => 
            array (
                'id' => 1627,
                'car_make_id' => 80,
                'name' => 'Signum',
            ),
            127 => 
            array (
                'id' => 1628,
                'car_make_id' => 80,
                'name' => 'Sintra',
            ),
            128 => 
            array (
                'id' => 1629,
                'car_make_id' => 80,
                'name' => 'Speedster',
            ),
            129 => 
            array (
                'id' => 1630,
                'car_make_id' => 80,
                'name' => 'Tigra',
            ),
            130 => 
            array (
                'id' => 1631,
                'car_make_id' => 80,
                'name' => 'Vectra',
            ),
            131 => 
            array (
                'id' => 1632,
                'car_make_id' => 80,
                'name' => 'Vivaro',
            ),
            132 => 
            array (
                'id' => 1633,
                'car_make_id' => 80,
                'name' => 'Zafira',
            ),
            133 => 
            array (
                'id' => 1634,
                'car_make_id' => 80,
                'name' => 'Zafira Life',
            ),
            134 => 
            array (
                'id' => 1635,
                'car_make_id' => 80,
                'name' => 'Zafira Tourer',
            ),
            135 => 
            array (
                'id' => 1636,
                'car_make_id' => 80,
                'name' => 'Other',
            ),
            136 => 
            array (
                'id' => 1637,
                'car_make_id' => 81,
                'name' => 'Huayra',
            ),
            137 => 
            array (
                'id' => 1638,
                'car_make_id' => 81,
                'name' => 'Zonda',
            ),
            138 => 
            array (
                'id' => 1639,
                'car_make_id' => 81,
                'name' => 'Other',
            ),
            139 => 
            array (
                'id' => 1640,
                'car_make_id' => 82,
                'name' => '1007',
            ),
            140 => 
            array (
                'id' => 1641,
                'car_make_id' => 82,
                'name' => '104',
            ),
            141 => 
            array (
                'id' => 1642,
                'car_make_id' => 82,
                'name' => '106',
            ),
            142 => 
            array (
                'id' => 1643,
                'car_make_id' => 82,
                'name' => '107',
            ),
            143 => 
            array (
                'id' => 1644,
                'car_make_id' => 82,
                'name' => '108',
            ),
            144 => 
            array (
                'id' => 1645,
                'car_make_id' => 82,
                'name' => '2008',
            ),
            145 => 
            array (
                'id' => 1646,
                'car_make_id' => 82,
                'name' => '204',
            ),
            146 => 
            array (
                'id' => 1647,
                'car_make_id' => 82,
                'name' => '205',
            ),
            147 => 
            array (
                'id' => 1648,
                'car_make_id' => 82,
                'name' => '206',
            ),
            148 => 
            array (
                'id' => 1649,
                'car_make_id' => 82,
                'name' => '207',
            ),
            149 => 
            array (
                'id' => 1650,
                'car_make_id' => 82,
                'name' => '208',
            ),
            150 => 
            array (
                'id' => 1651,
                'car_make_id' => 82,
                'name' => '3008',
            ),
            151 => 
            array (
                'id' => 1652,
                'car_make_id' => 82,
                'name' => '301',
            ),
            152 => 
            array (
                'id' => 1653,
                'car_make_id' => 82,
                'name' => '304',
            ),
            153 => 
            array (
                'id' => 1654,
                'car_make_id' => 82,
                'name' => '305',
            ),
            154 => 
            array (
                'id' => 1655,
                'car_make_id' => 82,
                'name' => '306',
            ),
            155 => 
            array (
                'id' => 1656,
                'car_make_id' => 82,
                'name' => '307',
            ),
            156 => 
            array (
                'id' => 1657,
                'car_make_id' => 82,
                'name' => '308',
            ),
            157 => 
            array (
                'id' => 1658,
                'car_make_id' => 82,
                'name' => '309',
            ),
            158 => 
            array (
                'id' => 1659,
                'car_make_id' => 82,
                'name' => '4007',
            ),
            159 => 
            array (
                'id' => 1660,
                'car_make_id' => 82,
                'name' => '4008',
            ),
            160 => 
            array (
                'id' => 1661,
                'car_make_id' => 82,
                'name' => '404',
            ),
            161 => 
            array (
                'id' => 1662,
                'car_make_id' => 82,
                'name' => '405',
            ),
            162 => 
            array (
                'id' => 1663,
                'car_make_id' => 82,
                'name' => '406',
            ),
            163 => 
            array (
                'id' => 1664,
                'car_make_id' => 82,
                'name' => '407',
            ),
            164 => 
            array (
                'id' => 1665,
                'car_make_id' => 82,
                'name' => '5008',
            ),
            165 => 
            array (
                'id' => 1666,
                'car_make_id' => 82,
                'name' => '504',
            ),
            166 => 
            array (
                'id' => 1667,
                'car_make_id' => 82,
                'name' => '505',
            ),
            167 => 
            array (
                'id' => 1668,
                'car_make_id' => 82,
                'name' => '508',
            ),
            168 => 
            array (
                'id' => 1669,
                'car_make_id' => 82,
                'name' => '604',
            ),
            169 => 
            array (
                'id' => 1670,
                'car_make_id' => 82,
                'name' => '605',
            ),
            170 => 
            array (
                'id' => 1671,
                'car_make_id' => 82,
                'name' => '607',
            ),
            171 => 
            array (
                'id' => 1672,
                'car_make_id' => 82,
                'name' => '806',
            ),
            172 => 
            array (
                'id' => 1673,
                'car_make_id' => 82,
                'name' => '807',
            ),
            173 => 
            array (
                'id' => 1674,
                'car_make_id' => 82,
                'name' => 'Bipper',
            ),
            174 => 
            array (
                'id' => 1675,
                'car_make_id' => 82,
                'name' => 'Bipper Tepee',
            ),
            175 => 
            array (
                'id' => 1676,
                'car_make_id' => 82,
                'name' => 'Boxer',
            ),
            176 => 
            array (
                'id' => 1677,
                'car_make_id' => 82,
                'name' => 'Expert',
            ),
            177 => 
            array (
                'id' => 1678,
                'car_make_id' => 82,
                'name' => 'Expert Tepee',
            ),
            178 => 
            array (
                'id' => 1679,
                'car_make_id' => 82,
                'name' => 'iOn',
            ),
            179 => 
            array (
                'id' => 1680,
                'car_make_id' => 82,
                'name' => 'J5',
            ),
            180 => 
            array (
                'id' => 1681,
                'car_make_id' => 82,
                'name' => 'Partner',
            ),
            181 => 
            array (
                'id' => 1682,
                'car_make_id' => 82,
                'name' => 'Partner Tepee',
            ),
            182 => 
            array (
                'id' => 1683,
                'car_make_id' => 82,
                'name' => 'RCZ',
            ),
            183 => 
            array (
                'id' => 1684,
                'car_make_id' => 82,
                'name' => 'Rifter',
            ),
            184 => 
            array (
                'id' => 1685,
                'car_make_id' => 82,
                'name' => 'TePee',
            ),
            185 => 
            array (
                'id' => 1686,
                'car_make_id' => 82,
                'name' => 'Traveller',
            ),
            186 => 
            array (
                'id' => 1687,
                'car_make_id' => 82,
                'name' => 'Other',
            ),
            187 => 
            array (
                'id' => 1688,
                'car_make_id' => 83,
                'name' => 'APE',
            ),
            188 => 
            array (
                'id' => 1689,
                'car_make_id' => 83,
                'name' => 'APE TM',
            ),
            189 => 
            array (
                'id' => 1690,
                'car_make_id' => 83,
                'name' => 'Porter',
            ),
            190 => 
            array (
                'id' => 1691,
                'car_make_id' => 83,
                'name' => 'Other',
            ),
            191 => 
            array (
                'id' => 1692,
                'car_make_id' => 84,
                'name' => 'Prowler',
            ),
            192 => 
            array (
                'id' => 1693,
                'car_make_id' => 84,
                'name' => 'Other',
            ),
            193 => 
            array (
                'id' => 1694,
                'car_make_id' => 85,
                'name' => '1',
            ),
            194 => 
            array (
                'id' => 1695,
                'car_make_id' => 85,
                'name' => 'Other',
            ),
            195 => 
            array (
                'id' => 1696,
                'car_make_id' => 86,
                'name' => '6000',
            ),
            196 => 
            array (
                'id' => 1697,
                'car_make_id' => 86,
                'name' => 'Bonneville',
            ),
            197 => 
            array (
                'id' => 1698,
                'car_make_id' => 86,
                'name' => 'Fiero',
            ),
            198 => 
            array (
                'id' => 1699,
                'car_make_id' => 86,
                'name' => 'Firebird',
            ),
            199 => 
            array (
                'id' => 1700,
                'car_make_id' => 86,
                'name' => 'G6',
            ),
            200 => 
            array (
                'id' => 1701,
                'car_make_id' => 86,
                'name' => 'Grand-Am',
            ),
            201 => 
            array (
                'id' => 1702,
                'car_make_id' => 86,
                'name' => 'Grand-Prix',
            ),
            202 => 
            array (
                'id' => 1703,
                'car_make_id' => 86,
                'name' => 'GTO',
            ),
            203 => 
            array (
                'id' => 1704,
                'car_make_id' => 86,
                'name' => 'Montana',
            ),
            204 => 
            array (
                'id' => 1705,
                'car_make_id' => 86,
                'name' => 'Solstice',
            ),
            205 => 
            array (
                'id' => 1706,
                'car_make_id' => 86,
                'name' => 'Sunbird',
            ),
            206 => 
            array (
                'id' => 1707,
                'car_make_id' => 86,
                'name' => 'Sunfire',
            ),
            207 => 
            array (
                'id' => 1708,
                'car_make_id' => 86,
                'name' => 'Targa',
            ),
            208 => 
            array (
                'id' => 1709,
                'car_make_id' => 86,
                'name' => 'Trans Am',
            ),
            209 => 
            array (
                'id' => 1710,
                'car_make_id' => 86,
                'name' => 'Trans Sport',
            ),
            210 => 
            array (
                'id' => 1711,
                'car_make_id' => 86,
                'name' => 'Vibe',
            ),
            211 => 
            array (
                'id' => 1712,
                'car_make_id' => 86,
                'name' => 'Other',
            ),
            212 => 
            array (
                'id' => 1713,
                'car_make_id' => 87,
                'name' => '356',
            ),
            213 => 
            array (
                'id' => 1714,
                'car_make_id' => 87,
                'name' => '912',
            ),
            214 => 
            array (
                'id' => 1715,
                'car_make_id' => 87,
                'name' => '914',
            ),
            215 => 
            array (
                'id' => 1716,
                'car_make_id' => 87,
                'name' => '918',
            ),
            216 => 
            array (
                'id' => 1717,
                'car_make_id' => 87,
                'name' => '924',
            ),
            217 => 
            array (
                'id' => 1718,
                'car_make_id' => 87,
                'name' => '928',
            ),
            218 => 
            array (
                'id' => 1719,
                'car_make_id' => 87,
                'name' => '944',
            ),
            219 => 
            array (
                'id' => 1720,
                'car_make_id' => 87,
                'name' => '959',
            ),
            220 => 
            array (
                'id' => 1721,
                'car_make_id' => 87,
                'name' => '962',
            ),
            221 => 
            array (
                'id' => 1722,
                'car_make_id' => 87,
                'name' => '968',
            ),
            222 => 
            array (
                'id' => 1723,
                'car_make_id' => 87,
                'name' => 'Boxster',
            ),
            223 => 
            array (
                'id' => 1724,
                'car_make_id' => 87,
                'name' => 'Carrera GT',
            ),
            224 => 
            array (
                'id' => 1725,
                'car_make_id' => 87,
                'name' => 'Cayenne',
            ),
            225 => 
            array (
                'id' => 1726,
                'car_make_id' => 87,
                'name' => 'Cayman',
            ),
            226 => 
            array (
                'id' => 1727,
                'car_make_id' => 87,
                'name' => 'Macan',
            ),
            227 => 
            array (
                'id' => 1728,
                'car_make_id' => 87,
                'name' => 'Panamera',
            ),
            228 => 
            array (
                'id' => 1729,
                'car_make_id' => 87,
                'name' => '911',
            ),
            229 => 
            array (
                'id' => 1730,
                'car_make_id' => 87,
                'name' => '930',
            ),
            230 => 
            array (
                'id' => 1731,
                'car_make_id' => 87,
                'name' => '964',
            ),
            231 => 
            array (
                'id' => 1732,
                'car_make_id' => 87,
                'name' => '991',
            ),
            232 => 
            array (
                'id' => 1733,
                'car_make_id' => 87,
                'name' => '992',
            ),
            233 => 
            array (
                'id' => 1734,
                'car_make_id' => 87,
                'name' => '993',
            ),
            234 => 
            array (
                'id' => 1735,
                'car_make_id' => 87,
                'name' => '996',
            ),
            235 => 
            array (
                'id' => 1736,
                'car_make_id' => 87,
                'name' => '997',
            ),
            236 => 
            array (
                'id' => 1737,
                'car_make_id' => 87,
                'name' => 'Taycan',
            ),
            237 => 
            array (
                'id' => 1738,
                'car_make_id' => 87,
                'name' => 'Other',
            ),
            238 => 
            array (
                'id' => 1739,
                'car_make_id' => 88,
                'name' => '300 Serie',
            ),
            239 => 
            array (
                'id' => 1740,
                'car_make_id' => 88,
                'name' => '400 Serie',
            ),
            240 => 
            array (
                'id' => 1741,
                'car_make_id' => 88,
                'name' => 'Other',
            ),
            241 => 
            array (
                'id' => 1742,
                'car_make_id' => 89,
                'name' => 'Alaskan',
            ),
            242 => 
            array (
                'id' => 1743,
                'car_make_id' => 89,
                'name' => 'Alpine A110',
            ),
            243 => 
            array (
                'id' => 1744,
                'car_make_id' => 89,
                'name' => 'Alpine A310',
            ),
            244 => 
            array (
                'id' => 1745,
                'car_make_id' => 89,
                'name' => 'Alpine V6',
            ),
            245 => 
            array (
                'id' => 1746,
                'car_make_id' => 89,
                'name' => 'Avantime',
            ),
            246 => 
            array (
                'id' => 1747,
                'car_make_id' => 89,
                'name' => 'Captur',
            ),
            247 => 
            array (
                'id' => 1748,
                'car_make_id' => 89,
                'name' => 'Clio',
            ),
            248 => 
            array (
                'id' => 1749,
                'car_make_id' => 89,
                'name' => 'Coupe',
            ),
            249 => 
            array (
                'id' => 1750,
                'car_make_id' => 89,
                'name' => 'Espace',
            ),
            250 => 
            array (
                'id' => 1751,
                'car_make_id' => 89,
                'name' => 'Express',
            ),
            251 => 
            array (
                'id' => 1752,
                'car_make_id' => 89,
                'name' => 'Fluence',
            ),
            252 => 
            array (
                'id' => 1753,
                'car_make_id' => 89,
                'name' => 'Fuego',
            ),
            253 => 
            array (
                'id' => 1754,
                'car_make_id' => 89,
                'name' => 'Grand Espace',
            ),
            254 => 
            array (
                'id' => 1755,
                'car_make_id' => 89,
                'name' => 'Grand Modus',
            ),
            255 => 
            array (
                'id' => 1756,
                'car_make_id' => 89,
                'name' => 'Grand Scenic',
            ),
            256 => 
            array (
                'id' => 1757,
                'car_make_id' => 89,
                'name' => 'Kadjar',
            ),
            257 => 
            array (
                'id' => 1758,
                'car_make_id' => 89,
                'name' => 'Kangoo',
            ),
            258 => 
            array (
                'id' => 1759,
                'car_make_id' => 89,
                'name' => 'Koleos',
            ),
            259 => 
            array (
                'id' => 1760,
                'car_make_id' => 89,
                'name' => 'Laguna',
            ),
            260 => 
            array (
                'id' => 1761,
                'car_make_id' => 89,
                'name' => 'Latitude',
            ),
            261 => 
            array (
                'id' => 1762,
                'car_make_id' => 89,
                'name' => 'Mascott',
            ),
            262 => 
            array (
                'id' => 1763,
                'car_make_id' => 89,
                'name' => 'Master',
            ),
            263 => 
            array (
                'id' => 1764,
                'car_make_id' => 89,
                'name' => 'Megane',
            ),
            264 => 
            array (
                'id' => 1765,
                'car_make_id' => 89,
                'name' => 'Modus',
            ),
            265 => 
            array (
                'id' => 1766,
                'car_make_id' => 89,
                'name' => 'P 1400',
            ),
            266 => 
            array (
                'id' => 1767,
                'car_make_id' => 89,
                'name' => 'R 11',
            ),
            267 => 
            array (
                'id' => 1768,
                'car_make_id' => 89,
                'name' => 'R 14',
            ),
            268 => 
            array (
                'id' => 1769,
                'car_make_id' => 89,
                'name' => 'R 18',
            ),
            269 => 
            array (
                'id' => 1770,
                'car_make_id' => 89,
                'name' => 'R 19',
            ),
            270 => 
            array (
                'id' => 1771,
                'car_make_id' => 89,
                'name' => 'R 20',
            ),
            271 => 
            array (
                'id' => 1772,
                'car_make_id' => 89,
                'name' => 'R 21',
            ),
            272 => 
            array (
                'id' => 1773,
                'car_make_id' => 89,
                'name' => 'R 25',
            ),
            273 => 
            array (
                'id' => 1774,
                'car_make_id' => 89,
                'name' => 'R 30',
            ),
            274 => 
            array (
                'id' => 1775,
                'car_make_id' => 89,
                'name' => 'R 4',
            ),
            275 => 
            array (
                'id' => 1776,
                'car_make_id' => 89,
                'name' => 'R 5',
            ),
            276 => 
            array (
                'id' => 1777,
                'car_make_id' => 89,
                'name' => 'R 6',
            ),
            277 => 
            array (
                'id' => 1778,
                'car_make_id' => 89,
                'name' => 'R 9',
            ),
            278 => 
            array (
                'id' => 1779,
                'car_make_id' => 89,
                'name' => 'Rapid',
            ),
            279 => 
            array (
                'id' => 1780,
                'car_make_id' => 89,
                'name' => 'Safrane',
            ),
            280 => 
            array (
                'id' => 1781,
                'car_make_id' => 89,
                'name' => 'Scenic',
            ),
            281 => 
            array (
                'id' => 1782,
                'car_make_id' => 89,
                'name' => 'Spider',
            ),
            282 => 
            array (
                'id' => 1783,
                'car_make_id' => 89,
                'name' => 'Talisman',
            ),
            283 => 
            array (
                'id' => 1784,
                'car_make_id' => 89,
                'name' => 'Trafic',
            ),
            284 => 
            array (
                'id' => 1785,
                'car_make_id' => 89,
                'name' => 'Twingo',
            ),
            285 => 
            array (
                'id' => 1786,
                'car_make_id' => 89,
                'name' => 'Twizy',
            ),
            286 => 
            array (
                'id' => 1787,
                'car_make_id' => 89,
                'name' => 'Vel Satis',
            ),
            287 => 
            array (
                'id' => 1788,
                'car_make_id' => 89,
                'name' => 'Wind',
            ),
            288 => 
            array (
                'id' => 1789,
                'car_make_id' => 89,
                'name' => 'ZOE',
            ),
            289 => 
            array (
                'id' => 1790,
                'car_make_id' => 89,
                'name' => 'Other',
            ),
            290 => 
            array (
                'id' => 1791,
                'car_make_id' => 90,
                'name' => 'Corniche',
            ),
            291 => 
            array (
                'id' => 1792,
                'car_make_id' => 90,
                'name' => 'Cullinan',
            ),
            292 => 
            array (
                'id' => 1793,
                'car_make_id' => 90,
                'name' => 'Dawn',
            ),
            293 => 
            array (
                'id' => 1794,
                'car_make_id' => 90,
                'name' => 'Flying Spur',
            ),
            294 => 
            array (
                'id' => 1795,
                'car_make_id' => 90,
                'name' => 'Ghost',
            ),
            295 => 
            array (
                'id' => 1796,
                'car_make_id' => 90,
                'name' => 'Park Ward',
            ),
            296 => 
            array (
                'id' => 1797,
                'car_make_id' => 90,
                'name' => 'Phantom',
            ),
            297 => 
            array (
                'id' => 1798,
                'car_make_id' => 90,
                'name' => 'Silver Cloud',
            ),
            298 => 
            array (
                'id' => 1799,
                'car_make_id' => 90,
                'name' => 'Silver Dawn',
            ),
            299 => 
            array (
                'id' => 1800,
                'car_make_id' => 90,
                'name' => 'Silver Seraph',
            ),
            300 => 
            array (
                'id' => 1801,
                'car_make_id' => 90,
                'name' => 'Silver Shadow',
            ),
            301 => 
            array (
                'id' => 1802,
                'car_make_id' => 90,
                'name' => 'Silver Spirit',
            ),
            302 => 
            array (
                'id' => 1803,
                'car_make_id' => 90,
                'name' => 'Silver Spur',
            ),
            303 => 
            array (
                'id' => 1804,
                'car_make_id' => 90,
                'name' => 'Wraith',
            ),
            304 => 
            array (
                'id' => 1805,
                'car_make_id' => 90,
                'name' => 'Other',
            ),
            305 => 
            array (
                'id' => 1806,
                'car_make_id' => 91,
                'name' => '100',
            ),
            306 => 
            array (
                'id' => 1807,
                'car_make_id' => 91,
                'name' => '111',
            ),
            307 => 
            array (
                'id' => 1808,
                'car_make_id' => 91,
                'name' => '114',
            ),
            308 => 
            array (
                'id' => 1809,
                'car_make_id' => 91,
                'name' => '115',
            ),
            309 => 
            array (
                'id' => 1810,
                'car_make_id' => 91,
                'name' => '200',
            ),
            310 => 
            array (
                'id' => 1811,
                'car_make_id' => 91,
                'name' => '213',
            ),
            311 => 
            array (
                'id' => 1812,
                'car_make_id' => 91,
                'name' => '214',
            ),
            312 => 
            array (
                'id' => 1813,
                'car_make_id' => 91,
                'name' => '216',
            ),
            313 => 
            array (
                'id' => 1814,
                'car_make_id' => 91,
                'name' => '218',
            ),
            314 => 
            array (
                'id' => 1815,
                'car_make_id' => 91,
                'name' => '220',
            ),
            315 => 
            array (
                'id' => 1816,
                'car_make_id' => 91,
                'name' => '25',
            ),
            316 => 
            array (
                'id' => 1817,
                'car_make_id' => 91,
                'name' => '400',
            ),
            317 => 
            array (
                'id' => 1818,
                'car_make_id' => 91,
                'name' => '414',
            ),
            318 => 
            array (
                'id' => 1819,
                'car_make_id' => 91,
                'name' => '416',
            ),
            319 => 
            array (
                'id' => 1820,
                'car_make_id' => 91,
                'name' => '418',
            ),
            320 => 
            array (
                'id' => 1821,
                'car_make_id' => 91,
                'name' => '420',
            ),
            321 => 
            array (
                'id' => 1822,
                'car_make_id' => 91,
                'name' => '45',
            ),
            322 => 
            array (
                'id' => 1823,
                'car_make_id' => 91,
                'name' => '600',
            ),
            323 => 
            array (
                'id' => 1824,
                'car_make_id' => 91,
                'name' => '618',
            ),
            324 => 
            array (
                'id' => 1825,
                'car_make_id' => 91,
                'name' => '620',
            ),
            325 => 
            array (
                'id' => 1826,
                'car_make_id' => 91,
                'name' => '623',
            ),
            326 => 
            array (
                'id' => 1827,
                'car_make_id' => 91,
                'name' => '75',
            ),
            327 => 
            array (
                'id' => 1828,
                'car_make_id' => 91,
                'name' => '800',
            ),
            328 => 
            array (
                'id' => 1829,
                'car_make_id' => 91,
                'name' => '820',
            ),
            329 => 
            array (
                'id' => 1830,
                'car_make_id' => 91,
                'name' => '825',
            ),
            330 => 
            array (
                'id' => 1831,
                'car_make_id' => 91,
                'name' => '827',
            ),
            331 => 
            array (
                'id' => 1832,
                'car_make_id' => 91,
                'name' => 'City Rover',
            ),
            332 => 
            array (
                'id' => 1833,
                'car_make_id' => 91,
                'name' => 'Metro',
            ),
            333 => 
            array (
                'id' => 1834,
                'car_make_id' => 91,
                'name' => 'Montego',
            ),
            334 => 
            array (
                'id' => 1835,
                'car_make_id' => 91,
                'name' => 'SD',
            ),
            335 => 
            array (
                'id' => 1836,
                'car_make_id' => 91,
                'name' => 'Streetwise',
            ),
            336 => 
            array (
                'id' => 1837,
                'car_make_id' => 91,
                'name' => 'Other',
            ),
            337 => 
            array (
                'id' => 1838,
                'car_make_id' => 92,
                'name' => 'Other',
            ),
            338 => 
            array (
                'id' => 1839,
                'car_make_id' => 93,
                'name' => '90',
            ),
            339 => 
            array (
                'id' => 1840,
                'car_make_id' => 93,
                'name' => '900',
            ),
            340 => 
            array (
                'id' => 1841,
                'car_make_id' => 93,
                'name' => '9000',
            ),
            341 => 
            array (
                'id' => 1842,
                'car_make_id' => 93,
                'name' => '9-3',
            ),
            342 => 
            array (
                'id' => 1843,
                'car_make_id' => 93,
                'name' => '9-4X',
            ),
            343 => 
            array (
                'id' => 1844,
                'car_make_id' => 93,
                'name' => '9-5',
            ),
            344 => 
            array (
                'id' => 1845,
                'car_make_id' => 93,
                'name' => '96',
            ),
            345 => 
            array (
                'id' => 1846,
                'car_make_id' => 93,
                'name' => '9-7X',
            ),
            346 => 
            array (
                'id' => 1847,
                'car_make_id' => 93,
                'name' => '99',
            ),
            347 => 
            array (
                'id' => 1848,
                'car_make_id' => 93,
                'name' => 'Other',
            ),
            348 => 
            array (
                'id' => 1849,
                'car_make_id' => 94,
                'name' => 'Other',
            ),
            349 => 
            array (
                'id' => 1850,
                'car_make_id' => 95,
                'name' => 'Alhambra',
            ),
            350 => 
            array (
                'id' => 1851,
                'car_make_id' => 95,
                'name' => 'Altea',
            ),
            351 => 
            array (
                'id' => 1852,
                'car_make_id' => 95,
                'name' => 'Arona',
            ),
            352 => 
            array (
                'id' => 1853,
                'car_make_id' => 95,
                'name' => 'Arosa',
            ),
            353 => 
            array (
                'id' => 1854,
                'car_make_id' => 95,
                'name' => 'Ateca',
            ),
            354 => 
            array (
                'id' => 1855,
                'car_make_id' => 95,
                'name' => 'Cordoba',
            ),
            355 => 
            array (
                'id' => 1856,
                'car_make_id' => 95,
                'name' => 'Exeo',
            ),
            356 => 
            array (
                'id' => 1857,
                'car_make_id' => 95,
                'name' => 'Ibiza',
            ),
            357 => 
            array (
                'id' => 1858,
                'car_make_id' => 95,
                'name' => 'Inca',
            ),
            358 => 
            array (
                'id' => 1859,
                'car_make_id' => 95,
                'name' => 'Leon',
            ),
            359 => 
            array (
                'id' => 1860,
                'car_make_id' => 95,
                'name' => 'Malaga',
            ),
            360 => 
            array (
                'id' => 1861,
                'car_make_id' => 95,
                'name' => 'Marbella',
            ),
            361 => 
            array (
                'id' => 1862,
                'car_make_id' => 95,
                'name' => 'Mii',
            ),
            362 => 
            array (
                'id' => 1863,
                'car_make_id' => 95,
                'name' => 'Tarraco',
            ),
            363 => 
            array (
                'id' => 1864,
                'car_make_id' => 95,
                'name' => 'Terra',
            ),
            364 => 
            array (
                'id' => 1865,
                'car_make_id' => 95,
                'name' => 'Toledo',
            ),
            365 => 
            array (
                'id' => 1866,
                'car_make_id' => 95,
                'name' => 'Other',
            ),
            366 => 
            array (
                'id' => 1867,
                'car_make_id' => 96,
                'name' => '105',
            ),
            367 => 
            array (
                'id' => 1868,
                'car_make_id' => 96,
                'name' => '120',
            ),
            368 => 
            array (
                'id' => 1869,
                'car_make_id' => 96,
                'name' => '130',
            ),
            369 => 
            array (
                'id' => 1870,
                'car_make_id' => 96,
                'name' => '135',
            ),
            370 => 
            array (
                'id' => 1871,
                'car_make_id' => 96,
                'name' => 'Citigo',
            ),
            371 => 
            array (
                'id' => 1872,
                'car_make_id' => 96,
                'name' => 'Fabia',
            ),
            372 => 
            array (
                'id' => 1873,
                'car_make_id' => 96,
                'name' => 'Favorit',
            ),
            373 => 
            array (
                'id' => 1874,
                'car_make_id' => 96,
                'name' => 'Felicia',
            ),
            374 => 
            array (
                'id' => 1875,
                'car_make_id' => 96,
                'name' => 'Forman',
            ),
            375 => 
            array (
                'id' => 1876,
                'car_make_id' => 96,
                'name' => 'Kamiq',
            ),
            376 => 
            array (
                'id' => 1877,
                'car_make_id' => 96,
                'name' => 'Karoq',
            ),
            377 => 
            array (
                'id' => 1878,
                'car_make_id' => 96,
                'name' => 'Kodiaq',
            ),
            378 => 
            array (
                'id' => 1879,
                'car_make_id' => 96,
                'name' => 'Octavia',
            ),
            379 => 
            array (
                'id' => 1880,
                'car_make_id' => 96,
                'name' => 'Pick-up',
            ),
            380 => 
            array (
                'id' => 1881,
                'car_make_id' => 96,
                'name' => 'Praktik',
            ),
            381 => 
            array (
                'id' => 1882,
                'car_make_id' => 96,
                'name' => 'Rapid',
            ),
            382 => 
            array (
                'id' => 1883,
                'car_make_id' => 96,
                'name' => 'Roomster',
            ),
            383 => 
            array (
                'id' => 1884,
                'car_make_id' => 96,
                'name' => 'Scala',
            ),
            384 => 
            array (
                'id' => 1885,
                'car_make_id' => 96,
                'name' => 'Superb',
            ),
            385 => 
            array (
                'id' => 1886,
                'car_make_id' => 96,
                'name' => 'Yeti',
            ),
            386 => 
            array (
                'id' => 1887,
                'car_make_id' => 96,
                'name' => 'Other',
            ),
            387 => 
            array (
                'id' => 1888,
                'car_make_id' => 97,
                'name' => 'Crossblade',
            ),
            388 => 
            array (
                'id' => 1889,
                'car_make_id' => 97,
                'name' => 'ForFour',
            ),
            389 => 
            array (
                'id' => 1890,
                'car_make_id' => 97,
                'name' => 'ForTwo',
            ),
            390 => 
            array (
                'id' => 1891,
                'car_make_id' => 97,
                'name' => 'Roadster',
            ),
            391 => 
            array (
                'id' => 1892,
                'car_make_id' => 97,
                'name' => 'Other',
            ),
            392 => 
            array (
                'id' => 1893,
                'car_make_id' => 98,
                'name' => 'Other',
            ),
            393 => 
            array (
                'id' => 1894,
                'car_make_id' => 99,
                'name' => 'C8',
            ),
            394 => 
            array (
                'id' => 1895,
                'car_make_id' => 99,
                'name' => 'C8 AILERON',
            ),
            395 => 
            array (
                'id' => 1896,
                'car_make_id' => 99,
                'name' => 'C8 DOUBLE 12 S',
            ),
            396 => 
            array (
                'id' => 1897,
                'car_make_id' => 99,
                'name' => 'C8 LAVIOLETTE SWB',
            ),
            397 => 
            array (
                'id' => 1898,
                'car_make_id' => 99,
                'name' => 'C8 SPYDER SWB',
            ),
            398 => 
            array (
                'id' => 1899,
                'car_make_id' => 99,
                'name' => 'Other',
            ),
            399 => 
            array (
                'id' => 1900,
                'car_make_id' => 100,
                'name' => 'Actyon',
            ),
            400 => 
            array (
                'id' => 1901,
                'car_make_id' => 100,
                'name' => 'Family',
            ),
            401 => 
            array (
                'id' => 1902,
                'car_make_id' => 100,
                'name' => 'Korando',
            ),
            402 => 
            array (
                'id' => 1903,
                'car_make_id' => 100,
                'name' => 'Kyron',
            ),
            403 => 
            array (
                'id' => 1904,
                'car_make_id' => 100,
                'name' => 'MUSSO',
            ),
            404 => 
            array (
                'id' => 1905,
                'car_make_id' => 100,
                'name' => 'REXTON',
            ),
            405 => 
            array (
                'id' => 1906,
                'car_make_id' => 100,
                'name' => 'Rodius',
            ),
            406 => 
            array (
                'id' => 1907,
                'car_make_id' => 100,
                'name' => 'Tivoli',
            ),
            407 => 
            array (
                'id' => 1908,
                'car_make_id' => 100,
                'name' => 'XLV',
            ),
            408 => 
            array (
                'id' => 1909,
                'car_make_id' => 100,
                'name' => 'Other',
            ),
            409 => 
            array (
                'id' => 1910,
                'car_make_id' => 101,
                'name' => 'B9 Tribeca',
            ),
            410 => 
            array (
                'id' => 1911,
                'car_make_id' => 101,
                'name' => 'Baja',
            ),
            411 => 
            array (
                'id' => 1912,
                'car_make_id' => 101,
                'name' => 'BRZ',
            ),
            412 => 
            array (
                'id' => 1913,
                'car_make_id' => 101,
                'name' => 'Forester',
            ),
            413 => 
            array (
                'id' => 1914,
                'car_make_id' => 101,
                'name' => 'Impreza',
            ),
            414 => 
            array (
                'id' => 1915,
                'car_make_id' => 101,
                'name' => 'Justy',
            ),
            415 => 
            array (
                'id' => 1916,
                'car_make_id' => 101,
                'name' => 'Legacy',
            ),
            416 => 
            array (
                'id' => 1917,
                'car_make_id' => 101,
                'name' => 'Levorg',
            ),
            417 => 
            array (
                'id' => 1918,
                'car_make_id' => 101,
                'name' => 'Libero',
            ),
            418 => 
            array (
                'id' => 1919,
                'car_make_id' => 101,
                'name' => 'Outback',
            ),
            419 => 
            array (
                'id' => 1920,
                'car_make_id' => 101,
                'name' => 'SVX',
            ),
            420 => 
            array (
                'id' => 1921,
                'car_make_id' => 101,
                'name' => 'Trezia',
            ),
            421 => 
            array (
                'id' => 1922,
                'car_make_id' => 101,
                'name' => 'Tribeca',
            ),
            422 => 
            array (
                'id' => 1923,
                'car_make_id' => 101,
                'name' => 'Vivio',
            ),
            423 => 
            array (
                'id' => 1924,
                'car_make_id' => 101,
                'name' => 'WRX STI',
            ),
            424 => 
            array (
                'id' => 1925,
                'car_make_id' => 101,
                'name' => 'XT',
            ),
            425 => 
            array (
                'id' => 1926,
                'car_make_id' => 101,
                'name' => 'XV',
            ),
            426 => 
            array (
                'id' => 1927,
                'car_make_id' => 101,
                'name' => 'Other',
            ),
            427 => 
            array (
                'id' => 1928,
                'car_make_id' => 102,
                'name' => 'Alto',
            ),
            428 => 
            array (
                'id' => 1929,
                'car_make_id' => 102,
                'name' => 'Baleno',
            ),
            429 => 
            array (
                'id' => 1930,
                'car_make_id' => 102,
                'name' => 'Cappuccino',
            ),
            430 => 
            array (
                'id' => 1931,
                'car_make_id' => 102,
                'name' => 'Carry',
            ),
            431 => 
            array (
                'id' => 1932,
                'car_make_id' => 102,
                'name' => 'Celerio',
            ),
            432 => 
            array (
                'id' => 1933,
                'car_make_id' => 102,
                'name' => 'Grand Vitara',
            ),
            433 => 
            array (
                'id' => 1934,
                'car_make_id' => 102,
                'name' => 'Ignis',
            ),
            434 => 
            array (
                'id' => 1935,
                'car_make_id' => 102,
                'name' => 'iK-2',
            ),
            435 => 
            array (
                'id' => 1936,
                'car_make_id' => 102,
                'name' => 'Jimny',
            ),
            436 => 
            array (
                'id' => 1937,
                'car_make_id' => 102,
                'name' => 'Kizashi',
            ),
            437 => 
            array (
                'id' => 1938,
                'car_make_id' => 102,
                'name' => 'Liana',
            ),
            438 => 
            array (
                'id' => 1939,
                'car_make_id' => 102,
                'name' => 'LJ',
            ),
            439 => 
            array (
                'id' => 1940,
                'car_make_id' => 102,
                'name' => 'SJ Samurai',
            ),
            440 => 
            array (
                'id' => 1941,
                'car_make_id' => 102,
                'name' => 'Splash',
            ),
            441 => 
            array (
                'id' => 1942,
                'car_make_id' => 102,
                'name' => 'Super-Carry',
            ),
            442 => 
            array (
                'id' => 1943,
                'car_make_id' => 102,
                'name' => 'Swift',
            ),
            443 => 
            array (
                'id' => 1944,
                'car_make_id' => 102,
                'name' => 'SX4',
            ),
            444 => 
            array (
                'id' => 1945,
                'car_make_id' => 102,
                'name' => 'SX4 S-Cross',
            ),
            445 => 
            array (
                'id' => 1946,
                'car_make_id' => 102,
                'name' => 'Vitara',
            ),
            446 => 
            array (
                'id' => 1947,
                'car_make_id' => 102,
                'name' => 'Wagon R+',
            ),
            447 => 
            array (
                'id' => 1948,
                'car_make_id' => 102,
                'name' => 'X-90',
            ),
            448 => 
            array (
                'id' => 1949,
                'car_make_id' => 102,
                'name' => 'Other',
            ),
            449 => 
            array (
                'id' => 1950,
                'car_make_id' => 103,
                'name' => 'Horizon',
            ),
            450 => 
            array (
                'id' => 1951,
                'car_make_id' => 103,
                'name' => 'Samba',
            ),
            451 => 
            array (
                'id' => 1952,
                'car_make_id' => 103,
                'name' => 'Other',
            ),
            452 => 
            array (
                'id' => 1953,
                'car_make_id' => 104,
                'name' => 'Indica',
            ),
            453 => 
            array (
                'id' => 1954,
                'car_make_id' => 104,
                'name' => 'Indigo',
            ),
            454 => 
            array (
                'id' => 1955,
                'car_make_id' => 104,
                'name' => 'Nano',
            ),
            455 => 
            array (
                'id' => 1956,
                'car_make_id' => 104,
                'name' => 'Safari',
            ),
            456 => 
            array (
                'id' => 1957,
                'car_make_id' => 104,
                'name' => 'Sumo',
            ),
            457 => 
            array (
                'id' => 1958,
                'car_make_id' => 104,
                'name' => 'Telcoline',
            ),
            458 => 
            array (
                'id' => 1959,
                'car_make_id' => 104,
                'name' => 'Telcosport',
            ),
            459 => 
            array (
                'id' => 1960,
                'car_make_id' => 104,
                'name' => 'Xenon',
            ),
            460 => 
            array (
                'id' => 1961,
                'car_make_id' => 104,
                'name' => 'Other',
            ),
            461 => 
            array (
                'id' => 1962,
                'car_make_id' => 105,
                'name' => 'Other',
            ),
            462 => 
            array (
                'id' => 1963,
                'car_make_id' => 106,
                'name' => 'Model 3',
            ),
            463 => 
            array (
                'id' => 1964,
                'car_make_id' => 106,
                'name' => 'Model S',
            ),
            464 => 
            array (
                'id' => 1965,
                'car_make_id' => 106,
                'name' => 'Model X',
            ),
            465 => 
            array (
                'id' => 1966,
                'car_make_id' => 106,
                'name' => 'Roadster',
            ),
            466 => 
            array (
                'id' => 1967,
                'car_make_id' => 106,
                'name' => 'Other',
            ),
            467 => 
            array (
                'id' => 1968,
                'car_make_id' => 107,
                'name' => '4-Runner',
            ),
            468 => 
            array (
                'id' => 1969,
                'car_make_id' => 107,
                'name' => 'Alphard',
            ),
            469 => 
            array (
                'id' => 1970,
                'car_make_id' => 107,
                'name' => 'Auris',
            ),
            470 => 
            array (
                'id' => 1971,
                'car_make_id' => 107,
                'name' => 'Auris Touring Sports',
            ),
            471 => 
            array (
                'id' => 1972,
                'car_make_id' => 107,
                'name' => 'Avalon',
            ),
            472 => 
            array (
                'id' => 1973,
                'car_make_id' => 107,
                'name' => 'Avensis',
            ),
            473 => 
            array (
                'id' => 1974,
                'car_make_id' => 107,
                'name' => 'Avensis Verso',
            ),
            474 => 
            array (
                'id' => 1975,
                'car_make_id' => 107,
                'name' => 'Aygo',
            ),
            475 => 
            array (
                'id' => 1976,
                'car_make_id' => 107,
                'name' => 'Camry',
            ),
            476 => 
            array (
                'id' => 1977,
                'car_make_id' => 107,
                'name' => 'Carina',
            ),
            477 => 
            array (
                'id' => 1978,
                'car_make_id' => 107,
                'name' => 'Celica',
            ),
            478 => 
            array (
                'id' => 1979,
                'car_make_id' => 107,
                'name' => 'C-HR',
            ),
            479 => 
            array (
                'id' => 1980,
                'car_make_id' => 107,
                'name' => 'Corolla',
            ),
            480 => 
            array (
                'id' => 1981,
                'car_make_id' => 107,
                'name' => 'Corolla Verso',
            ),
            481 => 
            array (
                'id' => 1982,
                'car_make_id' => 107,
                'name' => 'Cressida',
            ),
            482 => 
            array (
                'id' => 1983,
                'car_make_id' => 107,
                'name' => 'Crown',
            ),
            483 => 
            array (
                'id' => 1984,
                'car_make_id' => 107,
                'name' => 'Dyna',
            ),
            484 => 
            array (
                'id' => 1985,
                'car_make_id' => 107,
                'name' => 'FCV',
            ),
            485 => 
            array (
                'id' => 1986,
                'car_make_id' => 107,
                'name' => 'FJ',
            ),
            486 => 
            array (
                'id' => 1987,
                'car_make_id' => 107,
                'name' => 'Fortuner',
            ),
            487 => 
            array (
                'id' => 1988,
                'car_make_id' => 107,
                'name' => 'GT86',
            ),
            488 => 
            array (
                'id' => 1989,
                'car_make_id' => 107,
                'name' => 'Hiace',
            ),
            489 => 
            array (
                'id' => 1990,
                'car_make_id' => 107,
                'name' => 'Highlander',
            ),
            490 => 
            array (
                'id' => 1991,
                'car_make_id' => 107,
                'name' => 'Hilux',
            ),
            491 => 
            array (
                'id' => 1992,
                'car_make_id' => 107,
                'name' => 'IQ',
            ),
            492 => 
            array (
                'id' => 1993,
                'car_make_id' => 107,
                'name' => 'Land Cruiser',
            ),
            493 => 
            array (
                'id' => 1994,
                'car_make_id' => 107,
                'name' => 'Lite-Ace',
            ),
            494 => 
            array (
                'id' => 1995,
                'car_make_id' => 107,
                'name' => 'Matrix',
            ),
            495 => 
            array (
                'id' => 1996,
                'car_make_id' => 107,
                'name' => 'Mirai',
            ),
            496 => 
            array (
                'id' => 1997,
                'car_make_id' => 107,
                'name' => 'MR 2',
            ),
            497 => 
            array (
                'id' => 1998,
                'car_make_id' => 107,
                'name' => 'Paseo',
            ),
            498 => 
            array (
                'id' => 1999,
                'car_make_id' => 107,
                'name' => 'Picnic',
            ),
            499 => 
            array (
                'id' => 2000,
                'car_make_id' => 107,
                'name' => 'Previa',
            ),
        ));
        \DB::table('car_models')->insert(array (
            0 => 
            array (
                'id' => 2001,
                'car_make_id' => 107,
                'name' => 'Prius',
            ),
            1 => 
            array (
                'id' => 2002,
                'car_make_id' => 107,
                'name' => 'Prius+',
            ),
            2 => 
            array (
                'id' => 2003,
                'car_make_id' => 107,
            'name' => 'Proace (Verso)',
            ),
            3 => 
            array (
                'id' => 2004,
                'car_make_id' => 107,
                'name' => 'RAV 4',
            ),
            4 => 
            array (
                'id' => 2005,
                'car_make_id' => 107,
                'name' => 'Sequoia',
            ),
            5 => 
            array (
                'id' => 2006,
                'car_make_id' => 107,
                'name' => 'Sienna',
            ),
            6 => 
            array (
                'id' => 2007,
                'car_make_id' => 107,
                'name' => 'Starlet',
            ),
            7 => 
            array (
                'id' => 2008,
                'car_make_id' => 107,
                'name' => 'Supra',
            ),
            8 => 
            array (
                'id' => 2009,
                'car_make_id' => 107,
                'name' => 'Tacoma',
            ),
            9 => 
            array (
                'id' => 2010,
                'car_make_id' => 107,
                'name' => 'Tercel',
            ),
            10 => 
            array (
                'id' => 2011,
                'car_make_id' => 107,
                'name' => 'Tundra',
            ),
            11 => 
            array (
                'id' => 2012,
                'car_make_id' => 107,
                'name' => 'Urban Cruiser',
            ),
            12 => 
            array (
                'id' => 2013,
                'car_make_id' => 107,
                'name' => 'Verso',
            ),
            13 => 
            array (
                'id' => 2014,
                'car_make_id' => 107,
                'name' => 'Verso-S',
            ),
            14 => 
            array (
                'id' => 2015,
                'car_make_id' => 107,
                'name' => 'Yaris',
            ),
            15 => 
            array (
                'id' => 2016,
                'car_make_id' => 107,
                'name' => 'Other',
            ),
            16 => 
            array (
                'id' => 2017,
                'car_make_id' => 108,
                'name' => '601',
            ),
            17 => 
            array (
                'id' => 2018,
                'car_make_id' => 108,
                'name' => 'Other',
            ),
            18 => 
            array (
                'id' => 2019,
                'car_make_id' => 109,
                'name' => 'Dolomite',
            ),
            19 => 
            array (
                'id' => 2020,
                'car_make_id' => 109,
                'name' => 'Moss',
            ),
            20 => 
            array (
                'id' => 2021,
                'car_make_id' => 109,
                'name' => 'Spitfire',
            ),
            21 => 
            array (
                'id' => 2022,
                'car_make_id' => 109,
                'name' => 'TR3',
            ),
            22 => 
            array (
                'id' => 2023,
                'car_make_id' => 109,
                'name' => 'TR4',
            ),
            23 => 
            array (
                'id' => 2024,
                'car_make_id' => 109,
                'name' => 'TR5',
            ),
            24 => 
            array (
                'id' => 2025,
                'car_make_id' => 109,
                'name' => 'TR6',
            ),
            25 => 
            array (
                'id' => 2026,
                'car_make_id' => 109,
                'name' => 'TR7',
            ),
            26 => 
            array (
                'id' => 2027,
                'car_make_id' => 109,
                'name' => 'TR8',
            ),
            27 => 
            array (
                'id' => 2028,
                'car_make_id' => 109,
                'name' => 'Other',
            ),
            28 => 
            array (
                'id' => 2029,
                'car_make_id' => 110,
                'name' => 'Chimaera',
            ),
            29 => 
            array (
                'id' => 2030,
                'car_make_id' => 110,
                'name' => 'Griffith',
            ),
            30 => 
            array (
                'id' => 2031,
                'car_make_id' => 110,
                'name' => 'Tuscan',
            ),
            31 => 
            array (
                'id' => 2032,
                'car_make_id' => 110,
                'name' => 'Other',
            ),
            32 => 
            array (
                'id' => 2033,
                'car_make_id' => 111,
                'name' => '181',
            ),
            33 => 
            array (
                'id' => 2034,
                'car_make_id' => 111,
                'name' => 'Amarok',
            ),
            34 => 
            array (
                'id' => 2035,
                'car_make_id' => 111,
                'name' => 'Arteon',
            ),
            35 => 
            array (
                'id' => 2036,
                'car_make_id' => 111,
                'name' => 'Beetle',
            ),
            36 => 
            array (
                'id' => 2037,
                'car_make_id' => 111,
                'name' => 'Beetle',
            ),
            37 => 
            array (
                'id' => 2038,
                'car_make_id' => 111,
                'name' => 'Bora',
            ),
            38 => 
            array (
                'id' => 2039,
                'car_make_id' => 111,
                'name' => 'Buggy',
            ),
            39 => 
            array (
                'id' => 2040,
                'car_make_id' => 111,
                'name' => 'Caddy',
            ),
            40 => 
            array (
                'id' => 2041,
                'car_make_id' => 111,
                'name' => 'CC',
            ),
            41 => 
            array (
                'id' => 2042,
                'car_make_id' => 111,
                'name' => 'Corrado',
            ),
            42 => 
            array (
                'id' => 2043,
                'car_make_id' => 111,
                'name' => 'Crafter',
            ),
            43 => 
            array (
                'id' => 2044,
                'car_make_id' => 111,
                'name' => 'Eos',
            ),
            44 => 
            array (
                'id' => 2045,
                'car_make_id' => 111,
                'name' => 'Fox',
            ),
            45 => 
            array (
                'id' => 2046,
                'car_make_id' => 111,
                'name' => 'Golf',
            ),
            46 => 
            array (
                'id' => 2047,
                'car_make_id' => 111,
                'name' => 'Golf Plus',
            ),
            47 => 
            array (
                'id' => 2048,
                'car_make_id' => 111,
                'name' => 'Golf Sportsvan',
            ),
            48 => 
            array (
                'id' => 2049,
                'car_make_id' => 111,
                'name' => 'Iltis',
            ),
            49 => 
            array (
                'id' => 2050,
                'car_make_id' => 111,
                'name' => 'Jetta',
            ),
            50 => 
            array (
                'id' => 2051,
                'car_make_id' => 111,
                'name' => 'Karmann Ghia',
            ),
            51 => 
            array (
                'id' => 2052,
                'car_make_id' => 111,
                'name' => 'LT',
            ),
            52 => 
            array (
                'id' => 2053,
                'car_make_id' => 111,
                'name' => 'Lupo',
            ),
            53 => 
            array (
                'id' => 2054,
                'car_make_id' => 111,
                'name' => 'New Beetle',
            ),
            54 => 
            array (
                'id' => 2055,
                'car_make_id' => 111,
                'name' => 'Passat',
            ),
            55 => 
            array (
                'id' => 2056,
                'car_make_id' => 111,
                'name' => 'Passat Alltrack',
            ),
            56 => 
            array (
                'id' => 2057,
                'car_make_id' => 111,
                'name' => 'Passat CC',
            ),
            57 => 
            array (
                'id' => 2058,
                'car_make_id' => 111,
                'name' => 'Passat Variant',
            ),
            58 => 
            array (
                'id' => 2059,
                'car_make_id' => 111,
                'name' => 'Phaeton',
            ),
            59 => 
            array (
                'id' => 2060,
                'car_make_id' => 111,
                'name' => 'Polo',
            ),
            60 => 
            array (
                'id' => 2061,
                'car_make_id' => 111,
                'name' => 'Routan',
            ),
            61 => 
            array (
                'id' => 2062,
                'car_make_id' => 111,
                'name' => 'Santana',
            ),
            62 => 
            array (
                'id' => 2063,
                'car_make_id' => 111,
                'name' => 'Scirocco',
            ),
            63 => 
            array (
                'id' => 2064,
                'car_make_id' => 111,
                'name' => 'Sharan',
            ),
            64 => 
            array (
                'id' => 2065,
                'car_make_id' => 111,
                'name' => 'T1',
            ),
            65 => 
            array (
                'id' => 2066,
                'car_make_id' => 111,
                'name' => 'T2',
            ),
            66 => 
            array (
                'id' => 2067,
                'car_make_id' => 111,
                'name' => 'T3 Caravelle',
            ),
            67 => 
            array (
                'id' => 2068,
                'car_make_id' => 111,
                'name' => 'T3 Kombi',
            ),
            68 => 
            array (
                'id' => 2069,
                'car_make_id' => 111,
                'name' => 'T3 Multivan',
            ),
            69 => 
            array (
                'id' => 2070,
                'car_make_id' => 111,
                'name' => 'T3 other',
            ),
            70 => 
            array (
                'id' => 2071,
                'car_make_id' => 111,
                'name' => 'T4 California',
            ),
            71 => 
            array (
                'id' => 2072,
                'car_make_id' => 111,
                'name' => 'T4 Caravelle',
            ),
            72 => 
            array (
                'id' => 2073,
                'car_make_id' => 111,
                'name' => 'T4 Kombi',
            ),
            73 => 
            array (
                'id' => 2074,
                'car_make_id' => 111,
                'name' => 'T4 Multivan',
            ),
            74 => 
            array (
                'id' => 2075,
                'car_make_id' => 111,
                'name' => 'T4 other',
            ),
            75 => 
            array (
                'id' => 2076,
                'car_make_id' => 111,
                'name' => 'T5 California',
            ),
            76 => 
            array (
                'id' => 2077,
                'car_make_id' => 111,
                'name' => 'T5 Caravelle',
            ),
            77 => 
            array (
                'id' => 2078,
                'car_make_id' => 111,
                'name' => 'T5 Kombi',
            ),
            78 => 
            array (
                'id' => 2079,
                'car_make_id' => 111,
                'name' => 'T5 Multivan',
            ),
            79 => 
            array (
                'id' => 2080,
                'car_make_id' => 111,
                'name' => 'T5 other',
            ),
            80 => 
            array (
                'id' => 2081,
                'car_make_id' => 111,
                'name' => 'T5 Shuttle',
            ),
            81 => 
            array (
                'id' => 2082,
                'car_make_id' => 111,
                'name' => 'T5 Transporter',
            ),
            82 => 
            array (
                'id' => 2083,
                'car_make_id' => 111,
                'name' => 'T6 California',
            ),
            83 => 
            array (
                'id' => 2084,
                'car_make_id' => 111,
                'name' => 'T6 Caravelle',
            ),
            84 => 
            array (
                'id' => 2085,
                'car_make_id' => 111,
                'name' => 'T6 Kombi',
            ),
            85 => 
            array (
                'id' => 2086,
                'car_make_id' => 111,
                'name' => 'T6 Multivan',
            ),
            86 => 
            array (
                'id' => 2087,
                'car_make_id' => 111,
                'name' => 'T6 other',
            ),
            87 => 
            array (
                'id' => 2088,
                'car_make_id' => 111,
                'name' => 'T6 Transporter',
            ),
            88 => 
            array (
                'id' => 2089,
                'car_make_id' => 111,
                'name' => 'Taro',
            ),
            89 => 
            array (
                'id' => 2090,
                'car_make_id' => 111,
                'name' => 'T-Cross',
            ),
            90 => 
            array (
                'id' => 2091,
                'car_make_id' => 111,
                'name' => 'Tiguan',
            ),
            91 => 
            array (
                'id' => 2092,
                'car_make_id' => 111,
                'name' => 'Tiguan Allspace',
            ),
            92 => 
            array (
                'id' => 2093,
                'car_make_id' => 111,
                'name' => 'Touareg',
            ),
            93 => 
            array (
                'id' => 2094,
                'car_make_id' => 111,
                'name' => 'Touran',
            ),
            94 => 
            array (
                'id' => 2095,
                'car_make_id' => 111,
                'name' => 'T-Roc',
            ),
            95 => 
            array (
                'id' => 2096,
                'car_make_id' => 111,
                'name' => 'up!',
            ),
            96 => 
            array (
                'id' => 2097,
                'car_make_id' => 111,
                'name' => 'Vento',
            ),
            97 => 
            array (
                'id' => 2098,
                'car_make_id' => 111,
                'name' => 'XL1',
            ),
            98 => 
            array (
                'id' => 2099,
                'car_make_id' => 111,
                'name' => 'Other',
            ),
            99 => 
            array (
                'id' => 2100,
                'car_make_id' => 112,
                'name' => '240',
            ),
            100 => 
            array (
                'id' => 2101,
                'car_make_id' => 112,
                'name' => '244',
            ),
            101 => 
            array (
                'id' => 2102,
                'car_make_id' => 112,
                'name' => '245',
            ),
            102 => 
            array (
                'id' => 2103,
                'car_make_id' => 112,
                'name' => '262',
            ),
            103 => 
            array (
                'id' => 2104,
                'car_make_id' => 112,
                'name' => '264',
            ),
            104 => 
            array (
                'id' => 2105,
                'car_make_id' => 112,
                'name' => '340',
            ),
            105 => 
            array (
                'id' => 2106,
                'car_make_id' => 112,
                'name' => '360',
            ),
            106 => 
            array (
                'id' => 2107,
                'car_make_id' => 112,
                'name' => '440',
            ),
            107 => 
            array (
                'id' => 2108,
                'car_make_id' => 112,
                'name' => '460',
            ),
            108 => 
            array (
                'id' => 2109,
                'car_make_id' => 112,
                'name' => '480',
            ),
            109 => 
            array (
                'id' => 2110,
                'car_make_id' => 112,
                'name' => '740',
            ),
            110 => 
            array (
                'id' => 2111,
                'car_make_id' => 112,
                'name' => '744',
            ),
            111 => 
            array (
                'id' => 2112,
                'car_make_id' => 112,
                'name' => '745',
            ),
            112 => 
            array (
                'id' => 2113,
                'car_make_id' => 112,
                'name' => '760',
            ),
            113 => 
            array (
                'id' => 2114,
                'car_make_id' => 112,
                'name' => '780',
            ),
            114 => 
            array (
                'id' => 2115,
                'car_make_id' => 112,
                'name' => '850',
            ),
            115 => 
            array (
                'id' => 2116,
                'car_make_id' => 112,
                'name' => '855',
            ),
            116 => 
            array (
                'id' => 2117,
                'car_make_id' => 112,
                'name' => '940',
            ),
            117 => 
            array (
                'id' => 2118,
                'car_make_id' => 112,
                'name' => '944',
            ),
            118 => 
            array (
                'id' => 2119,
                'car_make_id' => 112,
                'name' => '945',
            ),
            119 => 
            array (
                'id' => 2120,
                'car_make_id' => 112,
                'name' => '960',
            ),
            120 => 
            array (
                'id' => 2121,
                'car_make_id' => 112,
                'name' => '965',
            ),
            121 => 
            array (
                'id' => 2122,
                'car_make_id' => 112,
                'name' => 'Amazon',
            ),
            122 => 
            array (
                'id' => 2123,
                'car_make_id' => 112,
                'name' => 'C30',
            ),
            123 => 
            array (
                'id' => 2124,
                'car_make_id' => 112,
                'name' => 'C70',
            ),
            124 => 
            array (
                'id' => 2125,
                'car_make_id' => 112,
                'name' => 'Polar',
            ),
            125 => 
            array (
                'id' => 2126,
                'car_make_id' => 112,
                'name' => 'S40',
            ),
            126 => 
            array (
                'id' => 2127,
                'car_make_id' => 112,
                'name' => 'S60',
            ),
            127 => 
            array (
                'id' => 2128,
                'car_make_id' => 112,
                'name' => 'S60 Cross Country',
            ),
            128 => 
            array (
                'id' => 2129,
                'car_make_id' => 112,
                'name' => 'S70',
            ),
            129 => 
            array (
                'id' => 2130,
                'car_make_id' => 112,
                'name' => 'S80',
            ),
            130 => 
            array (
                'id' => 2131,
                'car_make_id' => 112,
                'name' => 'S90',
            ),
            131 => 
            array (
                'id' => 2132,
                'car_make_id' => 112,
                'name' => 'V40',
            ),
            132 => 
            array (
                'id' => 2133,
                'car_make_id' => 112,
                'name' => 'V40 Cross Country',
            ),
            133 => 
            array (
                'id' => 2134,
                'car_make_id' => 112,
                'name' => 'V50',
            ),
            134 => 
            array (
                'id' => 2135,
                'car_make_id' => 112,
                'name' => 'V60',
            ),
            135 => 
            array (
                'id' => 2136,
                'car_make_id' => 112,
                'name' => 'V60 Cross Country',
            ),
            136 => 
            array (
                'id' => 2137,
                'car_make_id' => 112,
                'name' => 'V70',
            ),
            137 => 
            array (
                'id' => 2138,
                'car_make_id' => 112,
                'name' => 'V90',
            ),
            138 => 
            array (
                'id' => 2139,
                'car_make_id' => 112,
                'name' => 'V90 Cross Country',
            ),
            139 => 
            array (
                'id' => 2140,
                'car_make_id' => 112,
                'name' => 'XC40',
            ),
            140 => 
            array (
                'id' => 2141,
                'car_make_id' => 112,
                'name' => 'XC60',
            ),
            141 => 
            array (
                'id' => 2142,
                'car_make_id' => 112,
                'name' => 'XC70',
            ),
            142 => 
            array (
                'id' => 2143,
                'car_make_id' => 112,
                'name' => 'XC90',
            ),
            143 => 
            array (
                'id' => 2144,
                'car_make_id' => 112,
                'name' => 'Other',
            ),
            144 => 
            array (
                'id' => 2145,
                'car_make_id' => 113,
                'name' => '311',
            ),
            145 => 
            array (
                'id' => 2146,
                'car_make_id' => 113,
                'name' => '353',
            ),
            146 => 
            array (
                'id' => 2147,
                'car_make_id' => 113,
                'name' => 'Other',
            ),
            147 => 
            array (
                'id' => 2148,
                'car_make_id' => 114,
                'name' => 'Other',
            ),
            148 => 
            array (
                'id' => 2149,
                'car_make_id' => 115,
                'name' => 'MF 25',
            ),
            149 => 
            array (
                'id' => 2150,
                'car_make_id' => 115,
                'name' => 'MF 28',
            ),
            150 => 
            array (
                'id' => 2151,
                'car_make_id' => 115,
                'name' => 'MF 3',
            ),
            151 => 
            array (
                'id' => 2152,
                'car_make_id' => 115,
                'name' => 'MF 30',
            ),
            152 => 
            array (
                'id' => 2153,
                'car_make_id' => 115,
                'name' => 'MF 35',
            ),
            153 => 
            array (
                'id' => 2154,
                'car_make_id' => 115,
                'name' => 'MF 4',
            ),
            154 => 
            array (
                'id' => 2155,
                'car_make_id' => 115,
                'name' => 'MF 5',
            ),
            155 => 
            array (
                'id' => 2156,
                'car_make_id' => 115,
                'name' => 'Other',
            ),
            156 => 
            array (
                'id' => 2157,
                'car_make_id' => 116,
                'name' => 'Other',
            ),
        ));
        
        
    }
}