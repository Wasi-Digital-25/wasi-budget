<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    protected $table = 'budget_quotes';
    protected $fillable = [
        'company_id','user_id','number','client_name','client_email','client_phone','currency',
        'subtotal_cents','discount_cents','tax_cents','total_cents',
        'status','valid_until','public_hash'
    ];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(QuoteItem::class, 'quote_id'); }
}
