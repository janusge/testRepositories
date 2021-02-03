<?php

namespace App\Handler;

use App\RestDispatchTrait;
use App\Service\Contract\PaymentServiceInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use App\Handler\Contract\PaymentHandlerInterface;
use App\Exception\EntityNotFoundException;
use App\Exception\EmptyBodyRequestException;

class PaymentHandler implements
    RequestHandlerInterface,
    PaymentHandlerInterface
{
    use RestDispatchTrait;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function post(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $attributes = $request->getParsedBody();
            if(empty($attributes)){
                throw new EmptyBodyRequestException("No request body send.");
            }
            $payment = $this->paymentService->create($attributes);
            return new JsonResponse([
                "payment" => $payment
            ], 200);
        } catch (EmptyBodyRequestException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function get(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $id = $request->getAttribute('id');
            if ($id) {
                $payment = $this->paymentService->details($id);
                return new JsonResponse([
                    "payment" => $payment
                ], 200);
            } else {
                $payments = $this->paymentService->list();
                return new JsonResponse([
                    "payments" => $payments
                ], 200);
            }
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (\Exception $exception) {
            return new JsonResponse( [
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function put(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $id = $request->getAttribute('id');
            $attributes = $request->getParsedBody();
            if(empty($attributes)){
                throw new EmptyBodyRequestException("No request body send.");
            }
            $payment = $this->paymentService->update($id, $attributes);
            return new JsonResponse([
                "payment" => $payment
            ], 200);
        } catch (EntityNotFoundException | EmptyBodyRequestException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $id = $request->getAttribute('id');
            $payment = $this->paymentService->destroy($id);
            return new JsonResponse([
                "payment" => $payment
            ], 200);
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
