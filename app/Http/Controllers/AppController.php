<?php

namespace App\Http\Controllers;

use App\Helpers\HttpResponse;
use App\Repositories\FileRepository;
use Exception;
use Illuminate\Http\Request;


class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $repo;
    protected $response;

    public function __construct(FileRepository $repo , HttpResponse $response)
    {
        $this->repo = $repo;
        $this->response = $response;
    }


    public function processFile(Request $request)
    {
        
        try{

            $file = $request->file('file');

            $sales = $this->repo->prepareFile($file);
            $sales = $this->repo->formatData($sales);
            $sales = $this->repo->processSales($sales);

            return $this->response->sucess($sales);
        }catch(Exception $e){


            return $this->response->serverError($e->getMessage());
        }
      

    }
    //
}
