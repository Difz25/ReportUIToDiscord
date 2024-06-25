<?php

namespace Difz25\ReportUIToDiscord;

use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;

use jojoe77777\FormAPI\CustomForm;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "report") {
            if ($sender instanceof Player) {
                $this->ReportForm($sender);
                return true;
            } else {
                $sender->sendMessage(TextFormat::RED . "Please use this command in-game.");
                return false;
            }
        }
        return false;
    }

    public function ReportForm(Player $player): void {
        $form = new CustomForm(function (Player $player, $data) {
            if ($data === null) {
                return false;
            }
                if($data[0] == null) {
                    $player->sendMessage("Please type the player");
                    return false;
                }
            if($data[1] == null) {
                $player->sendMessage("Please type the reason");
                return false;
            }
            $player->sendMessage("The report is sending");
            foreach ($this->getServer()->getOnlinePlayers() as $p) {
                if($p->hasPermission("report.view")) {
                    $p->sendMessage("New Report\nReporter: " . $player->getName() . "Player: " . $data[0] . "\nReason: " . $data[1]);
                }
            }
            $webhook = new Webhook("https://discordapp.com/api/webhooks/1248144177863196692/xwcX_5p-bTONRJPOFVVD5p1QLDONqPePpe1nLcrTriX81bvK8kLY20pN5SQpvANsDi8S");
            $msg = new Message();
            $embed = new Embed();
            $embed->setTitle("Report");
            $embed->addField("Player: ", $data[0]);
            $embed->addField("Reason: ", $data[1]);
            $embed->addField("Report By: ", $player->getName());
            $embed->setFooter("Ban 24H / Permanently?");
            $msg->addEmbed($embed);
            $webhook->send($msg);
            return true;
        });
            $form->setTitle("Report");
            $form->addLabel("Report");
            $form->addInput("Player:", "Hello");
            $form->addInput("Reason:", "Hacking");
            $player->sendForm($form);
    }
}