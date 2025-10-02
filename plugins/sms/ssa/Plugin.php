<?php namespace SMS\SSA;

use SMS\SSA\Models\Testimonial;
use System\Classes\PluginBase;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        \RainLab\Pages\Classes\Page::extend(function($model) {
            $model->addDynamicMethod('getTestimonialsOptions', function() {
                return Testimonial::published()->get()->lists('name', 'id');
            });
        });
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
}
