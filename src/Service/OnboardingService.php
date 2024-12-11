<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\PaymentInformation;
use App\Entity\User;
use App\Form\AddressType;
use App\Form\PaymentInfoType;
use App\Form\UserInformationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class OnboardingService
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly FormFactoryInterface $formFactory,
        private readonly RouterInterface $router,
    ) {}

    public function handleSteps($step, $request): RedirectResponse|FormInterface
    {
        $form = match ($step) {
            'userInfoStep' => $this->renderUserInfoStep(),
            'addressStep' => $this->renderAddressStep(),
            'paymentInfoStep' => $this->renderPaymentInfoStep(),
            'confirmStep' => new RedirectResponse($this->router->generate('confirmInfo')),
            default => new RedirectResponse($this->router->generate('onboarding', ['step' => 'userInfoStep']))
        };

        if ($form instanceof RedirectResponse) {
            return $form;
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return match ($step) {
                'userInfoStep' => $this->handleUserInfoStep($form),
                'addressStep' => $this->handleAddressStep($form),
                'paymentInfoStep' => $this->handlePaymentInfoStep($form),
                default => new RedirectResponse($this->router->generate('onboarding', ['step' => 'userInfoStep']))
            };
        }

        return $form;
    }

    private function renderUserInfoStep(): FormInterface
    {
        $userInfo = $this->requestStack->getSession()->get('user-info-step');
        if (!$userInfo instanceof User) {
            $userInfo = new User();
        }
        return $this->formFactory->create(UserInformationType::class, $userInfo);
    }

    private function renderAddressStep(): RedirectResponse|FormInterface
    {
        $address = $this->requestStack->getSession()->get('address-step');
        if (!$address instanceof Address) {
            $address = new Address();
        }
        $userInfo = $this->requestStack->getSession()->get('user-info-step');

        return $this->formFactory->create(AddressType::class, $address);
    }

    private function renderPaymentInfoStep(): FormInterface
    {
        $payment = $this->requestStack->getSession()->get('payment-info-step');
        if (!$payment instanceof PaymentInformation) {
            $payment = new PaymentInformation();
        }

        return $this->formFactory->create(PaymentInfoType::class, $payment);
    }

    private function handleUserInfoStep(FormInterface $form): RedirectResponse
    {
        $data = $form->getData();
        $this->requestStack->getSession()->set('user-info-step', $form->getData());

        return new RedirectResponse($this->router->generate('onboarding', ['step' => 'addressStep']));
    }

    private function handleAddressStep(FormInterface $form): RedirectResponse
    {
        $userInfo = $this->requestStack->getSession()->get('user-info-step');
        if ($userInfo->isPremium() === false) {
            return new RedirectResponse($this->router->generate('confirmInfo'));
        }

        $this->requestStack->getSession()->set('address-step', $form->getData());
        return new RedirectResponse($this->router->generate('onboarding', ['step' => 'paymentInfoStep']));
    }

    private function handlePaymentInfoStep(FormInterface $form): RedirectResponse
    {
        $this->requestStack->getSession()->set('payment-info-step', $form->getData());
        return new RedirectResponse($this->router->generate('onboarding', ['step' => 'confirmStep']));
    }

}