<?php


namespace Eris11dib\StaffMode\util;


use pocketmine\Player;

class BanStatus{

    private $player;
    private $isBanned;
    private $until;
    private $reason;

    public function __construct(Player $player, bool $isBanned, int $until = -1, string $reason = ''){
        $this->player = $player;
        $this->isBanned = $isBanned;
        $this->until = $until;
        $this->reason = $reason;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    /**
     * @return int
     */
    public function getUntil(): int
    {
        return $this->until;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

}