<?php namespace SMS\SSA\Models;

use Model;
use Illuminate\Support\Facades\DB;

/**
 * Model
 */
class Testimonial extends Model
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
    public $table = 'sms_ssa_testimonials';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function scopeSelectedTestimonials($query, $testimonials)
    {
        $idString = implode(",", $testimonials);
        return $query
            ->whereIn('id', $testimonials)
            ->orderByRaw("FIELD(id, {$idString})");
    }
}
