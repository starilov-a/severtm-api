<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\AbPeriod;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Device;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Discount;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\EnumParameter;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserGroupBelong;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserOwnDevice;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserParameter;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPhone;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebUser;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebUserIpAddr;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ZIpaddr;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\ContractReissueSettings;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractSettingsRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ContractSettingsRepository implements ContractSettingsRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    public function loadForReissue(Contract $contract): ContractReissueSettings
    {
        /** @var User $user */
        $user = $this->em->getRepository(User::class)->find($contract->getId());

        $parameters = [];
        foreach ($this->em->getRepository(UserParameter::class)->findBy(['user' => $user]) as $parameter) {
            $parameters[$parameter->getParameter()->getCode()] = $parameter->getValue();
        }

        $webIps = [];
        foreach ($this->em->getRepository(WebUserIpAddr::class)->findBy(['webUser' => $user->getWebUser()]) as $webIp) {
            $webIps[] = [
                'ip' => $webIp->getIp(),
                'persistent' => $webIp->isPersistent(),
            ];
        }

        $networkIps = [];
        foreach ($this->em->getRepository(ZIpaddr::class)->findBy(['user' => $user]) as $networkIp) {
            $networkIps[] = [
                'ip' => $networkIp->getIp(),
                'ipBin' => $networkIp->getIpBin(),
                'switchIp' => $networkIp->getSwitchIp(),
                'switchPort' => $networkIp->getSwitchPort(),
                'mac' => $networkIp->getMac(),
                'district' => $networkIp->getDistrict(),
                'fwOn' => $networkIp->isFwOn(),
                'ipExt' => $networkIp->getIpExt(),
                'gw' => $networkIp->getGw(),
                'bind' => $networkIp->isBind(),
            ];
        }

        $groupIds = [];
        foreach ($this->em->getRepository(UserGroupBelong::class)->findBy(['user' => $user]) as $group) {
            $groupIds[] = $group->getGroupId();
        }

        $serviceModes = [];
        $userModes = $this->em->getRepository(UserServMode::class)
            ->createQueryBuilder('usm')
            ->join('usm.finPeriod', 'fp')
            ->join('usm.mode', 'mode')
            ->join('mode.service', 'service')
            ->where('usm.user = :user')
            ->andWhere('usm.isActive = 1')
            ->andWhere('fp.isCurrent = 1')
            ->andWhere('service.strCode != :voip')
            ->setParameter('user', $user)
            ->setParameter('voip', 'voip')
            ->getQuery()
            ->getResult();

        foreach ($userModes as $userMode) {
            $serviceModes[] = [
                'serviceModeId' => $userMode->getMode()->getId(),
                'serviceCostId' => $userMode->getServCostId(),
                'units' => $userMode->getUnits(),
                'useCost' => $userMode->usesCost(),
                'deviceId' => $userMode->getDevice()?->getId(),
            ];
        }

        $devices = [];
        foreach ($this->em->getRepository(UserOwnDevice::class)->findBy(['user' => $user]) as $device) {
            $devices[] = [
                'deviceId' => $device->getDevice()->getId(),
                'masterUid' => $device->getMasterUid(),
                'comment' => $device->getDeviceComment(),
            ];
        }

        $phones = [];
        foreach ($this->em->getRepository(UserPhone::class)->findBy(['user' => $user]) as $phone) {
            $phones[] = [
                'phoneTypeId' => $phone->getPhoneTypeId(),
                'phone' => $phone->getPhone(),
            ];
        }

        $discount = $this->em->getRepository(Discount::class)->findOneBy(['user' => $user])?->getQuantity();
        $tariffId = $user->getCurrentTariff()?->getTid();

        return new ContractReissueSettings(
            $parameters,
            $webIps,
            $networkIps,
            $groupIds,
            $serviceModes,
            $devices,
            $phones,
            $discount,
            $tariffId,
        );
    }

    public function applyForReissue(Contract $contract, ContractReissueSettings $settings): void
    {
        /** @var User $newUser */
        $newUser = $this->em->getRepository(User::class)->find($contract->getId());
        /** @var WebUser|null $newWebUser */
        $newWebUser = $this->em->getRepository(WebUser::class)->find($contract->getId());

        foreach ($settings->getParameters() as $parameterCode => $parameterValue) {
            $parameter = new UserParameter();
            $parameter->setUser($newUser);
            $parameter->setParameter($this->em->getReference(EnumParameter::class, $parameterCode));
            $parameter->setValue((string)$parameterValue);
            $this->em->persist($parameter);
        }

        if ($newWebUser !== null) {
            foreach ($settings->getWebIps() as $webIpData) {
                $webIp = new WebUserIpAddr();
                $webIp->setWebUser($newWebUser);
                $webIp->setIp($webIpData['ip']);
                $webIp->setPersistent((bool)$webIpData['persistent']);
                $this->em->persist($webIp);
            }
        }

        foreach ($settings->getNetworkIps() as $networkIpData) {
            $networkIp = new ZIpaddr();
            $networkIp->setIp($networkIpData['ip']);
            $networkIp->setIpBin((int)$networkIpData['ipBin']);
            $networkIp->setUser($newUser);
            $networkIp->setSwitchIp($networkIpData['switchIp']);
            $networkIp->setSwitchPort((int)$networkIpData['switchPort']);
            $networkIp->setMac($networkIpData['mac']);
            $networkIp->setDistrict($networkIpData['district']);
            $networkIp->setFwOn((bool)$networkIpData['fwOn']);
            $networkIp->setIpExt($networkIpData['ipExt']);
            $networkIp->setGw($networkIpData['gw']);
            $networkIp->setBind((bool)$networkIpData['bind']);
            $this->em->persist($networkIp);
        }

        foreach ($settings->getGroupIds() as $groupId) {
            $groupBelong = new UserGroupBelong();
            $groupBelong->setUser($newUser);
            $groupBelong->setGroupId((int)$groupId);
            $this->em->persist($groupBelong);
        }

        foreach ($settings->getDevices() as $deviceData) {
            /** @var Device|null $device */
            $device = $this->em->getRepository(Device::class)->find($deviceData['deviceId']);
            if ($device === null) {
                continue;
            }

            $userDevice = new UserOwnDevice();
            $userDevice->setUser($newUser);
            $userDevice->setDevice($device);
            $userDevice->setTimeStamp(new \DateTimeImmutable());
            $userDevice->setMasterUid($deviceData['masterUid']);
            $userDevice->setDeviceComment($deviceData['comment']);
            $this->em->persist($userDevice);
        }

        foreach ($settings->getPhones() as $phoneData) {
            $userPhone = new UserPhone();
            $userPhone->setUser($newUser);
            $userPhone->setPhoneTypeId((string)$phoneData['phoneTypeId']);
            $userPhone->setPhone((string)$phoneData['phone']);
            $this->em->persist($userPhone);
        }

        if ($settings->getDiscount() !== null) {
            $discount = new Discount();
            $discount->setUser($newUser);
            $discount->setQuantity($settings->getDiscount());
            $this->em->persist($discount);
        }

        $currentFinPeriod = $this->em->getRepository(FinPeriod::class)->findOneBy(['isCurrent' => true]);
        foreach ($settings->getServiceModes() as $serviceModeData) {
            $mode = $this->em->getRepository(ProdServMode::class)->find($serviceModeData['serviceModeId']);
            if ($mode === null || $currentFinPeriod === null) {
                continue;
            }

            $userMode = new UserServMode();
            $userMode->setUser($newUser);
            $userMode->setFinPeriod($currentFinPeriod);
            $userMode->setMode($mode);
            $userMode->setServCost((int)$serviceModeData['serviceCostId']);
            $userMode->setUnits((int)$serviceModeData['units']);
            $userMode->setIsActive(true);
            $userMode->setUseCost((int)$serviceModeData['useCost']);

            if ($serviceModeData['deviceId'] !== null) {
                $device = $this->em->getRepository(Device::class)->find($serviceModeData['deviceId']);
                $userMode->setDevice($device);
            }

            $this->em->persist($userMode);
        }

        if ($settings->getTariffId() !== null) {
            $abPeriod = new AbPeriod();
            $abPeriod->setUser($newUser);
            $abPeriod->setStartedAtTs(time());
            $abPeriod->setEndedAtTs((new \DateTimeImmutable('first day of next month'))->getTimestamp());
            $abPeriod->setBalanceStart(0.0);
            $abPeriod->setBalanceEnd(0.0);
            $abPeriod->setTidStart($settings->getTariffId());
            $this->em->persist($abPeriod);
        }

        $this->em->flush();
    }
}
