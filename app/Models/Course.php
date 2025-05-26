<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'thumbnail',
        'about',
        'is_popular',
        'category_id'
    ];

    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function benefits(): HasMany {
        return $this->hasMany(CourseBenefit::class);
    }

    public function sections(): HasMany {
        return $this->hasMany(CourseSection::class);
    }

    public function students(): HasMany {
        return $this->hasMany(CourseStudent::class, 'course_id');
    }

    public function mentors(): HasMany {
        return $this->hasMany(CourseMentor::class, 'course_id');
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
