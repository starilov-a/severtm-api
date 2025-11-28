<?php

namespace App\Modules\UserCabinet\Service\Dto\Response;


class ReplenishmentsCollectionDto
{

    /**
     * @var ReplenishmentDto[]
     */
    private array $items = [];

    public function add(ReplenishmentDto $replenishment): void
    {
        $this->items[] = $replenishment;
    }

    /**
     * @return ReplenishmentDto[]
     */
    public function getAll(): array
    {
        return $this->items;
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return array_map(
            fn(ReplenishmentDto $item) => $item->toArray(),
            $this->items
        );
    }
}
