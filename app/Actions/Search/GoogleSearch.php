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
        $url = "https://search.brave.com/search?q=fdsdf&source=web";
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->go($url);
        $web->setConfig([
            'agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:107.0) Gecko/20100101 Firefox/107.0',
            'headers' => [
               'Accept-Language' => 'en-US,en;q=0.5',
               'Accept-Encoding' => 'gzip, deflate, br',
               'Connection' => 'keep-alive',
               'Referer' => 'https://example.com',
               'Cache-Control' => 'no-cache',
            ]
         ]);
        /**
         * @var Collection<PdfDto>
         */
        $pdfs = collect();
        
        dd($web->filter("//a"));
        // dd($web->filter("//a[@class='url']"));

        $pdfs = $web->filter("//span[@class='VuuXrf']")->each(function($node) {
            echo $node->filter("div")->filter('span')->filter('a')->attr("href");
            $dto = new PdfDto([
                'link' => explode(".pdf",  $node->filter("div")->filter('span')->filter('a')->attr("href"))[0].".pdf",
                // 'title' => $node->filter("h2")->filter('a')->text(),
                // 'description' => substr($node->filter("div")->filter("p")->text(), 3)
            ]);
            return $dto;
        });

        return collect($pdfs);   
    }
}
