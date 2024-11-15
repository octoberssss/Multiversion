<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace MultiVersion\network\proto\v486\packets;

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\SetTitlePacket;

class v486SetTitlePacket extends SetTitlePacket{
    public const NETWORK_ID = ProtocolInfo::SET_TITLE_PACKET;

    public const TYPE_CLEAR_TITLE = 0;
    public const TYPE_RESET_TITLE = 1;
    public const TYPE_SET_TITLE = 2;
    public const TYPE_SET_SUBTITLE = 3;
    public const TYPE_SET_ACTIONBAR_MESSAGE = 4;
    public const TYPE_SET_ANIMATION_TIMES = 5;
    public const TYPE_SET_TITLE_JSON = 6;
    public const TYPE_SET_SUBTITLE_JSON = 7;
    public const TYPE_SET_ACTIONBAR_MESSAGE_JSON = 8;

    public int $type;
    public string $text = "";
    public int $fadeInTime = 0;
    public int $stayTime = 0;
    public int $fadeOutTime = 0;
    public string $xuid = "";
    public string $platformOnlineId = "";

    public static function fromLatest(SetTitlePacket $pk): self{
        $npk = new self;
        $npk->type = $pk->type;
        $npk->text = $pk->text;
        $npk->fadeInTime = $pk->fadeInTime;
        $npk->stayTime = $pk->stayTime;
        $npk->fadeOutTime = $pk->fadeOutTime;
        $npk->xuid = $pk->xuid;
        $npk->platformOnlineId = $pk->platformOnlineId;
        return $npk;
    }

    protected function decodePayload(PacketSerializer $in) : void{
        $this->type = $in->getVarInt();
        $this->text = $in->getString();
        $this->fadeInTime = $in->getVarInt();
        $this->stayTime = $in->getVarInt();
        $this->fadeOutTime = $in->getVarInt();
        $this->xuid = $in->getString();
        $this->platformOnlineId = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out) : void{
        $out->putVarInt($this->type);
        $out->putString($this->text);
        $out->putVarInt($this->fadeInTime);
        $out->putVarInt($this->stayTime);
        $out->putVarInt($this->fadeOutTime);
        $out->putString($this->xuid);
        $out->putString($this->platformOnlineId);
    }

    private static function type(int $type) : self{
        $result = new self;
        $result->type = $type;
        return $result;
    }

    private static function text(int $type, string $text) : self{
        $result = self::type($type);
        $result->text = $text;
        return $result;
    }

    public static function title(string $text) : self{
        return self::text(self::TYPE_SET_TITLE, $text);
    }

    public static function subtitle(string $text) : self{
        return self::text(self::TYPE_SET_SUBTITLE, $text);
    }

    public static function actionBarMessage(string $text) : self{
        return self::text(self::TYPE_SET_ACTIONBAR_MESSAGE, $text);
    }

    public static function clearTitle() : self{
        return self::type(self::TYPE_CLEAR_TITLE);
    }

    public static function resetTitleOptions() : self{
        return self::type(self::TYPE_RESET_TITLE);
    }

    public static function setAnimationTimes(int $fadeIn, int $stay, int $fadeOut) : self{
        $result = self::type(self::TYPE_SET_ANIMATION_TIMES);
        $result->fadeInTime = $fadeIn;
        $result->stayTime = $stay;
        $result->fadeOutTime = $fadeOut;
        return $result;
    }
}