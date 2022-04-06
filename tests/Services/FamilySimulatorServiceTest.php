<?php


namespace Services;

use App\Exceptions\FamilySimulatorException;
use App\Helpers\ActionEnum;
use App\Request\Request;
use App\Services\Cache;
use App\Services\FamilySimulator;
use App\Services\FamilySimulatorService;
use PHPUnit\Framework\TestCase;

class FamilySimulatorServiceTest extends TestCase
{
    private Cache $cache;
    private Request $request;
    private FamilySimulatorService $familySimulatorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = $this->createMock(Cache::class);
        $this->request = $this->createMock(Request::class);
        $this->familySimulatorService = new FamilySimulatorService($this->cache);
    }

    public function testGetFamily()
    {
        $this->cache->method('get')->willReturn([]);
        $this->familySimulatorService->getFamily();
        $this->assertTrue(true);
    }

    public function testFamilyCount()
    {
        $familySimulator = $this->familySimulatorService->getFamily();
        $this->assertEquals($familySimulator->getCount(), (new FamilySimulator())->getCount());
    }

    public function testValidateControlValue()
    {
        $this->request->method('getPost')->willReturn('add_dad');
        $this->familySimulatorService->family($this->request);
        $this->assertEquals(ActionEnum::ADD_DAD, $this->request->getPost('control'));
    }

    public function testValidateDadFamily()
    {
        $this->expectException(FamilySimulatorException::class);
        $this->cache->method('get')->willReturn(['father' => 1]);
        $this->request->method('getPost')->willReturn('add_dad');
        $this->familySimulatorService->family($this->request);
    }

    public function testMumExceptionMessage()
    {
        $this->cache->method('get')->willReturn(['mother' => 1]);
        $this->request->method('getPost')->willReturn('add_mum');
        try {
            $this->familySimulatorService->family($this->request);
        }catch (FamilySimulatorException $e){
            $this->assertSame('ERROR: The family already has a mum. (No support for modern families yet. :))', $e->getMessage());
        }
    }

    public function testChildExceptionMessage()
    {
        $this->cache->method('get')->willReturn([]);
        $this->request->method('getPost')->willReturn('add_child');
        try {
            $this->familySimulatorService->family($this->request);
        }catch (FamilySimulatorException $e){
            $this->assertSame('ERROR: No child without a mum or a dad.', $e->getMessage());
        }
    }

    public function testChildIfDadExists()
    {
        $this->cache->method('get')->willReturn(['father'=> 1]);
        $this->request->method('getPost')->willReturn('add_child');
        try {
            $this->familySimulatorService->family($this->request);
        }catch (FamilySimulatorException $e){
            $this->assertSame('ERROR: No child without a mum or a dad.', $e->getMessage());
        }
    }

    public function testChildCount()
    {
        $this->cache->method('get')->willReturn(['father'=> 1, 'mother' => 1]);
        $this->request->method('getPost')->willReturn('add_child');
        try {
            $familySimulator = $this->familySimulatorService->family($this->request);
            $this->assertEquals(1, $familySimulator->getChildren());
        }catch (FamilySimulatorException $e){
            $this->assertSame('ERROR: No child without a mum or a dad.', $e->getMessage());
        }
    }

    public function testDadCount()
    {
        $this->cache->method('get')->willReturn(['mother' => 1]);
        $this->request->method('getPost')->willReturn('add_dad');
        $familySimulator = $this->familySimulatorService->family($this->request);
        $this->assertEquals(1, $familySimulator->getFather());
    }

    public function testAdaptChildCountWithoutDad()
    {
        $this->cache->method('get')->willReturn(['mother' => 1]);
        $this->request->method('getPost')->willReturn('add_adapt_child');
        $familySimulator = $this->familySimulatorService->family($this->request);
        $this->assertEquals(1, $familySimulator->getChildren());
    }

}
