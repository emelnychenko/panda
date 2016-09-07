<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

define('PANDA_FRAMEWORK', 'v1.0');

include 'panda.procedures.php';

include 'Essence/ReadableAbstract.php';
include 'BootloaderInterface.php';
include 'Bootloader.php';

Panda\Bootloader::factory()->load('Panda\\', __DIR__);