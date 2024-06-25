<?php

/**
 *
 *  _____      __    _   ___ ___
 * |   \ \    / /__ /_\ | _ \_ _|
 * | |) \ \/\/ /___/ _ \|  _/| |
 * |___/ \_/\_/   /_/ \_\_| |___|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Written by @CortexPE <https://CortexPE.xyz>
 * Intended for use on SynicadeNetwork <https://synicade.com>
 */

declare(strict_types = 1);

namespace CortexPE\DiscordWebhookAPI\task;

use pocketmine\Server;

use pocketmine\scheduler\AsyncTask;

use pocketmine\thread\NonThreadSafeValue;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;

use function curl_close;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function in_array;
use function json_encode;

class DiscordWebhookSendTask extends AsyncTask{

    /** @var NonThreadSafeValue $webhook */
    protected NonThreadSafeValue $webhook;
    /** @var NonThreadSafeValue $message */
    protected NonThreadSafeValue $message;
    
    /**
     * @param Webhook $webhook
     * @param Message $message
     */
    public function __construct(Webhook $webhook, Message $message){
        $this->webhook = new NonThreadSafeValue($webhook);
        $this->message = new NonThreadSafeValue($message);
    }

    /**
     * @return void
     */
    public function onRun(): void{
        $ch = curl_init($this->webhook->deserialize()->getURL());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->message->deserialize()));
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $this->setResult([curl_exec($ch), curl_getinfo($ch, CURLINFO_RESPONSE_CODE)]);
        curl_close($ch);
    }

    /**
     * @return void
     */
    public function onCompletion(): void{
        $response = $this->getResult();
        if(!in_array($response[1], [200, 204])){
            Server::getInstance()->getLogger()->error("[DiscordWebhookAPI] Got error ({$response[1]}): " . $response[0]);
        }
    }
}