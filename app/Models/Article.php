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
    public function scopeFilterByCategory($query, $categoryIds)
    {
        dd($categoryIds);
        return $query->when($categoryIds, function ($q) use ($categoryIds) {
            $q->whereIn('category_id', $categoryIds);
        });
    }

    /**
     * Scope to filter by source.
     */
    public function scopeFilterBySource($query, $ids)
    {
        return $query->when($ids, function ($q) use ($ids) {
            $q->whereIn('source_id', $ids);
        });
    }

    /**
     * Scope to filter by author.
     */
    public function scopeFilterByAuthor($query, $ids)
    {
        return $query->when($ids, function ($q) use ($ids) {
            $q->whereIn('author_id', $ids);
        });
    }
}
