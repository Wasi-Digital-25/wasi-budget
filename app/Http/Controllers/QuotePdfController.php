<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuotePdfController extends Controller
{
    use AuthorizesRequests;

    public function download(Quote $quote)
    {
        $this->authorize('view', $quote);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            abort(501, 'PDF not available in this environment');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotes.pdf', ['quote' => $quote]);
        return $pdf->download('quote-' . $quote->id . '.pdf');
    }
}
