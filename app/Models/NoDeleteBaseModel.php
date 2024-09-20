<?php

namespace App\Models;

use App\Traits\EloquentRelationshipTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Carbon\Carbon;
use DateTimeInterface;

class NoDeleteBaseModel extends Model implements HasMedia
{

    use HasFactory, InteractsWithMedia;
    use EloquentRelationshipTrait;

    protected $appends = ['formatted_date_time', 'formatted_date', 'formatted_updated_date', 'photo'];
    protected $casts = ['id' => 'integer'];
    protected $hidden = ['media'];


    public function scopeActive($query)
    {
        return $query->where('is_active', '=', 1);
    }

    public function scopeInorder($query)
    {
        return $query->orderBy('in_order', 'asc');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }



    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('default')
            ->useFallbackUrl('' . url('') . '/images/default.png')
            ->useFallbackPath(public_path('/images/default.png'));
    }

    public function getPhotoAttribute()
    {
        return $this->getFirstMediaUrl('default');
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at != null ? $this->created_at->format('d M Y') : '';
    }

    public function getFormattedUpdatedDateAttribute()
    {
        return $this->updated_at != null ? $this->updated_at->format('d M Y') : '';
    }

    public function getFormattedDateTimeAttribute()
    {
        return $this->created_at != null ? $this->created_at->translatedFormat('d M Y \a\t h:i a') : '';
    }

    public function calculateDiffInHours($from, $to)
    {
        $from = Carbon::createFromFormat('H:s:i', $from);
        $to = Carbon::createFromFormat('H:s:i', $to);
        return $to->diffInHours($from) ?? 0;
    }

    public function carbonFromTime($time)
    {
        return Carbon::createFromFormat('H:s:i', $time);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public static function getPossibleEnumValues($column)
    {
        $instance = new static; // create an instance of the model to be able to get the table name
        $tableName = $instance->getTable();
        $enumValues = [];

        $raw = \DB::raw("SHOW COLUMNS FROM {$tableName} WHERE Field = '{$column}'");
        $raw = "SHOW COLUMNS FROM {$tableName} WHERE Field = '{$column}'";
        $columnInfo = collect(\DB::select($raw))->first();

        // Extract enum values from the column type definition
        if ($columnInfo) {
            preg_match('/^enum\((.*)\)$/', $columnInfo->Type, $matches);
            if (isset($matches[1])) {
                $enumValues = str_getcsv($matches[1], ',', "'");
            }
        }
        return $enumValues;

        // $type = \DB::select(\DB::raw('SHOW COLUMNS FROM ' . $instance->getTable() . ' WHERE Field = "' . $name . '"'))[0]->Type;
        // preg_match('/^enum\((.*)\)$/', $type, $matches);
        // $enum = array();
        // foreach (explode(',', $matches[1]) as $value) {
        //     $v = trim($value, "'");
        //     $enum[] = $v;
        // }
        // return $enum;
    }
}