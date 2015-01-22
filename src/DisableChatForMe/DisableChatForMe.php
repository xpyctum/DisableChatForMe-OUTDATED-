<?php

namespace DisableChatForMe;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class DisableChatForMe extends PluginBase implements Listener{

    private $players = [];

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

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
                $this->chaton($sender);
                $sender->sendMessage("[DisableChat] You successfully enabled chat for yourself");
                break;
            }
            case "chatoff":{
                $this->chatoff($sender);
                $sender->sendMessage("[DisableChat] You successfully disabled chat for yourself");
                break;
            }
        }
    }

    public function ischatoff(Player $player){
        return in_array($player->getName(), $this->players);
    }

    public function chaton(Player $player){
        unset($this->players[$player->getName()]);
    }

    public function chatoff(Player $player){
        $this->players[] = $player->getName();
    }

    public function onChat(PlayerChatEvent $event){
        if($event->getPlayer() instanceof Player){
            foreach($this->getServer()->getOnlinePlayers() as $player){
                if($this->ischatoff($player)){
                    $this->getLogger()->debug($event->getMessage());
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