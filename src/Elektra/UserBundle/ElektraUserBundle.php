<?php

namespace Elektra\UserBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElektraUserBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {

        parent::build($container);
    }
}