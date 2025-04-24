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
    public function index(): View
    {
        return view('customers.index', [
            'customers' => Customer::paginate(10)
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
        Mail::to($request->email)->send(new MyMailer($request->first_name . ' ' . $request->last_name));

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
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
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        // The request is automatically validated by the CustomerRequest class
        $customer->update($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
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
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Search for customers by name, email, phone or address.
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        $page = $request->get('page', 1);

        $customers = Customer::where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->orWhere('address', 'like', "%{$term}%")
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'customers' => $customers->items(),
                'pagination' => [
                    'total' => $customers->total(),
                    'per_page' => $customers->perPage(),
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage()
                ]
            ]);
    }
    /**
     * Search for customers by name, email, phone or address.
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        $customers = Customer::where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->orWhere('address', 'like', "%{$term}%")
            ->paginate(10);

        return response()->json([
            'customers' => $customers->items(),
            'pagination' => [
                'total' => $customers->total(),
                'per_page' => $customers->perPage(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem(),
                'links' => $customers->linkCollection()->toArray()
            ]
        ]);
    }
}