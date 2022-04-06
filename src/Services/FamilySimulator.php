<?php

declare(strict_types=1);

namespace App\Services;

class FamilySimulator
{

    private int $count = 0;
    private int $foodExpense = 0;
    private int $father = 0;
    private int $mother = 0;
    private int $children = 0;
    private int $cats = 0;
    private int $dogs = 0;
    private int $goldFish = 0;

    public function set(?array $family = []): void
    {
        foreach ($family as $key => $value) {
            $this->$key = (int)$value;
        }
    }

    public function setFoodExpense(int $foodExpense): void
    {
        $this->foodExpense = $foodExpense;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function setFather(): void
    {
        $this->father = 1;
    }

    public function setMother(): void
    {
        $this->mother = 1;
    }

    public function setChildren(int $children): void
    {
        $this->children = $children;
    }

    public function setDogs(int $dogs): void
    {
        $this->dogs = $dogs;
    }

    public function setCats(int $cats): void
    {
        $this->cats = $cats;
    }

    public function setGoldFish(int $goldFish): void
    {
        $this->goldFish = $goldFish;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getFather(): int
    {
        return $this->father;
    }

    public function getMother(): int
    {
        return $this->mother;
    }

    public function getChildren(): int
    {
        return $this->children;
    }

    public function getCats(): int
    {
        return $this->cats;
    }

    public function getDogs(): int
    {
        return $this->dogs;
    }

    public function getGoldFish(): int
    {
        return $this->goldFish;
    }

    public function getFoodExpense(): int
    {
        return $this->foodExpense;
    }

}
