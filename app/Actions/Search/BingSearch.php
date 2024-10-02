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
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection<PdfDto>
     */
    public function execute(Request $request)
    {
        /**
         * @var Collection<PdfDto>
         */
        $pdfs = collect();
        $paginations = [1, 50, 100];

        foreach ($paginations as $first) {
            $search = urlencode(trim($request->input('s')) . " filetype:pdf (site:amazonaws.com OR site:archive.org)");
            $url = "https://www.bing.com/search?q=" . $search . "&count=50&first=$first";
            $web = new \Spekulatius\PHPScraper\PHPScraper;
            $web->go($url);
            
            $list = ($web->filter("//li[@class='b_algo']")->each(function ($node) {
                $dto = new PdfDto([
                    'link' => explode(".pdf",  $node->filter("h2")->filter('a')->attr("href"))[0] . ".pdf",
                    'title' => $node->filter("h2")->filter('a')->text(),
                    'description' => substr($node->filter("div")->filter("p")->text(), 3)
                ]);
                return $dto;
            }));

            $pdfs->push(...$list);
        }

        return collect($pdfs);
    }
}
