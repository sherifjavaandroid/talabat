<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class CategoryColorGen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:colorize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random colors for categories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //generate colors for categories
        $categories = Category::all();
        foreach ($categories as $category) {
            $color = "#" . $this->changeBrightness($this->genColor(), rand(300, 550));
            $category->color = $color;
            $category->save();
        }

        return 0;
    }


    public function genColor()
    {
        return  substr(str_shuffle('ABCDEF0123456789'), 0, 6);
    }

    function changeBrightness($hex, $adjust)
    {
        $red   = hexdec($hex[0] . $hex[1]);
        $green = hexdec($hex[2] . $hex[3]);
        $blue  = hexdec($hex[4] . $hex[5]);

        $cb = $red + $green + $blue;

        if ($cb > $adjust) {
            $db = ($cb - $adjust) % 255;

            $red -= $db;
            $green -= $db;
            $blue -= $db;
            if ($red < 0) $red = 0;
            if ($green < 0) $green = 0;
            if ($blue < 0) $blue = 0;
        } else {
            $db = ($adjust - $cb) % 255;

            $red += $db;
            $green += $db;
            $blue += $db;
            if ($red > 255) $red = 255;
            if ($green > 255) $green = 255;
            if ($blue > 255) $blue = 255;
        }

        return str_pad(dechex($red), 2, '0', 0)
            . str_pad(dechex($green), 2, '0', 0)
            . str_pad(dechex($blue), 2, '0', 0);
    }

}
