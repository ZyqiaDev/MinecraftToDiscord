<?php

namespace MinecraftToDiscord;

use MinecraftToDiscord\discord\DiscordManager;
use MinecraftToDiscord\eventlistener\EventListener;
use pocketmine\plugin\PluginBase;

class MCToDisc extends PluginBase
{
	public $weebhook;
	public $pureperms;

	function onEnable(): void
	{
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->pureperms = $this->getServer()->getPluginManager()->getPlugin('PurePerms');
		$this->getLogger()->info("§o§bMinecraftToDiscord §8» §aEnabled");
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->weebhook = $this->getConfig()->get("weebhook");
		DiscordManager::postWebhook($this->weebhook, $this->getConfig()->get("OnEnable"), "");
	}

	function onDisable()
	{
		$this->reloadConfig();
		$this->getLogger()->info("§o§bMinecraftToDiscord §8» §cDisabled");
		DiscordManager::postWebhook($this->weebhook, $this->getConfig()->get("OnDisable"), "");
	}
}