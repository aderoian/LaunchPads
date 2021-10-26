<?php

/*
 * Copyright 2021 Cosmic5173
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

declare(strict_types=1);

namespace Cosmic5173\LaunchPad;

use pocketmine\block\BlockFactory;
use pocketmine\block\StonePressurePlate;
use pocketmine\block\WoodenPressurePlate;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;

// Big thanks to jasonwynn10 for the help <3

class Main extends PluginBase {

    public const CONFIG_VERSION = 2;

    public static Main $instance;

    public static function getInstance(): Main{
        return self::$instance;
    }

    public function onEnable(): void{
        self::$instance = $this;
        $this->saveDefaultConfig();
        if($this->getConfig()->get("config-version") !== self::CONFIG_VERSION){
            rename($this->getDataFolder()."config.yml", $this->getDataFolder()."config-old.yml");
            $this->saveDefaultConfig();
            $this->getLogger()->alert("Your config file is out of date, and has been saved to 'config-old.yml'. A new config file has been generated.");
        }

        BlockFactory::getInstance()->register(new class() extends StonePressurePlate{
            public function hasEntityCollision(): bool
            {
                return true;
            }

            public function addVelocityToEntity(Entity $entity): ?Vector3
            {
                if($entity instanceof Living){
                    if(!in_array($entity->getWorld()->getFolderName(), Main::getInstance()->getConfig()->get("disabled-worlds"))){
                        $entity->knockBack(0, $entity->getDirectionVector()->getX(), $entity->getDirectionVector()->getZ(), Main::getInstance()->getConfig()->get("knockback-amount"));
                    }
                }
                return parent::addVelocityToEntity($entity);
            }
        }, true);

        BlockFactory::getInstance()->register(new class() extends WoodenPressurePlate {
            public function hasEntityCollision(): bool
            {
                return true;
            }

            public function addVelocityToEntity(Entity $entity): ?Vector3
            {
                if($entity instanceof Living){
                    if(!in_array($entity->getWorld()->getFolderName(), Main::getInstance()->getConfig()->get("disabled-worlds"))){
                        $entity->knockBack(0, $entity->getDirectionVector()->getX(), $entity->getDirectionVector()->getZ(), Main::getInstance()->getConfig()->get("knockback-amount"));
                    }
                }
                return parent::addVelocityToEntity($entity);
            }
        }, true);
    }
}