@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 rounded-2xl shadow-md bg-white">
        {{-- Header --}}
        <div class="row align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div class="col-md-6">
                <h1 class="h4 fw-bold text-dark mb-1">Customer List</h1>
                <p class="text-muted small mb-0">Manage all your customers in one place</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <a href="{{ route('customers.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add Customer
                </a>
            </div>
        </div>

        {{-- Contact Table --}}
        <div class="rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider border border-gray-300">
                        <tr>
                            @php
                                $columns = [
                                    'software' => 'Software',
                                    'name' => 'Name',
                                    'email' => 'Email',
                                    'phone' => 'Phone',
                                    'company_name' => 'Company Name',
                                    'address' => 'Address',
                                    'area' => 'Area',
                                    'city' => 'City',
                                    'country' => 'Country',
                                    'post_code' => 'Post Code',
                                    'note' => 'Note',
                                    'source' => 'Source',
                                    'created_at' => 'Date & Time',
                                ];
                                $sortColumn = request()->get('sort_by', 'created_at');
                                $sortDirection = request()->get('sort_dir', 'desc');

                                function sortUrl($column, $direction)
                                {
                                    return route(
                                        'customers.index',
                                        array_merge(request()->except('page'), [
                                            'sort_by' => $column,
                                            'sort_dir' => $direction,
                                            'page' => 1,
                                        ]),
                                    );
                                }
                            @endphp

                            <th class="px-4 py-3 text-left border border-gray-300" style="width: 50px;">SL</th>

                            @foreach ($columns as $key => $label)
                                <th class="px-4 py-3 text-left border border-gray-300" style="min-width: 200px;">
                                    <div class="flex items-center gap-2 whitespace-nowrap">
                                        <span class="font-semibold">{{ $label }}</span>
                                        <span class="flex gap-1">
                                            <a href="{{ sortUrl($key, 'asc') }}" title="Sort {{ $label }} ascending"
                                                class="sort-arrow"
                                                style="{{ $sortColumn === $key && $sortDirection === 'asc' ? 'color:#4B5563; font-weight:700;' : 'color:#9CA3AF;' }}">
                                                ↑
                                            </a>
                                            <a href="{{ sortUrl($key, 'desc') }}"
                                                title="Sort {{ $label }} descending" class="sort-arrow"
                                                style="{{ $sortColumn === $key && $sortDirection === 'desc' ? 'color:#4B5563; font-weight:700;' : 'color:#9CA3AF;' }}">
                                                ↓
                                            </a>
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="bg-white divide-y divide-gray-100 text-gray-800 text-left">
                        @forelse ($allContacts as $index => $contact)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 text-sm border border-gray-300" style="width: 50px;">
                                    {{ ($allContacts->currentPage() - 1) * $allContacts->perPage() + $index + 1 }}
                                </td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->software }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->name }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->email }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->phone }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->company_name }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->address }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->area }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->city }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->country }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->post_code }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->note }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">{{ $contact->source }}</td>
                                <td class="px-4 py-3 text-sm border border-gray-300">
                                    {{ $contact->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" class="px-4 py-6 text-gray-500 text-center">
                                    No contacts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <style>
            .sort-arrow {
                font-size: 12px;
                line-height: 12px;
                padding: 0;
                cursor: pointer;
                user-select: none;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .sort-arrow:hover {
                color: #2563eb;
                /* Tailwind blue-600 */
            }
        </style>


        {{-- Pagination --}}
        <div class="mt-4">
            {{ $allContacts->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .sort-arrow {
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            user-select: none;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .sort-arrow:hover {
            color: #2563eb;
        }
    </style>
@endsection
