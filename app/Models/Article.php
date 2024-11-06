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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

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
            $q->orWhereDate('published_at', $date);
        });
    }

    /**
     * Scope to filter by category ID.
     */
    public function scopeFilterByCategory($query, $categoryIds)
    {
        return $query->when($categoryIds, function ($q) use ($categoryIds) {
            $q->orWhereIn('category_id', $categoryIds)->with('category:id,name');
        });
    }

    /**
     * Scope to filter by source.
     */
    public function scopeFilterBySource($query, $ids)
    {
        return $query->when($ids, function ($q) use ($ids) {
            $q->orWhereIn('source_id', $ids)->with('source:id,name');
        });
    }

    /**
     * Scope to filter by author.
     */
    public function scopeFilterByAuthor($query, $ids)
    {
        return $query->when($ids, function ($q) use ($ids) {
            $q->whereIn('author_id', $ids)->with('author:id,name');
        });
    }
}
