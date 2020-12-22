<?php

namespace MinecraftToDiscord\eventlistener;

use MinecraftToDiscord\discord\DiscordManager;
use MinecraftToDiscord\MCToDisc;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class EventListener implements Listener
{
	private $main;

	public function __construct(MCToDisc $main)
	{
		$this->main = $main;
	}

	public function onCommandUse(CommandEvent $ev): void
	{
		$p = $ev->getSender();
		if ($p instanceof Player) {
			if ($this->main->getConfig()->get("PurePermsExtension") == "Enabled") {
				DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}", "{rank}", "{command}"], [$p->getName(), $this->main->pureperms->getUserDataMgr()->getGroup($p)->getName(), $ev->getCommand()], $this->main->getConfig()->get("CommandUse")), "");
			} else {
				DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}", "{command}"], [$p->getName(), $ev->getCommand()], $this->main->getConfig()->get("CommandUse")), "");
			}
		}
	}

	public function onPlayerJoin(PlayerJoinEvent $ev): void
	{
		$p = $ev->getPlayer();
		if ($this->main->getConfig()->get("PurePermsExtension") == "Enabled") {
			DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}", "{rank}"], [$p->getName(), $this->main->pureperms->getUserDataMgr()->getGroup($ev->getPlayer())->getName()], $this->main->getConfig()->get("PlayerJoin")), "");
		} else {
			DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}"], [$p->getName()], $this->main->getConfig()->get("PlayerJoin")), "");
		}
	}

	public function onPlayerQuit(PlayerQuitEvent $ev): void
	{
		$p = $ev->getPlayer();
		if ($this->main->getConfig()->get("PurePermsExtension") == "Enabled") {
			DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}", "{rank}"], [$p->getName(), $this->main->pureperms->getUserDataMgr()->getGroup($ev->getPlayer())->getName()], $this->main->getConfig()->get("PlayerQuit")), "");
		} else {
			DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}"], [$p->getName()], $this->main->getConfig()->get("PlayerQuit")), "");
		}
	}
	public function onPlayerChat(PlayerChatEvent $ev): void
	{
		$p = $ev->getPlayer();
		$msg = $ev->getMessage();
		if ($this->main->getConfig()->get("PurePermsExtension") == "Enabled") {
			DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}", "{msg}", "{rank}"], [$p->getName(), $msg, $this->main->pureperms->getUserDataMgr()->getGroup($ev->getPlayer())->getName()], $this->main->getConfig()->get("PlayerChat")), "");
		}else{
			DiscordManager::postWebhook($this->main->weebhook, str_replace(["{player}", "{msg}"], [$p->getName(), $msg], $this->main->getConfig()->get("PlayerChat")), "");
		}
	}
}