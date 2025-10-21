<?php namespace SMS\SSA\Models;

use Model;

/**
 * Model
 */
class Media extends Model
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
    public $table = 'sms_ssa_medias';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $attachOne = [
        'thumbnail' => \System\Models\File::class
    ];

    public $attachMany = [
        'gallery' => \System\Models\File::class
    ];

    public function getTypeOptions()
    {
        return [
            'photo' => 'Photo',
            'video' => 'Video'
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSelectedMedias($query, $medias)
    {
        $idString = implode(",", $medias);
        return $query
            ->whereIn('id', $medias)
            ->orderByRaw("FIELD(id, {$idString})");
    }
}
