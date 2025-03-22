<?php

namespace App\Utils;

use AllowDynamicProperties;
use App\Representation\ResponseToClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[AllowDynamicProperties] class ParamsHelper
{
    const INPUT_NOT_FLUSHABLE = array('password');
    private array|null $inputs = array();

    private string|null $ip;

    private string|null $pip;

    private string|null $os;

    private string|null $method;

    private string|null $routeName;

    private SerializerInterface $serializer;

    private LoggerInterface $logger;

    public function __construct(RequestStack $requestStack, SerializerInterface $serializer, LoggerInterface $logger)
    {
        $request = $requestStack->getMainRequest();

        if ($request instanceof Request) {
            $this->ip = $request->getClientIp();
            $this->pip = getmygid();
            $this->routeName = $request->get('_route');
            $this->method = $request->getMethod();
            $this->os = $request->headers->get('os');

        } else {
            $this->ip = 'unknown';
            $this->pip = getmygid();
            $this->routeName = 'unknown';
            $this->method = 'unknown';
            $this->os = 'unknown';
        }

        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getInputs(): ?array
    {
        return $this->inputs;
    }

    /**
     * @param array|null $inputs
     * @return ParamsHelper
     */
    public function setInputs(array|null $inputs): ParamsHelper
    {
        $this->inputs = $inputs;
        return $this;
    }


    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function flushInputWithLogger()
    {
        if ($this->logger instanceof LoggerInterface) {
            if (!empty($this->inputs)) {
                $this->logger->info($this->loggerIdentifier() . 'RequestFromClient : ' .$this->serializer->serialize($this->filterParamsToFlush($this->inputs), 'json'));
            }else{
                $this->logger->info($this->loggerIdentifier() . 'RequestFromClient : No inputs to flush');
            }

            return true;
        }
        return false;
    }

    /**
     * @param ResponseToClient $responseToClient
     * @return bool
     */
    public function flushResponseToClient(ResponseToClient $responseToClient): bool
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->info($this->loggerIdentifier() . 'ResponseToClient : ' . json_encode($responseToClient, JSON_PRETTY_PRINT));
            return true;
        }

        return false;
    }

    /**
     * @param JsonResponse $response
     * @return bool
     */
    public function flushJsonResponse(JsonResponse $response): bool
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->info($this->loggerIdentifier() . 'ResponseToClient : ' . $response->getContent());
            return true;
        }

        return false;
    }

    public function flushString(string $message, array $context)
    {
        if (!$this->logger instanceof LoggerInterface) {
            return false;
        }

        switch ($context['method'])
        {
            case 'info':
                $this->logger->info($this->loggerIdentifier() . $context['domain'] . ' -- ' .$message);
                break;
            case 'critical':
                $this->logger->critical($this->loggerIdentifier() . $context['domain'] . ' -- ' .$message);
                break;
            case 'warning':
                $this->logger->warning($this->loggerIdentifier() . $context['domain'] . ' -- ' .$message);
                break;
            default :
                break;
        }

        return true;
    }

    /**
     * @return string
     */
    private function loggerIdentifier()
    {
        return "[$this->ip][$this->pip][$this->routeName][$this->method][$this->os]";
    }

    /**
     * @param array $inputs
     * @return array
     */
    public function filterParamsToFlush(array $inputs)
    {
        return array_filter($inputs, function ($index) {
            return in_array($index, self::INPUT_NOT_FLUSHABLE) ? false : true;
        }, ARRAY_FILTER_USE_KEY);
    }
}
