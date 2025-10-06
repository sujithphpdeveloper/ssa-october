<?php namespace SMS\SSA;

use SMS\SSA\Models\FormSubmission;
use SMS\SSA\Models\Testimonial;
use SMS\SSA\Models\Media;
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

            $model->addDynamicMethod('getPhotosOptions', function() {
                return Media::type('photo')->published()->get()->lists('name', 'id');
            });

            $model->addDynamicMethod('getVideosOptions', function() {
                return Media::type('video')->published()->get()->lists('name', 'id');
            });

            $model->addDynamicMethod('getFormTypeOptions', function() {
                $formSubmission = new FormSubmission;
                return $formSubmission->getTypeOptions();
            });

            $model->addDynamicMethod('onContact', function() {
                dd('test');
            });
        });
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'SMS\SSA\Components\FormSubmission' => 'formSubmissions'
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
}
