<?php namespace SMS\SSA\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Support\Facades\Url;

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
        'name' => 'required',
        'slug' => 'required|unique:sms_ssa_programs' // Ensure slug is unique
    ];

    public $jsonable = ['data'];


    protected $slugs = [
        'slug' => 'name'
    ];

    public $attachOne = [
        'thumbnail' => \System\Models\File::class
    ];

    public $hasMany = [
        'tournaments' => Tournament::class
    ];
    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function getUrlAttribute()
    {
        return Url::to('programs/'.$this->slug);
    }

    public function getStatAttribute()
    {
        return isset($this->data['stat'])?$this->data['stat']:[];
    }
}
