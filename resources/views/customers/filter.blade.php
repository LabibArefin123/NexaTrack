@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 rounded-2xl shadow-md bg-white">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h1 class="h4 fw-bold text-dark mb-1">Customer Filter List</h1>
                <p class="text-muted small mb-0">Manage all your filtered customers in one place</p>
            </div>

            <div class="btn-group">
                <a href="{{ route('customers.export.pdf', request()->query()) }}" target="_blank"
                    class="btn btn-sm btn-danger d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    Export as PDF
                </a>
                <a href="{{ route('customers.export.excel', request()->query()) }}" target="_blank"
                    class="btn btn-sm btn-success d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-file-earmark-excel-fill"></i>
                    Export as Excel
                </a>
            </div>
        </div>

        {{-- Filtered Table --}}
        <div class="rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
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
                    ];

                    $sortColumn = request('sort_by', 'created_at');
                    $sortDirection = request('sort_dir', 'desc');

                    function sortUrl($column, $direction)
                    {
                        return route(
                            'customers.filter',
                            array_merge(request()->except('page'), [
                                'sort_by' => $column,
                                'sort_dir' => $direction,
                                'page' => 1,
                            ]),
                        );
                    }
                @endphp

                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800" style="table-layout: auto;">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider border border-gray-300">
                        <tr>
                            <th class="px-3 py-1 text-left border border-gray-300" style="width: 50px;">SL</th>

                            @foreach ($columns as $key => $label)
                                <th class="px-3 py-1 text-left border border-gray-300" style="min-width: 200px;">
                                    <div class="flex items-center gap-2 whitespace-nowrap">
                                        <span class="font-semibold">{{ $label }}</span>
                                        <span class="flex gap-1">
                                            <a href="{{ sortUrl($key, 'asc') }}" title="Sort {{ $label }} ascending"
                                                class="sort-arrow {{ $sortColumn === $key && $sortDirection === 'asc' ? 'text-gray-800 fw-bold' : 'text-gray-400' }}">
                                                ↑
                                            </a>
                                            <a href="{{ sortUrl($key, 'desc') }}"
                                                title="Sort {{ $label }} descending"
                                                class="sort-arrow {{ $sortColumn === $key && $sortDirection === 'desc' ? 'text-gray-800 fw-bold' : 'text-gray-400' }}">
                                                ↓
                                            </a>
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100 text-gray-800 text-left">
                        @forelse ($allContacts as $index => $contact)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 align-top">
                                <td class="px-3 py-1 border border-gray-300 align-top">
                                    {{ ($allContacts->currentPage() - 1) * $allContacts->perPage() + $index + 1 }}
                                </td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->software }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->name }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->email }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->phone }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->company_name }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->address }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->area }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->city }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->country }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->post_code }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->note }}</td>
                                <td class="px-3 py-1 border border-gray-300 align-top">{{ $contact->source }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 2 }}" class="px-4 py-6 text-center text-gray-500">
                                    No contacts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $allContacts->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .sort-arrow {
            font-size: 12px;
            line-height: 12px;
            cursor: pointer;
            user-select: none;
            transition: color 0.2s ease;
            text-decoration: none;
        }

        .sort-arrow:hover {
            color: #2563eb;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        /* Dynamic row height adjustments */
        table td,
        table th {
            vertical-align: top;
            white-space: normal;
            /* allow wrapping */
            word-break: break-word;
            /* break long words */
        }

        /* Optional: reduce padding to shrink row height */
        table td,
        table th {
            padding-top: 0.25rem;
            /* 4px */
            padding-bottom: 0.25rem;
            /* 4px */
            padding-left: 0.75rem;
            /* 12px */
            padding-right: 0.75rem;
            /* 12px */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/colresizable/colresizable.min.js"></script>

    <script>
        $(function() {
            const table = $("table");
            const storageKey = "customer_table_column_widths";

            // Load saved widths
            const savedWidths = localStorage.getItem(storageKey);
            let colWidths = savedWidths ? JSON.parse(savedWidths) : null;

            // Apply saved widths if available
            if (colWidths && colWidths.length === table.find("th").length) {
                table.find("th").each(function(index) {
                    $(this).css("width", colWidths[index]);
                });
            }

            table.colResizable({
                liveDrag: true,
                gripInnerHtml: "<div class='grip'></div>",
                draggingClass: "dragging",
                minWidth: 50,
                onResize: function() {
                    // Save widths after resizing stops
                    const widths = [];
                    table.find("th").each(function() {
                        widths.push($(this).css("width"));
                    });
                    localStorage.setItem(storageKey, JSON.stringify(widths));
                }
            });
        });
    </script>

    <style>
        /* Optional styling for drag grips */
        .grip {
            background-color: #ccc;
            width: 5px;
            cursor: col-resize;
            height: 100%;
        }

        .dragging {
            user-select: none;
        }
    </style>
@endpush
