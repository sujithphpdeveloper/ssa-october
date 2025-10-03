<?php namespace SMS\SSA\Models;

use Model;

/**
 * Model
 */
class Tournament extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var array dates to cast from the database.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'sms_ssa_tournaments';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $belongsTo = [
        'programme' => [
            Programme::class,
            'key' => 'programme_id'
        ]
    ];

    public $attachOne = [
        'thumbnail' => \System\Models\File::class
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

}
