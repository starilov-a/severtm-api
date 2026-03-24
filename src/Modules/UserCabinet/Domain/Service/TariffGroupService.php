<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\TariffGroup;
use App\Modules\UserCabinet\Domain\RepositoryInterface\TariffGroupRepositoryInterface;

class TariffGroupService
{
    public function __construct(
        protected TariffGroupRepositoryInterface    $tariffGroupRepo,
    ) {}
    public function getTariffGroupRegions(): array
    {
        return [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs',
            200 => 'yaroslavl_tariffs'
        ];
    }

    public function createTariffGroup(string $code, string $name, bool $userVisible = true): TariffGroup
    {
        $code = trim($code);
        if ($code === '')
            throw new BusinessException('Пустой код группы');

        $name = trim($name);
        if ($name === '')
            throw new BusinessException('Пустое название группы');

        $existing = $this->tariffGroupRepo->findOneBy(['code' => $code]);
        if ($existing)
            throw new BusinessException('Такой тариф уже существует');

        $group = new TariffGroup();
        $group->setCode($code);
        $group->setName($name);
        $group->setUserVisible((int)$userVisible);

        return $this->tariffGroupRepo->save($group);
    }

    public function linkTariffForGroup(Tariff $tariff, TariffGroup $tariffGroup): Tariff
    {
        if ($tariff->isInGroup($tariffGroup))
            throw new BusinessException('Тариф уже относится к этой группе');

        $this->tariffGroupRepo->linkTariffForGroup($tariff, $tariffGroup);

        $tariff->addGroup($tariffGroup);

        return $tariff;
    }
}
