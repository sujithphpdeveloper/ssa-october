<?php namespace SMS\SSA\Models;

use Model;

/**
 * Model
 */
class FormSubmission extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    public $formTypes = [
        'contact' => 'Contact Us',
        'registration' => 'Registration'
    ];

    /**
     * @var array dates to cast from the database.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'sms_ssa_form_submissions';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $jsonable = ['data'];

    public function getTypeOptions()
    {
        return $this->formTypes;
    }
}
