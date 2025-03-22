<?php

namespace App\Service;

use AllowDynamicProperties;
use App\Utils\ParamsHelper;
use App\Utils\QuerySqlHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Utils\CardHelper;

#[AllowDynamicProperties] class CardService
{
    private ParamsHelper $paramsHelper;
    private QuerySqlHelper $querySqlHelper;
    private ResponseService $responseService;

    public function __construct(ParamsHelper $paramsHelper, QuerySqlHelper $querySqlHelper,ResponseService $responseService)
    {
        $this->paramsHelper = $paramsHelper;
        $this->querySqlHelper = $querySqlHelper;
        $this->responseService = $responseService;
    }

    public function getRandomCards():JsonResponse
    {
        try {
                $cardsSuite = $this->querySqlHelper->endPointQuery('getAllCardsSuit',[]);
                $cardsValue = $this->querySqlHelper->endPointQuery('getAllCardsValue',[]);

                if (empty($cardsSuite) || empty($cardsValue)) {
                    return $this->responseService->getResponseToClient(null,404,'general.not_found');
                }

                $colors = array_map(fn($color) => $color->getName(), $cardsSuite);
                $values = array_map(fn($value) => $value->getName(), $cardsValue);

                $deck = [];
                foreach ($colors as $color) {
                    foreach ($values as $value) {
                        $deck[] = ['color' => $color, 'value' => $value];
                    }
                }

                shuffle($deck);
                $hand = array_slice($deck, 0, 10);

                return $this->responseService->getResponseToClient($hand);

        }catch (\Exception $exception){
            return $this->responseService->getResponseToClient(null,500,'general.500');
        }
    }

    public function getSortedCards():JsonResponse
    {
        try {
            $cards = $this->paramsHelper->getInputs();

            usort($cards['cards'], function ($a, $b){
                $colorA = CardHelper::COLOR_ORDER[$a['color']] ?? 99;
                $colorB = CardHelper::COLOR_ORDER[$b['color']] ?? 99;

                if ($colorA !== $colorB) {
                    return $colorA <=> $colorB;
                }

                $valueA = CardHelper::VALUE_ORDER[$a['value']] ?? 99;
                $valueB = CardHelper::VALUE_ORDER[$b['value']] ?? 99;

                return $valueA <=> $valueB;
            });

            return $this->responseService->getResponseToClient($cards['cards']);

        }catch (\Exception $exception){
            return $this->responseService->getResponseToClient(null,500,'general.500');
        }


    }
}