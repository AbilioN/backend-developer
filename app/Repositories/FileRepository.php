<?php
namespace App\Repositories;

use App\Http\Services\AddressService;
use Carbon\Carbon;

class FileRepository
{

    private $service;
    public function __construct(AddressService $service)
    {
        $this->service = $service;
    }

    public function prepareFile($file)
    {
        $lines = [];
        foreach(file($file) as $line)
        {
            $lines[] = $line;
        }


        $sales   = [];

        foreach($lines as $line)
        {
            $id = substr($line, 0, 3);
            $date = substr($line, 3, 8);
            $amount = substr($line, 11, 10);
            $installments = substr($line, 21, 2);
            $clientName = substr($line, 23, 20);
            $cep = substr($line, 43, 8);

            $newSeller = [];
            $newSeller['id'] = $id;
            $newSeller['date'] = $date;
            $newSeller['amount'] = $amount;
            $newSeller['installments'] = $installments;
            $newSeller['client'] = $clientName;
            $newSeller['cep'] = $cep;

            $sales  [] = $newSeller;
        }

        return $sales   ;
    }

    public function formatData($sales)
    {
        $sales = collect($sales);
        $sales->transform(function($item){


            $date  = substr_replace($item['date'], '-' , 4, 0);
            $date = substr_replace($date, '-' , 7, 0);
            $item['date'] = $date;


            // format amount
            $item['amount']  = substr_replace($item['amount'], '.' , -2, 0);
            $item['amount'] = ltrim($item['amount'], "0"); 


            // format installments
            $item['installments'] = ltrim($item['installments'], "0"); 


            // format clientName
            $item['client'] = trim($item['client']);

            return $item;
        });

        return $sales;
    }

    public function processSales($sales)
    {

        $processedSales = [];

        foreach($sales as $sale)
        {
            $newProcessedSale = [];
            $newProcessedSale['id'] = $sale['id'];
            
            $newProcessedSale['date'] = $sale['date'];
            $newProcessedSale['amount'] = $sale['amount'];
            $newProcessedSale['costumer'] = [
                'name' => $sale['client'],
                'address' => $this->service->verifyCep($sale['cep'])
            ];
            
            $totalInstallments = (int) $sale['installments'];
            $parcelas = $sale['amount']/ $totalInstallments;

            if($parcelas*$totalInstallments != $sale['amount']){
                // apply how to solve the difference;
                dd($parcelas*$totalInstallments, $sale['amount']);
                
            }

            $installments = [];
            for($i = 1; $i <= $totalInstallments; $i++)
            {

                $sumDays = $i*30;
                $sumString = "+". $sumDays . " days";
                $installmentDate = date('Y-m-d', strtotime($sumString, strtotime($sale['date'])));

                while($this->isWeekend($installmentDate))
                {
                    $sumDays = $sumDays+1;
                    $sumString = "+". $sumDays . " days";
                    $installmentDate = date('Y-m-d', strtotime($sumString, strtotime($sale['date'])));
    
                }

                $installment['installment'] = $i;
                $installment['amount'] = $parcelas;
                $installment['date'] = $installmentDate;
                $installments[] = $installment;
            }

            $newProcessedSale['installments'] = $installments;

            $processedSales[] = $newProcessedSale;
        }

        return $processedSales;
    }

    function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }
}