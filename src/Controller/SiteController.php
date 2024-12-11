<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\OnboardingService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SiteController extends BaseController
{

    public function __construct(
        private UserRepository $userRepository,
        private RequestStack $requestStack,
        private OnboardingService $onboardingService
    ) {}

    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
    ): Response {

        $query = $entityManager->getRepository(User::class)->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/onboarding/save', name: 'saveUser')]
    public function saveUser(Request $request): RedirectResponse
    {
        $session = $this->requestStack->getSession();

        $user = $session->get('user-info-step');
        $address = $session->get('address-step');
        $paymentInfo = $session->get('payment-info-step');

        $user->setAddress($address);
        if ($paymentInfo !== null) {
            $user->setPaymentInformation($paymentInfo);
        }

        $this->userRepository->save($user, true);

        $session->remove('user-info-step');
        $session->remove('address-step');
        $session->remove('payment-info-step');

        return $this->redirectToRoute('home');
    }


    #[Route('/onboarding/confirmInfo', name: 'confirmInfo')]
    public function confirmInfo(Request $request): Response
    {

        return $this->render('onboarding/confirm.html.twig', [
            'user' => $this->requestStack->getSession()->get('user-info-step'),
            'address' => $this->requestStack->getSession()->get('address-step'),
            'paymentInfo' => $this->requestStack->getSession()->get('payment-info-step'),
            'step' => $this->stepTitle('confirmStep')
        ]);
    }

    #[Route('/onboarding/{step}', name: 'onboarding', defaults: ['step' => 'userInfoStep'])]
    public function onboarding(string $step, Request $request): RedirectResponse|Response
    {
        $form = $this->onboardingService->handleSteps($step, $request);

        if ($form instanceof RedirectResponse) {
            return $form;
        }

        return $this->render(sprintf('onboarding/%s.html.twig', $step), [
            'form' => $form,
            'data' => $form->getData(),
            'step' => $this->stepTitle($step),
        ]);
    }

    private function stepTitle(string $step)
    {
        return match ($step) {
            'userInfoStep' => 'User Information',
            'addressStep' => 'Address',
            'paymentInfoStep' => 'Payment Information',
            'confirmStep' => 'Confirm Information',
            default => ''
        };
    }
}