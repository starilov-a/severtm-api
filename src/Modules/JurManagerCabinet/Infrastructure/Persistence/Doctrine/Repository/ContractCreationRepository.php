<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\AutoIncrementUid;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\CustomerInn;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebUser;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\AddressRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\AutoIncrementUidRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\CustomerInnRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\PasswordHashRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\TariffRepository;
use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\ContractStatus;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractCreationRepositoryInterface;
use App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers\ContractMapper;
use Doctrine\ORM\EntityManagerInterface;

class ContractCreationRepository implements ContractCreationRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,

        private readonly AddressRepository $addressRepo,
        private readonly TariffRepository $tariffRepo,
        private readonly CustomerInnRepository $customerInnRepo,
        private readonly AutoIncrementUidRepository $autoIncrementUidRepo,
        private readonly PasswordHashRepository $passwordHashRepo,
    ) {}

    public function create(CreateJurContractDto $contractDto): Contract
    {
        $newId = $this->autoIncrementUidRepo->getAndIncreaseNextUserId();
        $now = new \DateTimeImmutable();
        $periodEnd = $now->modify('first day of next month midnight');

        // Шифрование паролей
        $passes = $this->passwordHashRepo->generateHashes($contractDto->getPassword());

        $customerInn = $this->customerInnRepo->findOneBy(['inn' => $contractDto->getInn()]);
        if ($customerInn === null) {
            $customerInn = new CustomerInn($contractDto->getInn());
            $this->em->persist($customerInn);
            $this->em->flush();
        }

        $addressTable = $this->addressRepo->find($contractDto->getAddress()->getId());
        $tariffTable = $this->tariffRepo->find(1);

        $tableUser = new User();
        $tableUser->setId($newId);
        $tableUser->setLogin($contractDto->getLogin());
        $tableUser->setPassword($passes['encryptHash']);
        $tableUser->setFullName($contractDto->getFullName());
        $tableUser->setPassport($contractDto->getPassport());
        $tableUser->setDistrict($addressTable->getDistrict());
        $tableUser->setAddress($addressTable);
        $tableUser->setPhoneExtra($contractDto->getPhoneExtra());                  // дополнительный телефон
        $tableUser->setIsJuridical(2);                                    // Всегда неактивированный, тк юрик
        $tableUser->setBill(0.0);
        $tableUser->setBonus(0.0);
        $tableUser->setBillAbs(0.0);
        $tableUser->setCredit(false);
        $tableUser->setRegDate($now);
        $tableUser->setAbPstart($now);
        $tableUser->setAbPend($periodEnd);
        $tableUser->setAbLdiscount(0);
        $tableUser->setPrivLevel(0);
        $tableUser->setGroupId(0);
        $tableUser->setEmail($contractDto->getEmail());
        $tableUser->setCurrentTariff($tariffTable);
        $tableUser->setNextTariff($tariffTable);
        $tableUser->setBw('0');
        $tableUser->setCurrentBw('0');
        $tableUser->setComments('');
        $tableUser->setDeleted(false);
        $tableUser->setBirthdate(null);
        $tableUser->setCustomerInn($customerInn);

        $this->em->persist($tableUser);

        $webUser = new WebUser();
        $webUser->setUid($newId);
        $webUser->setUser($tableUser);
        $webUser->setEnabled(1);
        $webUser->setCreationTime($now);
        $webUser->setLastAtime($now);
        $webUser->setLastAip('0');
        $webUser->setLogin($contractDto->getLogin());
        $webUser->setPasswdHash($passes['md5Hash']);
        $webUser->setPasswdHashEncrypt($passes['aesEncryptHash']);
        $webUser->setName($contractDto->getName());
        $webUser->setEmail($contractDto->getEmail());
        $webUser->setPhone($contractDto->getPhone());
        $webUser->setComment('');
        $webUser->setHashType('md5');
        $webUser->setIsSendActivation(0);

        $this->em->persist($webUser);
        $this->em->flush();


        return ContractMapper::map($tableUser);
    }
}
