<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Support\Resource;

use Illuminate\Support\ServiceProvider;
use League\Fractal\ScopeFactoryInterface;
use League\Fractal\Serializer\Serializer;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Serializer\DataArraySerializer;

class ResourceServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register()
    {
        // register bindings \League\Fractal\ScopeFactoryInterface
        $this->app->bind(ScopeFactoryInterface::class, function ($app) {
            return null;
        });

        // register bindings \League\Fractal\Serializer\Serializer
        $this->app->bind(Serializer::class, function ($app) {
            return new DataArraySerializer;
        });

        // register bindings \League\Fractal\Manager
        $this->app->bind(FractalManager::class, function ($app) {
            $manager = new FractalManager($this->scopeFactory());
            $manager->setSerializer($this->serializer());
            return $manager;
        });
    }

    /**
     * fractal manager scope factory instance
     *
     * @return \League\Fractal\ScopeFactoryInterface|null
     */
    protected function scopeFactory()
    {
        return $this->app->make(ScopeFactoryInterface::class);
    }

    /**
     * retrieve instance of League\Fractal\Serializer\Serializer
     *
     * @return \League\Fractal\Serializer\Serializer
     */
    protected function serializer()
    {
        return $this->app->make(Serializer::class);
    }
}