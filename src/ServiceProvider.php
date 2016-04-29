<?php

namespace Brain\Striatum;

use ArrayObject;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Striatum Service Provider
 *
 * @package Brain\Striatum
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Thelonius' default services.
     *
     * @param Container $container A DI container implementing ArrayAccess and container-interop.
     */
    public function register( Container $container )
    {
        $container['striatum.hooks'] = function() {
            return new ArrayObject;
        };

        $container['striatum.frozen'] = function() {
            return new ArrayObject;
        };

        $container['striatum.bucket'] = $container->factory( function() {
            return new Bucket;
        } );

        $container['striatum.subject'] = $container->factory( function( $container ) {
            return new Subject( $container['striatum.bucket'] );
        } );

        $container['striatum.hook'] = $container->factory( function() {
            return new Hook;
        } );

        $container['striatum.manager'] = function( $container ) {
            return new SubjectsManager(
                $container['striatum.hooks'],
                $container['striatum.frozen'],
                $container['striatum.subject']
            );
        };

        $container['hooks.api'] = function( $container ) {
            return new API(
                $container['striatum.manager'],
                $container['striatum.hooks'],
                $container['striatum.hook']
            );
        };
    }
}
