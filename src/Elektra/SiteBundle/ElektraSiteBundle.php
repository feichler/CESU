<?php

namespace Elektra\SiteBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElektraSiteBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {

        parent::build($container);
    }
}