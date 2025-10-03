<?php namespace SMS\SSA\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;

/**
 * Model
 */
class Programme extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use Sluggable;

    /**
     * @var array dates to cast from the database.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'sms_ssa_programs';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title' => 'required',
        'slug' => 'required|unique:sms_ssa_programs' // Ensure slug is unique
    ];

    protected $slugs = [
        'slug' => 'title'
    ];

    public $attachOne = [
        'thumbnail' => \System\Models\File::class
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }
}
