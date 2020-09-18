<?php

namespace Mailery\Template\Email\Provider;

use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Di\Container;
use Yiisoft\Factory\Factory;
use Mailery\Template\Email\Provider\EmailEditorConfigs;
use Mailery\Template\Email\Model\EmailEditorList;

final class EmailEditorServiceProvider extends ServiceProvider
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $factory = new Factory();
        $configs = $container->get(EmailEditorConfigs::class)->getConfigs();
        foreach ($configs as $alias => $config) {
            $container->set($alias, fn () => $factory->create($config));
        }

        $container->set(
            EmailEditorList::class,
            function () use($container, $configs) {
                $types = array_map(
                    function ($type) use($container) {
                        return $container->get($type);
                    },
                    array_keys($configs)
                );

                return new EmailEditorList($types);
            }
        );
    }
}
