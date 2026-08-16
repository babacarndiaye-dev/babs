<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GalleryItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['gallery_category_id', 'title', 'type', 'file_path', 'video_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('media')->singleFile();
    }
}
