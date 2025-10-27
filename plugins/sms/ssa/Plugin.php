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

        if (\App::runningInBackend()) {
            $pluginManager = \System\Classes\PluginManager::instance();
            if ($pluginManager->hasPlugin('RainLab.Pages')) {
                \Event::listen('backend.form.extendFields', function ($widget) {
                    if ($widget->model instanceof \RainLab\Pages\Classes\Page) {
                        $widget->removeTab("rainlab.pages::lang.editor.content");
                    }
                });
            }
        }

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
        });
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'SMS\SSA\Components\SiteHelper' => 'siteHelper',
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'SSA Additional Settings',
                'description' => 'Manage common website settings like social links, contacts, and scripts.',
                'category'    => 'CATEGORY_MISC', // Can be an existing category or a new one
                'icon'        => 'oc-icon-cog', // Icon from Font Awesome
                'class'       => \SMS\SSA\Models\Settings::class,
                'order'       => 500,
                // Optional: Required permissions to access this page
                // 'permissions' => ['sms.ssa.access_settings'],
            ]
        ];
    }
}
