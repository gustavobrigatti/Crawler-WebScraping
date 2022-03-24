<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class TesteController
{
    public function index(Client $client){
        $crawler = $client->request('GET', 'https://olx.com.br/autos-e-pecas/carros-vans-e-utilitarios?q=onix');
        $crawler->filter("[class='sc-1fcmfeb-2 fvbmlV']")->each(function (Crawler $carNode){
            $carro = [];
            $nameNode = $carNode->filter("[class='sc-1mbetcw-0 fKteoJ sc-ifAKCX jyXVpA']")->first();
            $infosNode = $carNode->filter("[class='sc-1j5op1p-0 lnqdIU sc-ifAKCX eLPYJb']")->first();
            $priceNode = $carNode->filter("[class='sc-ifAKCX eoKYee']")->first();
            $localNode = $carNode->filter("[class='sc-7l84qu-1 ciykCV sc-ifAKCX dpURtf']")->first();
            try{
                $carro['name'] = $nameNode->text();
                $carro['infos'] = $infosNode->text();
                $carro['price'] = $priceNode->text();
                $carro['local'] = $localNode->text();
            }catch (\Exception $e){

            }
            var_dump($carro);
        });
    }
}
