<?php

namespace App\Modules\UserCabinet\Application\Dto\Response;


class WriteOffCollectionDto
{

    /**
     * @var WriteOffDto[]
     */
    private array $items = [];

    public function add(WriteOffDto $writeOff): void
    {
        $this->items[] = $writeOff;
    }

    /**
     * @return WriteOffDto[]
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
            fn(WriteOffDto $item) => $item->toArray(),
            $this->items
        );
    }
}
