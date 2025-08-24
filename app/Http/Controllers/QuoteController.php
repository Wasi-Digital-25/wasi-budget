<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quote;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteSentMail;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Quote::class);
        $quotes = Quote::where('company_id', $request->user()->company_id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('quotes.index', compact('quotes'));
    }

    public function create(): View
    {
        $this->authorize('create', Quote::class);
        return view('quotes.create');
    }

    public function store(StoreQuoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['company_id'] = $request->user()->company_id;
        $data['user_id'] = $request->user()->id;

        $client = Client::findOrFail($data['client_id']);
        $data['client_name'] = $client->name;
        $data['client_email'] = $client->email;
        $data['client_phone'] = $client->phone;
        $data['valid_until'] = $data['expires_at'];
        $items = $data['items'];
        unset($data['expires_at'], $data['items']);

        $quote = Quote::create($data);

        foreach ($items as $item) {
            $lineTotal = $item['qty'] * $item['unit_price_cents'] - ($item['discount_cents'] ?? 0);
            $quote->items()->create([
                'description' => $item['description'],
                'quantity' => $item['qty'],
                'unit_price_cents' => $item['unit_price_cents'],
                'discount_cents' => $item['discount_cents'] ?? 0,
                'line_total_cents' => $lineTotal,
            ]);
        }

        $quote->save();

        return redirect()->route('quotes.show', $quote);
    }

    public function show(Quote $quote): View
    {
        $this->authorize('view', $quote);
        return view('quotes.show', compact('quote'));
    }

    public function edit(Quote $quote): View
    {
        $this->authorize('update', $quote);
        return view('quotes.edit', compact('quote'));
    }

    public function update(UpdateQuoteRequest $request, Quote $quote): RedirectResponse
    {
        $this->authorize('update', $quote);
        $data = $request->validated();
        $data['valid_until'] = $data['expires_at'];
        $items = $data['items'];
        unset($data['expires_at'], $data['items']);

        $quote->update($data);

        $quote->items()->delete();
        foreach ($items as $item) {
            $lineTotal = $item['qty'] * $item['unit_price_cents'] - ($item['discount_cents'] ?? 0);
            $quote->items()->create([
                'description' => $item['description'],
                'quantity' => $item['qty'],
                'unit_price_cents' => $item['unit_price_cents'],
                'discount_cents' => $item['discount_cents'] ?? 0,
                'line_total_cents' => $lineTotal,
            ]);
        }
        $quote->save();

        return redirect()->route('quotes.show', $quote);
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $this->authorize('delete', $quote);
        $quote->delete();
        return redirect()->route('quotes.index');
    }

    public function send(Quote $quote): RedirectResponse
    {
        $this->authorize('send', $quote);
        $quote->status = 'sent';
        $quote->save();
        if ($quote->client_email) {
            Mail::to($quote->client_email)->queue(new QuoteSentMail($quote));
        }
        return redirect()->route('quotes.show', $quote);
    }

    public function accept(Quote $quote): RedirectResponse
    {
        $this->authorize('accept', $quote);
        $quote->status = 'accepted';
        $quote->save();
        return redirect()->route('quotes.show', $quote);
    }

    public function reject(Quote $quote): RedirectResponse
    {
        $this->authorize('reject', $quote);
        $quote->status = 'rejected';
        $quote->save();
        return redirect()->route('quotes.show', $quote);
    }

    public function pdf(Quote $quote)
    {
        $this->authorize('view', $quote);
        $pdf = Pdf::loadView('quotes.pdf', ['quote' => $quote]);
        return $pdf->download('quote-' . $quote->number . '.pdf');
    }
}
