<?php


namespace App\DTO;


use App\Services\FamilySimulator;

class FamilySimulatorFactory
{

    public static function createNew(FamilySimulator $familySimulator): array
    {
       return [
            'father' => $familySimulator->getFather(),
            'mother' => $familySimulator->getMother(),
            'cats' => $familySimulator->getCats(),
            'dogs' => $familySimulator->getDogs(),
            'children' => $familySimulator->getChildren(),
            'goldFish' => $familySimulator->getGoldFish(),
            'foodExpense' => $familySimulator->getFoodExpense(),
            'count' => $familySimulator->getCount(),
       ];
    }

}
