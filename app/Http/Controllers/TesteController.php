<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Goutte\Client;

class TesteController
{
    public function index(Client $client){
        $crawler = $client->request('GET', 'https://olx.com.br/autos-e-pecas/carros-vans-e-utilitarios?q=onix');
        $inlineCarClass = 'sc-1fcmfeb-2 fvbmlV';
        $crawler->filter("[class='$inlineCarClass']")->each(function ($carNode){
            $carro = new Carro();
            $nameNode = $carNode->filter("[class='sc-1mbetcw-0 fKteoJ sc-ifAKCX jyXVpA']")->first();
            $infosNode = $carNode->filter("[class='sc-1j5op1p-0 lnqdIU sc-ifAKCX eLPYJb']")->first();
            $priceNode = $carNode->filter("[class='sc-ifAKCX eoKYee']")->first();
            $localNode = $carNode->filter("[class='sc-7l84qu-1 ciykCV sc-ifAKCX dpURtf']")->first();
            $carro->nome = $nameNode->text();
            $carro->infos = $infosNode->text();
            $carro->preco = $priceNode->text();
            $carro->local = $localNode->text();
            dd($carro);
        });
    }
}
