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

namespace CortexPE\DiscordWebhookAPI;

use JsonSerializable;

class Message implements JsonSerializable{
	
	/** @var array $data */
	protected array $data = [];

	/**
	 * @param string $content
	 * @return void
	 */
	public function setContent(string $content): void{
		$this->data["content"] = $content;
	}

	/**
	 * @return string|null
	 */
	public function getContent(): ?string{
		return $this->data["content"];
	}

	/**
	 * @return string|null
	 */
	public function getUsername(): ?string{
		return $this->data["username"];
	}

	/**
	 * @param string $username
	 * @return void
	 */
	public function setUsername(string $username): void{
		$this->data["username"] = $username;
	}

	/**
	 * @return string|null
	 */
	public function getAvatarURL(): ?string{
		return $this->data["avatar_url"];
	}

	/**
	 * @param string $avatarURL
	 * @return void
	 */
	public function setAvatarURL(string $avatarURL): void{
		$this->data["avatar_url"] = $avatarURL;
	}

	/**
	 * @param Embed $embed
	 * @return void
	 */
	public function addEmbed(Embed $embed): void{
		if(!empty(($arr = $embed->asArray()))){
			$this->data["embeds"][] = $arr;
		}
	}

	/**
	 * @param boolean $ttsEnabled
	 * @return void
	 */
	public function setTextToSpeech(bool $ttsEnabled): void{
		$this->data["tts"] = $ttsEnabled;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array{
		return $this->data;
	}
}
