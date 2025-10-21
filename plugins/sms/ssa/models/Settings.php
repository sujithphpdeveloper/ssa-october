<?php namespace SMS\SSA\Models;

use Model;


class Settings extends Model
{
    public $implement = [
        \System\Behaviors\SettingsModel::class
    ];

    public $settingsCode = 'sms_ssa_settings';

    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {

    }
}
