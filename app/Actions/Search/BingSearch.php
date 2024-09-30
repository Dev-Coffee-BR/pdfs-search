<?php

namespace App\Actions\Search;

use App\Dtos\PdfDto;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BingSearch
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
        $search = str_replace(" ", "%20", $request->input('s'));
        $url = "https://www.bing.com/search?q=$search%20filetype%3Apdf";
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->go($url);
       
        $pdfs = collect();
        $web->filter("//li[@class='b_algo']")->each(function($node) use (&$pdfs) {
            if(str_contains($node->filter("h2")->filter('a')->attr("href"), ".pdf")) {
                $dto = new PdfDto([
                    'link' => $node->filter("h2")->filter('a')->attr("href"),
                    'title' => $node->filter("h2")->filter('a')->text(),
                    'description' => substr($node->filter("div")->filter("p")->text(), 3)
                ]);
                $pdfs->add($dto);
            }
        });
        return $pdfs;
    }
}
