<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */
namespace Panda\Foundation;

/**
 *  Panda Foundation Sesison Essence Interface
 *
 *  @subpackage Framework
 */
abstract class SupportServicesAbstract implements SupportServicesInterface
{
    /**
     *  @var array
     */ 
    protected $aliases = array();

    /**
     *  @var array
     */ 
    protected $provide = array();

    public function append($alias, $service = null)
    {
        $this->tpe_pair_iterator($alias, $service, function($alias, $service) {
            if (
                is_object($service)
            ) {
                $this->aliases[get_class($service)] = $alias; 
            }

            $this->aliases[$alias] = $alias;
            $this->provide[$alias] = $service;
        });

        return $this;
    }   

    public function get($invoke)
    {
        if (
            array_key_exists($invoke, $this->aliases)
        ) {
            return $this->provide[$this->aliases[$invoke]];
        }

        return null;
    }

    use TechnicalProviderExpansion;
}