<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\BlockState;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\CustomerInn;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebUser;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\AddressRegionRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\AddressRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\AutoIncrementUidRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\BlockStateRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\CustomerInnRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\PasswordHashRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\TariffRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserJurStateRepository;
use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractCreationRepositoryInterface;
use App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers\ContractMapper;
use Doctrine\ORM\EntityManagerInterface;

class ContractCreationRepository implements ContractCreationRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface      $em,

        private AddressRepository           $addressRepo,
        private TariffRepository            $tariffRepo,
        private CustomerInnRepository       $customerInnRepo,
        private AutoIncrementUidRepository  $autoIncrementUidRepo,
        private PasswordHashRepository      $passwordHashRepo,
        private UserJurStateRepository      $userJurStateRepo,
        private BlockStateRepository        $blockStateRepo,
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
        $tariffTable = $this->tariffRepo->find(1); //TODO: возможно стоит вынести в DTO
        $jurStateTable = $this->userJurStateRepo->find(2);
        $blockStateTable = $this->blockStateRepo->findByCode('blocked');

        $tableUser = new User();
        $tableUser->setId($newId);
        $tableUser->setLogin($contractDto->getLogin());
        $tableUser->setPassword($passes['encryptHash']);
        $tableUser->setFullName($contractDto->getFullName());
        $tableUser->setPassport($contractDto->getPassport());
        $tableUser->setDistrict($addressTable->getDistrict()->getId());
        $tableUser->setAddress($addressTable);
        $tableUser->setPhoneExtra($contractDto->getPhoneExtra());                  // дополнительный телефон
        $tableUser->setJurState($jurStateTable);                                   // Всегда неактивированный, тк юрик
        $tableUser->setBlockState($blockStateTable);
        $tableUser->setBlockDate($now);
        $tableUser->setBill(0.0);
        $tableUser->setBonus(0.0);
        $tableUser->setBillAbs(0.0);
        $tableUser->setCredit(false);
        $tableUser->setCurrentBankAccount('');
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
