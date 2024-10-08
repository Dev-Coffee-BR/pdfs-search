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
    public function __construct() {}

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function execute(Request $request): Collection
    {
        $pdfs = collect();
        $paginations = [1, 50, 100];
    
        foreach ($paginations as $first) {
            $search = urlencode(trim($request->input('s')) . " filetype:pdf");
            $url = "https://www.bing.com/search?q=" . $search . "&count=50&first=$first&setlang=pt-BR&cc=BR";
            $web = new \Spekulatius\PHPScraper\PHPScraper;
            $web->setConfig([
                'Accept-Language' => 'pt-PT',
                'agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
            ]);
            $web->go($url);
            $list = $web->filter("//li[@class='b_algo']")->each(function ($node) {
                return new PdfDto([
                    'link' => $node->filter("h2")->filter('a')->attr("href"),
                    'title' => $node->filter("h2")->filter('a')->text(),
                    'description' => substr($node->filter("div")->filter("p")->text(), 3)
                ]);
            });
    
            $pdfs->push(...$list);
        }
        return $pdfs;
    }
    
}
