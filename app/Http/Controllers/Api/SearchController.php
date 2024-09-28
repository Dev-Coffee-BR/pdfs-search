<?php

namespace App\Http\Controllers\Api;

use App\Actions\Search\SearchActionMain;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected SearchActionMain $searchActionMain
    ){}

    public function consult(Request $request)
    {
        if($request->s == null) {
            return response([
                'pdfs' => []
            ], 200);
        }
        return response($this->searchActionMain->execute($request), 200);
    }
}
