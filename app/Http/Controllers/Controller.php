<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $perPage = 20;
    public $productsPerPage = 20;


    // default constructor
    public function __construct()
    {
        $this->productsPerPage = (int) setting('productsPerPage', 30);
    }


    public function readalbeError($validator)
    {
        return Arr::first(Arr::flatten($validator->messages()->get('*')));
    }


    //
    public function isDemo()
    {
        if (!\App::environment('production')) {
            throw new \Exception(__("App is in demo version. Some changes can't be made"));
        };
    }

    public function isInDemo()
    {
        return !\App::environment('production');
    }
}