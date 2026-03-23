<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'main_category',
        'subcategory',
        'experience_years',
        'description',
        'is_verified',
        'is_active',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'experience_years' => 'integer',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les services actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les services vérifiés
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope par catégorie principale
     */
    public function scopeInCategory($query, $category)
    {
        return $query->where('main_category', $category);
    }

    /**
     * Scope par sous-catégorie
     */
    public function scopeInSubcategory($query, $subcategory)
    {
        return $query->where('subcategory', $subcategory);
    }
}
