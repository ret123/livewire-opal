<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_type', 'company_name', 'company_number', 'email', 'first_name', 'last_name', 'country', 'phone', 'website', 'twitter', 'linkedin', 'facebook', 'organization_status', 'organization_description', 'company_registration', 'vat_registration', 'additional_doc'
    ];

    protected $casts = [
        'oraganization_status' => 'array',

    ];

    protected function organizationStatus(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }
}
