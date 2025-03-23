<?php

namespace App\Controller;

use App\Service\CardService;
use App\Service\ResponseService;
use App\Utils\ParamsHelper;
use App\Validator\Card\CardsValidator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

final class CardController extends AbstractController
{
    #[Route('/random/cards/draw', name: 'api_app_random_cards', methods: ['GET'])]
    #[OA\Get(path: '/random/cards/draw', tags: ['Card'])]
    #[OA\Response(
        response: 200,
        description: 'Successful draw',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'header',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'code', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Opération effectuée avec succès !')
                    ]
                ),
                new OA\Property(
                    property: 'response',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'color', type: 'string', example: 'Trèfle'),
                            new OA\Property(property: 'value', type: 'string', example: 'Roi')
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal Server Error',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'header',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'code', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Une erreur est survenue merci de réessayer plus tard')
                    ]
                ),
                new OA\Property(property: 'response', type: 'null', nullable: true)
            ]
        )
    )]
    public function randomCards(CardService $cardService, Request $request,ParamsHelper $paramsHelper,LoggerInterface $drawCardsLogger)
    {
        $inputs = $request->request->all();
        $paramsHelper->setInputs($inputs);
        $paramsHelper->setLogger($drawCardsLogger);
        $paramsHelper->flushInputWithLogger();
        return $cardService->getRandomCards();
    }

    #[Route('/random/cards/sort', name: 'api_app_sort_cards', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'cards',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'color', type: 'string', example: 'Carreaux'),
                            new OA\Property(property: 'value', type: 'string', example: 'Valet')
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'header',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'code', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Opération effectuée avec succès !')
                    ]
                ),
                new OA\Property(
                    property: 'response',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'color', type: 'string', example: 'Trèfle'),
                            new OA\Property(property: 'value', type: 'string', example: 'Roi')
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request - Invalid parameters',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'header',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'code', type: 'integer', example: 400),
                        new OA\Property(property: 'message', type: 'string', example: 'Les paramètres soumis sont incorrects')
                    ]
                ),
                new OA\Property(property: 'response', type: 'null', nullable: true)
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal Server Error',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'header',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'code', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Une erreur est survenue merci de réessayer plus tard')
                    ]
                ),
                new OA\Property(property: 'response', type: 'null', nullable: true)
            ]
        )
    )]
    public function handCardsSorted(CardService $cardService, Request $request,ParamsHelper $paramsHelper,ValidatorInterface $validator,LoggerInterface $sortCardsLogger,ResponseService $responseService)
    {
        $inputs = json_decode($request->getContent(), true);
        $paramsHelper->setInputs($inputs);
        $paramsHelper->setLogger($sortCardsLogger);
        $paramsHelper->flushInputWithLogger();
        if (count(CardsValidator::validateCards($inputs, $validator)) > 0) {
            return $responseService->getResponseToClient(null, 400, 'general.params');
        }
        return $cardService->getSortedCards();
    }
}
