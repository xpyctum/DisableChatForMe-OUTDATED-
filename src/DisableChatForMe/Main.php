<?php

namespace Main;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class DisableChat extends PluginBase implements Listener{
    /**
     * @var array
     */
    private $players = [];
    public function onEnable(){}

    public function onDisable(){}

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool|void
     */
    public function onCommand(CommandSender $sender, Command $command,$label,array $args){
        switch($command->getName()){
            case "chaton":{
                $this->chatoff($sender);
                $sender->sendMessage("[DisableChat] You successfully disabled chat for yourself");
                break;
            }
            case "chatoff":{
                $this->chaton($sender);
                $sender->sendMessage("[DisableChat] You successfully enabled chat for yourself");
                break;
            }
        }
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function ischatoff(Player $player){
        return in_array($player->getName(), $this->players);
    }

    /**
     * @param Player $player
     */
    public function chaton(Player $player){
        unset($this->players[$player->getName()]);
    }

    /**
     * @param Player $player
     */
    public function chatoff(Player $player){
        $this->players[] = $player->getName();
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event){
        if($event->getPlayer() instanceof Player){
            foreach($this->getServer()->getOnlinePlayers() as $player){
                if($this->ischatoff($player)){

                }else{
                    $message = $event->getMessage();
                    $user = $event->getPlayer()->getDisplayName();
                    $player->sendMessage("<".$user."> ".$message);
                }

            }
            $event->setCancelled(true);
        }
    }
}