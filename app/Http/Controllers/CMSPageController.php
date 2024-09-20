<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use Illuminate\Http\Request;

class CMSPageController extends Controller
{
    //
    public function index(Request $request, $slug)
    {
        $page = ContentPage::where('slug', $slug)->first();
        if ($page) {
            $content = $page->content;
            $title = $page->name;
            return view('cms-page', compact('content', 'title'));
        } else {
            abort(404);
        }
    }
}
