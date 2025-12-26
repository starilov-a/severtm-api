<?php

namespace App\Tests\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Entity\BlockState;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebUser;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class UserTest extends TestCase
{
    private User $user;
    private ReflectionClass $reflection;

    protected function setUp(): void
    {
        $this->user = new User();
        $this->reflection = new ReflectionClass(User::class);
    }

    /** Вспомогательный метод для установки приватных свойств */
    private function setPrivateProperty(string $name, mixed $value): void
    {
        $property = $this->reflection->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($this->user, $value);
        $property->setAccessible(false);
    }

    public function testGettersAndDefaultValues(): void
    {
        // Проверяем дефолтные значения (из атрибутов и свойств с инициализацией)
        $this->assertSame(0, $this->user->getId()); // id не установлен, но по умолчанию 0 (int)
        $this->assertSame('', $this->user->getLogin());
        $this->assertNull($this->user->getPassword());
        $this->assertSame('', $this->user->getFullName());
        $this->assertSame('', $this->user->getEmail());
        $this->assertSame(0, $this->user->isJuridical());
        $this->assertSame(0.0, $this->user->getBill());
        $this->assertSame(0.0, $this->user->getBonus());
        $this->assertSame(0.0, $this->user->getBillAbs());
        $this->assertFalse($this->user->isCredit());
        $this->assertSame('', $this->user->getBlockComments());
        $this->assertSame(0, $this->user->getPrivLevel());
        $this->assertSame(0, $this->user->getGroupId());
        $this->assertFalse($this->user->isDeleted());
    }

    public function testSettersAndGetters(): void
    {
        // Тестируем те поля, у которых есть публичные сеттеры
        $this->user->setLogin('testlogin');
        $this->assertSame('testlogin', $this->user->getLogin());

        $this->user->setFullName('Иванов Иван Иванович');
        $this->assertSame('Иванов Иван Иванович', $this->user->getFullName());

        $this->user->setEmail('test@example.com');
        $this->assertSame('test@example.com', $this->user->getEmail());

        // Пример установки связанных сущностей
        $blockState = $this->createMock(BlockState::class);
        $this->user->setBlockState($blockState);
        $this->assertSame($blockState, $this->user->getBlockState());

        $currentTariff = $this->createMock(Tariff::class);
        $this->user->setCurrentTariff($currentTariff);
        $this->assertSame($currentTariff, $this->user->getCurrentTariff());

        $nextTariff = $this->createMock(Tariff::class);
        $this->user->setNextTariff($nextTariff);
        $this->assertSame($nextTariff, $this->user->getNextTariff());

        $address = $this->createMock(\App\Modules\Common\Domain\Entity\Address::class);
        $this->user->setAddress($address);
        $this->assertSame($address, $this->user->getAddress());
    }

    public function testPrivatePropertiesViaReflection(): void
    {
        // Для полей без сеттеров — устанавливаем через Reflection и проверяем геттеры

        $this->setPrivateProperty('password', 'secret123');
        $this->assertSame('secret123', $this->user->getPassword());

        $this->setPrivateProperty('taxNumber', '1234567890');
        $this->assertSame('1234567890', $this->user->getTaxNumber());

        $birthdate = new \DateTimeImmutable('1990-01-01');
        $this->setPrivateProperty('birthdate', $birthdate);
        $this->assertSame($birthdate, $this->user->getBirthdate());

        $this->setPrivateProperty('phoneExtra', '+79991234567');
        $this->assertSame('+79991234567', $this->user->getPhoneExtra());

        $this->setPrivateProperty('flat', 15);
        $this->assertSame(15, $this->user->getFlat());

        $creditDeadline = new \DateTimeImmutable('2026-01-01');
        $this->setPrivateProperty('creditDeadline', $creditDeadline);
        $this->assertSame($creditDeadline, $this->user->getCreditDeadline());

        // regDate — timestamp, геттер возвращает DateTimeImmutable
        $this->setPrivateProperty('regDate', 1730000000); // какой-то timestamp
        $expectedDate = (new DateTimeImmutable())->setTimestamp(1730000000);
        $this->assertEquals($expectedDate, $this->user->getRegDate());
    }

    public function testGetRegionThroughAddress(): void
    {
        $region = $this->createMock(\App\Modules\Common\Domain\Entity\Region::class);
        $district = $this->createMock(\App\Modules\Common\Domain\Entity\District::class);
        $district->method('getRegion')->willReturn($region);

        $address = $this->createMock(\App\Modules\Common\Domain\Entity\Address::class);
        $address->method('getDistrict')->willReturn($district);

        $this->user->setAddress($address);

        $this->assertSame($region, $this->user->getRegion());
    }
}