<?php

namespace App\Actions\Search;

use Illuminate\Http\Request;
use App\Actions\Search\BingSearch;
use App\Actions\Search\GoogleSearch;

use App\Repositories\Modules\RequestLog\RequestLogRepository;

class SearchActionMain
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected BingSearch $bingSearch,
        protected GoogleSearch $googleSearch,
        protected RequestLogRepository $requestLogRepository
    ){}

    public function execute(Request $request)
    {
        $pdfs = $this->bingSearch->execute($request);

        if($request->s != null){
            $this->requestLogRepository->create([
                'search' => $request->s,
                'engine' => 'bing',
                'ip' => $_REQUEST['REMOTE_ADDR']
            ]);
        }

        return [
            'pdfs' => $pdfs,
            'message' => "Success",
            'total' => $pdfs->count()
        ];
    }
}
