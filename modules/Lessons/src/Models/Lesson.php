<?php
namespace Modules\Lessons\src\Models;

use Modules\Video\src\Models\Video;
use Modules\Courses\src\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Modules\Document\src\Models\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
    protected $table = 'lessons';
    protected $fillable = [
        'name',
        'slug',
        'video_id',
        'course_id',
        'document_id',
        'parent_id',
        'is_trial',
        'view',
        'position',
        'durations',
        'description'
    ];

    protected $with = [
        'video',
        'document'
    ];

    public function children() {
        return $this->hasMany(lesson::class, 'parent_id');
    }

    public function subLessons() {
        return $this->children()->orderBy('position', 'asc')->with('subLessons');
    }

    public function video() {
        return $this->belongsTo(
            Video::class, 
            'video_id',
            'id'
        );
    }

    public function document() {
        return $this->belongsTo(
            Document::class, 
            'document_id',
            'id'
        );
    }

    public function course() {
        return $this->belongsTo(
            Course::class, 
            'course_id',
            'id'
        );
    }
}
