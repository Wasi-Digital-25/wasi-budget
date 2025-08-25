<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    use HasFactory;

    protected $table = 'budget_quote_items';
    protected $fillable = [
        'quote_id','description','quantity','unit_price_cents','total_cents',
    ];

    public function quote(): BelongsTo { return $this->belongsTo(Quote::class, 'quote_id'); }
}
