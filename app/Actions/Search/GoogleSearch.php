<?php

namespace App\Actions\Search;

use App\Dtos\PdfDto;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GoogleSearch
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection<PdfDto>
     */
    public function execute(Request $request): Collection
    {
        $search = str_replace(" ", "+", $request->input('s'));
        $url = "https://www.google.com/search?q=$search+filetype%3Apdf";
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->go($url);
        $pdfs = collect();
        return $pdfs;
    }
}
