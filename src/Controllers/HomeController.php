<?php

namespace App\Controllers;


use App\Exceptions\FamilySimulatorException;
use App\Exceptions\TwigRenderException;
use App\Helpers\Twig;
use App\Request\Request;
use App\Services\Cache;
use App\Services\FamilySimulator;
use App\Services\FamilySimulatorService;

class HomeController
{
    private ?Request $request;
    private FamilySimulatorService $familySimulatorService;

    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->familySimulatorService = new FamilySimulatorService(new Cache());
    }

    public function index(): void
    {
        try {
            $response = Twig::render(
                'index.twig',
                'Views',
                ['family' => $this->familySimulatorService->getFamily()]
            );
        } catch (TwigRenderException $e) {
            $response = $e->getMessage();
        }
        echo $response;
    }

    public function familySimulator(): void
    {
        $response = ['error' => true, 'message' => 'Something went wrong'];
        try {
            $familySimulator = $this->familySimulatorService->family($this->request);
            $response = ['error' => false, 'html' => $this->getFamilySimulator($familySimulator)];
        } catch (FamilySimulatorException $e) {
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);
    }

    private function getFamilySimulator(FamilySimulator $familySimulator): string
    {
        try {
            return Twig::render(
                'list.twig',
                'Views',
                ['familySimulator' => $familySimulator]
            );
        } catch (TwigRenderException $e) {
            return $e->getMessage();
        }
    }

}
