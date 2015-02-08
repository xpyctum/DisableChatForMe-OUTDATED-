<?php

namespace DisableChatForMe;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class DisableChatForMe extends PluginBase implements Listener{

    private $players = array();

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("[DisableChatForMe] Successfully enabled!");
    }

    public function onDisable(){
        unset($this->players);
        $this->getLogger()->info("[DisableChatForMe] Successfully disabled!");
    }

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
        return in_array($player->getName(),$this->players);
    }

    public function chaton(Player $player){
        unset($this->players[$player->getName()]);
        $player->setNameTag($player->getDisplayName());
    }

    public function chatoff(Player $player){
        $this->players[$player->getName()] = $player->getName();
        $player->setNameTag($player->getDisplayName()." [MUTE]");
    }

    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        if($player instanceof Player){
            if(!$event->isCancelled()){
                $user = $player->getName();
                $message = $event->getMessage();
                $format = "<".$user."> ".$message;
                $this->getLogger()->info($format);
                foreach($this->getServer()->getOnlinePlayers() as $p){
                    if($this->ischatoff($p)){
                        //nothing :+1:
                    }else{
                        $p->sendMessage($format);
                    }
                }
                $event->setCancelled();
            }
        }
    }
}
