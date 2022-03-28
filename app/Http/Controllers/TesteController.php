<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class TesteController extends Controller
{
    protected $mensagem;

    public function index(){
        return view('index');
    }

    public function show($carro, Client $client){
        //Caso não exista o cache, ele executa o código e cria, se existir ele apenas executa o cache já criado
        if (!file_exists('Carros/'.$carro.'.html')){
            $this->criar_cache($carro, $client);
        }else{
            //Obtenção da data atual
            $data_atual = new \DateTime();
            //Obtenção da data de criação do arquivo cache
            $data_cache = new \DateTime(date('Y-m-d H:i:s', filectime('Carros/'.$carro.'.html')));
            //Obtenção da diferença de horas da data atual para a data de criação do arquivo cache
            $diff = $data_atual->diff($data_cache);
            $horas = $diff->h + ($diff->days * 24);
            //Se o arquivo foi criado a mais de uma hora, o código é interpretado novamente e armazenado em um cache
            if ($horas>=1){
                //Exclusão do arquivo criado anteriormente
                unlink('Carros/'.$carro.'.html');
                $this->criar_cache($carro, $client);
            }else{
                //Exibição do cache já interpretado anteriormente
                require('Carros/'.$carro.'.html');
            }
        }
    }

    public function criar_cache($carro, $client){
        //Início do cache
        ob_start();
        // Para teste estou pegando apenas os resultados das duas primeiras páginas
        // porém a olx lista da página 1-100, casso queira toda a listagem podemos
        // apenas alterar para $<=100 na validação do for
        $this->mensagem .= '<h2>MODELO DO CARRO: '.$carro.'</h2>';
        for ($i=1; $i<=2; $i++){
            if ($i==1){
                $crawler = $client->request('GET', "https://olx.com.br/autos-e-pecas/carros-vans-e-utilitarios?q='.$carro.'");
            }else{
                $crawler = $client->request('GET', "https://olx.com.br/autos-e-pecas/carros-vans-e-utilitarios?o='.$i.'&q='.$carro.'");
            }
            $this->extractContactsFrom($crawler, $i);
        }
        echo $this->mensagem;
        //Obtenção do código do cache
        $cache = ob_get_contents();
        //Final do cache
        ob_end_clean();
        //Cache salvo para busca do modelo específico
        file_put_contents('Carros/'.$carro.'.html', $cache);
        //Exibição do cache que acabou de ser interpretado e criado
        echo $cache;
    }

    public function extractContactsFrom(Crawler $crawler, $page){
        // Obtenção de todo o html da url passada e filtragem dos carros pala classe de cada <li>
        $this->mensagem .= "<h2>PÁGINA ".$page."</h2>";
        $crawler->filter("[class='sc-1fcmfeb-2 fvbmlV']")->each(function (Crawler $carNode){
            // Obtenção das informações do anúncio de acordo com a classe de cada informação
            $nameNode = $carNode->filter("[class='sc-1mbetcw-0 fKteoJ sc-ifAKCX jyXVpA']")->first();
            $infosNode = $carNode->filter("[class='sc-1j5op1p-0 lnqdIU sc-ifAKCX eLPYJb']")->first();
            $priceNode = $carNode->filter("[class='sc-ifAKCX eoKYee']")->first();
            $localNode = $carNode->filter("[class='sc-7l84qu-1 ciykCV sc-ifAKCX dpURtf']")->first();
            try{
                $this->mensagem .= "Modelo: ".$nameNode->text()."<br>Informaçõe: ".$infosNode->text()."<br>Preço: ".$priceNode->text()."<br>Local: ".$localNode->text()."<br><br>";
            }catch (\Exception $e){

            }
        });
    }
}
