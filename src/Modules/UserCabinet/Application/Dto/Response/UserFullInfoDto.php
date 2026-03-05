<?php

namespace App\Modules\UserCabinet\Application\Dto\Response;


use App\Modules\Common\Application\Dto\Dto;

class UserFullInfoDto extends Dto
{
    private UserDto $user;
    private WebUserDto $webUser;
    private AddressDto $address;


    function __construct(UserDto $user, WebUserDto $webUser, AddressDto $address) {
        $this->user = $user;
        $this->webUser = $webUser;
        $this->address = $address;
    }

    public function getUser(): UserDto
    {
        return $this->user;
    }

    public function getWebUser(): WebUserDto
    {
        return $this->webUser;
    }

    public function getAddress(): AddressDto
    {
        return $this->address;
    }

}
