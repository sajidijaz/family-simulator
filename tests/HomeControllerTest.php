<?php


use App\Request\Request;
use App\Services\FamilySimulatorService;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{

    private Request $request;
    private FamilySimulatorService $familySimulatorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createMock(Request::class);
        $_SERVER["REQUEST_METHOD"] = "POST";
        $this->familySimulatorService = $this->createMock(FamilySimulatorService::class);
    }

    public function testFamilySimulator()
    {
        $expected = ['error' => false, 'html' => ''];
        $this->expectOutputString(json_encode($expected));
        $this->familySimulatorService->method('family')->willReturn(new \App\Services\FamilySimulator());
        $controller = new \App\Controllers\HomeController();
        $controller->familySimulator();
    }

}
