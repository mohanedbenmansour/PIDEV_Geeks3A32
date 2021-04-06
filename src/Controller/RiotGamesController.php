<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class RiotGamesController extends AbstractController
{
    /**
     * @Route("/riot/games", name="riot_games")
     */
    public function index( Request $request): Response
    {
        $httpClient = HttpClient::create();
$nickname=$request->query->get('nickname');
$level="";
$id="";
$icon=rand(1,4);

$tier="";
$name="";
$rank="";

$wins="";
$losses="";

    $response = $httpClient->request('GET', 'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/'.$nickname.'?api_key=RGAPI-a4de6d5c-a358-4dde-8fe9-7f19518b575f');

        if (200 !== $response->getStatusCode()) {
            dd($response->getStatusCode());
            // handle the HTTP request error (e.g. retry the request)
        } else {
            $content = $response->getContent();
            $content = json_decode($content, true);
           $level=$content["summonerLevel"];
           $id=$content["id"];
        }
        $response = $httpClient->request('GET', 'https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/gPa7DtFQWhn9BdPFUqHtnmO0_Wg6lQbSxfX1GHreu5vqT9s?api_key=RGAPI-a4de6d5c-a358-4dde-8fe9-7f19518b575f');

        if (200 !== $response->getStatusCode()) {
            dd($response->getStatusCode());
            // handle the HTTP request error (e.g. retry the request)
        } else {
            $content = $response->getContent();
            $content = json_decode($content, true);

           $tier=$content[0]["tier"];;
            $rank=$content[0]["rank"];;
            $name=$content[0]["summonerName"];;
            $wins=$content[0]["wins"];;
            $losses=$content[0]["losses"];;
        }

        return $this->render('riot_games/riot.html.twig', [
            'name' => $name,
            'tier'=>$tier,
            'rank' => $rank,
            'level' => $level,
          'icon' => $icon,
          'wins' => $wins,
          'losses' => $losses,
        ]);
    }
    /**
     * @Route("/riot", name="riot")
     */
    public function index1(): Response
    {


        return $this->render('riot_games/index.html.twig', [
            'controller_name' => 'RiotGamesController',
        ]);
    }
}
