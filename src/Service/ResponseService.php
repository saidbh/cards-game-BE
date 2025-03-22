<?php

namespace App\Service;

use App\Representation\ResponseToClient;
use App\Utils\ParamsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ResponseService
{
    const KEY_INVALID_PARAMS    = 'general.params';
    private $translator;

    private $paramsHelper;

    public function __construct(TranslatorInterface $translator, ParamsHelper $paramsHelper)
    {
        $this->translator = $translator;
        $this->paramsHelper = $paramsHelper;
    }

    /**
     * @throws ExceptionInterface
     */
    public function getResponseToClient($response = null, int $code = 200, string $messageKey = 'general.success', string $domain = 'messages', array $paramsMessage = array()): JsonResponse
    {
        $response = new ResponseToClient($response, $code, $this->getMessage($messageKey, $domain, $paramsMessage));
        $this->paramsHelper->flushResponseToClient($response);
        return new JsonResponse($response);
    }

    /**
     * @param string $messageKey
     * @param string $domain
     * @param array $paramsMessage
     * @return string
     */
    private function getMessage(string $messageKey, string $domain = 'messages', array $paramsMessage = array()): string
    {
        $message = $this->translator->trans($messageKey, $paramsMessage, $domain);

        if ($message !== $messageKey) {
            return $message;
        }

        return $this->translator->trans('general.500', $paramsMessage, $domain);
    }
}