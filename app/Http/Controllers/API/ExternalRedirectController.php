<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;




class ExternalRedirectController extends Controller
{

    public function index(Request $request)
    {
        $externalUrl = $request->endpoint;
        if (empty($externalUrl)) {
            return response()->json([
                "message" => __("Failed"),
            ], 400);
        }
        $externalUrl = str_ireplace(";", "&", $externalUrl);
        return Http::get($externalUrl)->json();
    }


    //api to web redirect
    public function webRedirect(Request $request)
    {
        //take the parameters from the request and redirect to the web
        $baseUrl = route('web.auth.redirect');
        $params = $request->all();
        $url = $baseUrl . "?" . http_build_query($params);
        return redirect()->to($url);
    }
}