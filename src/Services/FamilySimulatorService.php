<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\FamilySimulatorException;
use App\DTO\FamilySimulatorFactory;
use App\Helpers\ActionEnum;
use App\interfaces\CacheInterface;
use App\Request\Request;

class FamilySimulatorService
{
    private CacheInterface $cacheService;
    public function __construct(CacheInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function family(Request $request): FamilySimulator
    {
        $familySimulator = $this->getFamily();
        $sum = $familySimulator->getFoodExpense();
        $count = $familySimulator->getCount();
        switch ($request->getPost('control')) {
            case ActionEnum::ADD_DAD:
                if ($familySimulator->getFather()) {
                    throw new FamilySimulatorException(
                        'ERROR: The family already has a dad. (No support for modern families yet. :))'
                    );
                }
                $familySimulator->setFather();
                $count++;
                $sum += 200;
                break;
            case ActionEnum::ADD_MUM:
                if ($familySimulator->getMother()) {
                    throw new FamilySimulatorException(
                        'ERROR: The family already has a mum. (No support for modern families yet. :))'
                    );
                }
                $familySimulator->setMother();
                $count++;
                $sum += 200;
                break;
            case ActionEnum::ADD_CHILD:
                if (!$familySimulator->getMother() || !$familySimulator->getFather()) {
                    throw new FamilySimulatorException(
                        'ERROR: No child without a mum or a dad.'
                    );
                }
                $children = $familySimulator->getChildren();
                $familySimulator->setChildren(++$children);
                $count++;
                if ($familySimulator->getChildren() > 2) $sum += 100;
                else $sum += 150;
                break;
            case ActionEnum::ADD_ADAPT_CHILD:
                if (!$familySimulator->getMother()) {
                    throw new FamilySimulatorException(
                        'ERROR: No adapt child without a mum.'
                    );
                }
                $children = $familySimulator->getChildren();
                $familySimulator->setChildren(++$children);
                $count++;
                if ($familySimulator->getChildren() > 2) $sum += 100;
                else $sum += 150;
                break;
            case ActionEnum::ADD_CAT:
                if (!$familySimulator->getMother() && !$familySimulator->getFather()) {
                    throw new FamilySimulatorException(
                        'ERROR: No child without a mum or a dad.'
                    );
                }
                $cats = $familySimulator->getCats();
                $familySimulator->setCats(++$cats);
                $count++;
                $sum += 10;
                break;
            case ActionEnum::ADD_DOG:
                if (!$familySimulator->getMother() && !$familySimulator->getFather()) {
                    throw new FamilySimulatorException(
                        'ERROR: No child without a mum or a dad.'
                    );
                }
                $dogs = $familySimulator->getDogs();
                $familySimulator->setDogs(++$dogs);
                $count++;
                $sum += 15;
                break;
            case ActionEnum::ADD_GOLDFISH:
                if (!$familySimulator->getMother() && !$familySimulator->getFather()) {
                    throw new FamilySimulatorException(
                        'ERROR: No child without a mum or a dad.'
                    );
                }
                $goldFish = $familySimulator->getGoldFish();
                $familySimulator->setGoldFish(++$goldFish);
                $count++;
                $sum += 2;
                break;
            default:
                // reset the family simulator
                $familySimulator = new FamilySimulator();
                $count = $sum = 0;
                break;
        }
        $familySimulator->setCount($count);
        $familySimulator->setFoodExpense($sum);
        $this->cacheService->set(
            'family_simulator',
            FamilySimulatorFactory::createNew($familySimulator),
            60 * 5
        );
        return $familySimulator;
    }

    public function getFamily(): FamilySimulator
    {
        $familyData = $this->cacheService->get('family_simulator') ?? [];
        $familySimulator = new FamilySimulator();
        $familySimulator->set($familyData);
        return $familySimulator;
    }

}
