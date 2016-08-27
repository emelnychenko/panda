<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

define('PANDA_FRAMEWORK', 'v1.0');

include 'panda.procedures.php';

include 'source/Foundation/EssenceReadableInterface.php';
include 'source/Foundation/EssenceReadableAbstract.php';
include 'source/Foundation/TechnicalProviderExpansion.php';
include 'source/Foundation/SingletonProviderInterface.php';
include 'source/Foundation/SingletonProviderExpansion.php';
include 'source/LadderInterface.php';
include 'source/Ladder.php';

Panda\Ladder::factory()->add(
    'Panda\\', __DIR__ . '/source'
);