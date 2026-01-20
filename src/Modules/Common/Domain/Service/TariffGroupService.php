<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\TariffGroup;
use App\Modules\Common\Domain\Repository\TariffGroupRepository;
use App\Modules\Common\Infrastructure\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

class TariffGroupService
{
    public function __construct(
        protected EntityManagerInterface    $em,

        protected TariffGroupRepository    $tariffGroupRepo,
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

        return $this->save($group);
    }

    private function save(TariffGroup $group): TariffGroup
    {
        $this->em->persist($group);
        $this->em->flush();

        return $group;
    }
}