<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Mail\MyMailer;
use Illuminate\Support\Facades\Mail;


class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request): View
    {
        $query = Customer::query();

        if ($request->filled('term')) {
            $term = $request->term;
            $query->where(function($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                  ->orWhere('last_name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        return view('customers.index', [
            'customers' => $query->latest()->paginate(12)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        // The request is automatically validated by the CustomerRequest class
        Customer::create($request->validated());

        //envoi de l'email
        Mail::to($request->email)->send(new MyMailer('Welcome to PharmaStock', 'Dear ' . $request->first_name . ', welcome to our pharmacy!'));

        return redirect()->route('customers.index')
            ->with('success', 'Patient created successfully!');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Patient updated successfully!');
    }

    /**
     * Show the form for confirming deletion of the specified customer.
     */
    public function delete(Customer $customer): View
    {
        return view('customers.delete', compact('customer'));
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Patient deleted successfully!');
    }

    /**
     * Search for customers by name, email, phone or address.
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        
        // If search term is empty, redirect to index
        if (empty($term) || trim($term) === '') {
            return redirect()->route('customers.index');
        }
        
        // Simple search by name only
        $customers = Customer::with(['orders'])
            ->where(function($query) use ($term) {
                $query->where('first_name', 'like', "%{$term}%")
                      ->orWhere('last_name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%")
                      ->orWhere('phone', 'like', "%{$term}%")
                      ->orWhere('address', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get();

        // Return search results view
        return view('customers.search-results', compact('customers', 'term'));
    }
}