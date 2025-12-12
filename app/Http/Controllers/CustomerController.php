<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FilteredCustomersExport;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        \Log::info('Inside CustomerController@index AJAX request', [
            'ajax' => $request->ajax(),
            'q' => $request->query('q'),
            'filter_field' => $request->query('filter_field'),
            'url' => $request->fullUrl(),
        ]);

        $allowedFields = [
            'software',
            'name',
            'email',
            'phone',
            'company_name',
            'address',
            'area',
            'city',
            'country',
            'post_code',
            'note',
            'source',
            'created_at',
        ];

        $query = Customer::query();

        // AJAX live search suggestions
        if ($request->ajax() && $request->filled('q')) {
            $search = $request->query('q');

            $searchableFields = [
                'Software' => 'software',
                'Name' => 'name',
                'Email' => 'email',
                'Phone' => 'phone',
                'Company' => 'company_name',
                'Address' => 'address',
                'Area' => 'area',
                'City' => 'city',
                'Country' => 'country',
                'Post Code' => 'post_code',
                'Note' => 'note',
                'Source' => 'source',
            ];

            $suggestions = [];

            foreach ($searchableFields as $label => $column) {
                $matches = Customer::select($column)
                    ->where($column, 'LIKE', "%{$search}%")
                    ->whereNotNull($column)
                    ->distinct()
                    ->limit(10)
                    ->pluck($column)
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                if (!empty($matches)) {
                    $suggestions[$label] = $matches;
                }
            }

            return response()->json($suggestions);
        }

        // AJAX dynamic filter values loading
        if ($request->ajax() && $request->filled('filter_field')) {
            $field = $request->query('filter_field');

            if (in_array($field, $allowedFields)) {
                $values = Customer::whereNotNull($field)
                    ->distinct()
                    ->orderBy($field)
                    ->pluck($field)
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                return response()->json($values);
            }

            return response()->json([], 400);
        }

        // Normal search
        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $fields = [
                    'software',
                    'name',
                    'email',
                    'phone',
                    'company_name',
                    'address',
                    'area',
                    'city',
                    'country',
                    'post_code',
                    'note',
                    'source'
                ];

                foreach ($fields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        // Advanced filter
        $filterField = $request->query('filter_field');
        $filterValue = $request->query('filter_value');

        if ($filterField && $filterValue) {
            if (!in_array($filterField, $allowedFields)) {
                abort(400, 'Invalid filter field');
            }
            $query->where($filterField, $filterValue);
        }

        // Sorting
        $sortColumn = $request->query('sort_by', 'created_at');
        $sortDirection = strtolower($request->query('sort_dir', 'desc'));

        if (in_array($sortColumn, $allowedFields) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->latest();
        }

        // Pagination
        $allContacts = $query->paginate(25)->withQueryString();

        return view('customers.index', compact('allContacts'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'software' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'area' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'post_code' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            'source' => 'nullable|string|max:255',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    public function filter(Request $request)
    {
        $field = $request->query('filter_field');
        $value = $request->query('filter_value');

        Log::info('CustomerController@filter called', [
            'field' => $field,
            'value' => $value,
            'url' => $request->fullUrl(),
        ]);

        $allowedFields = [
            'software',
            'name',
            'email',
            'phone',
            'company_name',
            'address',
            'area',
            'city',
            'country',
            'post_code',
            'note',
            'source',
        ];

        if (!empty($field) && !in_array($field, $allowedFields)) {
            abort(400, 'Invalid filter field');
        }

        $query = Customer::query();

        if (!empty($field) && !empty($value)) {
            $query->where($field, $value);
        }

        $contacts = $query->orderByDesc('created_at')
            ->paginate(25)
            ->appends($request->only(['filter_field', 'filter_value']));

        if (!view()->exists('customers.filter')) {
            Log::error('View [customers.filter] not found.');
            abort(500, 'View [customers.filter] is missing.');
        }

        $noDataMessage = $contacts->isEmpty() ? 'Sorry, no contacts found for this filter.' : null;

        return view('customers.filter', [
            'allContacts' => $contacts,
            'noDataMessage' => $noDataMessage,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $contactId = $request->query('contact_id');

        if ($contactId) {
            // Export single customer
            $customer = Customer::findOrFail($contactId);
            $contacts = collect([$customer]); // Wrap single model in collection
        } else {
            // Export filtered list
            $contacts = $this->getFilteredData($request);
        }

        $pdf = PDF::loadView('customers.export_pdf', [
            'contacts' => $contacts,
        ]);

        return $pdf->stream('customers_filtered.pdf');
    }

    public function exportExcel(Request $request)
    {
        $contacts = $this->getFilteredData($request);

        $filename = 'customers_filtered_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'Software',
            'Name',
            'Email',
            'Phone',
            'Company',
            'Address',
            'Area',
            'City',
            'Country',
            'Post Code',
            'Note',
            'Source',
            'Created At',
        ];

        $callback = function () use ($contacts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // CSV headers

            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->software,
                    $contact->name,
                    $contact->email,
                    $contact->phone,
                    $contact->company_name,
                    $contact->address,
                    $contact->area,
                    $contact->city,
                    $contact->country,
                    $contact->post_code,
                    $contact->note,
                    $contact->source,
                    $contact->created_at?->format('d M Y, h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function getFilteredData(Request $request)
    {
        $allowedFields = [
            'software',
            'name',
            'email',
            'phone',
            'company_name',
            'address',
            'area',
            'city',
            'country',
            'post_code',
            'note',
            'source',
            'created_at',
        ];

        $query = Customer::query();

        // Filtering
        $field = $request->query('filter_field');
        $value = $request->query('filter_value');

        if ($field && $value && in_array($field, $allowedFields)) {
            $query->where($field, $value);
        }

        // Sorting
        $sortColumn = $request->query('sort_by', 'created_at');
        $sortDirection = strtolower($request->query('sort_dir', 'desc'));

        if (in_array($sortColumn, $allowedFields) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->latest();
        }

        return $query->get(); // Return as collection for export
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
