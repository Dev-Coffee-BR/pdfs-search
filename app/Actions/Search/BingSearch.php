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
    public function execute(Request $request)
    {
        $search = $request->input('s');
        $url = "https://www.bing.com/search?q=$search+inurl%3A.pdf+filetype%3Apdf";
        // echo $url;
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->go($url);
       
        /**
         * @var Collection<PdfDto>
         */
        $pdfs = collect();
        $pdfs = $web->filter("//li[@class='b_algo']")->each(function($node) {
            $dto = new PdfDto([
                'link' => explode(".pdf",  $node->filter("h2")->filter('a')->attr("href"))[0].".pdf",
                'title' => $node->filter("h2")->filter('a')->text(),
                'description' => substr($node->filter("div")->filter("p")->text(), 3)
            ]);
            return $dto;
        });
        return collect($pdfs);   
    }
}
