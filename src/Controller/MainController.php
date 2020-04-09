<?php

namespace App\Controller;

use App\Entity\SequenceFormData;
use App\Form\SequenceForm;
use App\Service\SequenceService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class MainController extends AbstractController {

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var SequenceService
     */
    private $sequenceService;

    /**
     * MainController constructor.
     * @param FlashBagInterface $flashBag
     * @param SequenceService $sequenceService
     */
    public function __construct(FlashBagInterface $flashBag, SequenceService $sequenceService) {
        $this->flashBag = $flashBag;
        $this->sequenceService = $sequenceService;
    }

    /**
     * @Route("/", name="main")
     * @param Request $request
     * @return Response
     */
    public function mainPage(Request $request) {
        $form = $this->createForm(SequenceForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $inputNumbers = $this->sequenceService->prepareInput($form->getData()['numbers']);
                $outputNumbers = $this->sequenceService->sequenceOutput($inputNumbers);
                $numbersArray = [];
                foreach ($inputNumbers as $number) {
                    $numbersArray[] = [
                        'entryNumber' => $number,
                        'biggestValue' => $this->sequenceService->findBiggestNumberInSequenceOutput($number, $outputNumbers),
                    ];
                }

                return $this->render('main.html.twig', [
                    'form' => $form->createView(),
                    'data' => $numbersArray,
                ]);
            } catch (Exception $e) {
                $this->flashBag->add('danger', $e->getMessage());
            }
        }

        return $this->render('main.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}