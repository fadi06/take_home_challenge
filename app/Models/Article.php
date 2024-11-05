<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    /**
     * Scope to filter by keyword in the title.
     */
    public function scopeSearchByKeyword($query, $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('title', 'LIKE', '%' . $keyword . '%');
        });
    }

    /**
     * Scope to filter by creation date.
     */
    public function scopeFilterByDate($query, $date)
    {
        return $query->when($date, function ($q) use ($date) {
            $q->whereDate('published_at', $date);
        });
    }

    /**
     * Scope to filter by category ID.
     */
    public function scopeFilterByCategory($query, $categoryId)
    {
        return $query->when($categoryId, function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    /**
     * Scope to filter by source.
     */
    public function scopeFilterBySource($query, $source)
    {
        return $query->when($source, function ($q) use ($source) {
            $q->where('source', 'LIKE', '%' . $source . '%');
        });
    }
}
