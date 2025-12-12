<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Filtered Customers PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            margin: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
            font-size: 8px;
        }

        th,
        td {
            border: 0.5px solid #444;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        td.small {
            font-size: 7px;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <h2>Filtered Customer List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Software</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Address</th>
                <th>Area</th>
                <th>City</th>
                <th>Country</th>
                <th>Post Code</th>
                <th>Note</th>
                <th>Source</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $index => $contact)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $contact->software }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ $contact->company_name }}</td>
                    <td>{{ $contact->address }}</td>
                    <td>{{ $contact->area }}</td>
                    <td>{{ $contact->city }}</td>
                    <td>{{ $contact->country }}</td>
                    <td>{{ $contact->post_code }}</td>
                    <td class="small">{{ $contact->note }}</td>
                    <td class="small">{{ $contact->source }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
