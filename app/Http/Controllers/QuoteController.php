<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteSentMail;

class QuoteController extends Controller
{
    use AuthorizesRequests;
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
        $items = $data['items'];
        unset($data['items']);
        $data['company_id'] = $request->user()->company_id;
        $data['user_id'] = $request->user()->id;
        $quote = Quote::create($data);

        foreach ($items as $item) {
            $quote->items()->create([
                'name' => $item['description'],
                'description' => $item['description'],
                'quantity' => $item['qty'],
                'unit' => 'unit',
                'unit_price_cents' => (int) round($item['price'] * 100),
                'discount_cents' => 0,
                'tax_cents' => 0,
                'line_total_cents' => (int) round($item['qty'] * $item['price'] * 100),
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
        $items = $data['items'];
        unset($data['items']);

        $quote->fill($data);
        $quote->items()->delete();
        foreach ($items as $item) {
            $quote->items()->create([
                'name' => $item['description'],
                'description' => $item['description'],
                'quantity' => $item['qty'],
                'unit' => 'unit',
                'unit_price_cents' => (int) round($item['price'] * 100),
                'discount_cents' => 0,
                'tax_cents' => 0,
                'line_total_cents' => (int) round($item['qty'] * $item['price'] * 100),
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
        // placeholder response
        return response('PDF not implemented');
    }
}
