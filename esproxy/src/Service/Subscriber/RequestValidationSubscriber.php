<?php

namespace App\Service\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RequestValidationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() === 422) {
            $violationList = $exception->getPrevious()->getViolations();

            if ($violationList instanceof ConstraintViolationListInterface) {
                $errors = [];

                /** @var ConstraintViolation $violation */
                foreach ($violationList as $violation) {

                    $constraint = $violation->getConstraint();

                    switch (true) {
                        case $constraint instanceof Assert\Type:
                        case $constraint instanceof Assert\Range:
                        case $constraint instanceof Assert\NotBlank:
                        case $constraint instanceof Assert\All:
                        case $constraint instanceof Assert\PositiveOrZero:
                        case $constraint instanceof Assert\LessThanOrEqual:
                            $httpCode = 400;
                            break;
                        default:
                            $httpCode = 400;


                    }

                    $response = new JsonResponse([
                        'status' => 'error',
                        'message' => $violation->getMessage(),
                        'errors' => $errors,
                    ], $httpCode);

                    $event->setResponse($response);
                }
            }
        }
    }
}