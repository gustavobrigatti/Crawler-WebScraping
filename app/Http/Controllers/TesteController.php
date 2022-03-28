<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class TesteController
{
    protected $carros;

    public function index(){
        return view('index');
    }

    public function store(Request $request, Client $client){
        // Para teste estou pegando apenas os resultados das duas primeiras páginas
        // porém a olx lista da página 1-100, casso queira toda a listagem podemos
        // apenas alterar para $<=100 na validação do for
        for ($i=1; $i<=2; $i++){
            if ($i==1){
                $crawler = $client->request('GET', "https://olx.com.br/autos-e-pecas/carros-vans-e-utilitarios?q='.$request->carro.'");
            }else{
                $crawler = $client->request('GET', "https://olx.com.br/autos-e-pecas/carros-vans-e-utilitarios?o='.$i.'&q='.$request->carro.'");
            }
            $this->extractContactsFrom($crawler, $i);
        }
        echo $this->carros;
    }

    public function extractContactsFrom(Crawler $crawler, $page){
        // Obtenção de todo o html da url passada e filtragem dos carros pala classe de cada <li>
        $this->carros .= "<h2>PÁGINA ".$page."</h2>";
        $crawler->filter("[class='sc-1fcmfeb-2 fvbmlV']")->each(function (Crawler $carNode){
            $carro = [];
            // Obtenção das informações do anúncio de acordo com a classe de cada informação
            $nameNode = $carNode->filter("[class='sc-1mbetcw-0 fKteoJ sc-ifAKCX jyXVpA']")->first();
            $infosNode = $carNode->filter("[class='sc-1j5op1p-0 lnqdIU sc-ifAKCX eLPYJb']")->first();
            $priceNode = $carNode->filter("[class='sc-ifAKCX eoKYee']")->first();
            $localNode = $carNode->filter("[class='sc-7l84qu-1 ciykCV sc-ifAKCX dpURtf']")->first();
            try{
                $carro['name'] = $nameNode->text();
                $carro['infos'] = $infosNode->text();
                $carro['price'] = $priceNode->text();
                $carro['local'] = $localNode->text();
                $this->carros .= "Modelo: ".$carro['name']."<br>Informaçõe: ".$carro['infos']."<br>Preço: ".$carro['price']."<br>Local: ".$carro['local']."<br><br>";
            }catch (\Exception $e){

            }
        });
    }
}
