<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Movement Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="p-6 bg-gray-100">
    <div class="container p-6 mx-auto bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-700">Stock Movement Report</h2>
            <a href="/dashboard" class="px-6 py-2 text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                Dashboard
            </a>
        </div>

        <!-- Filters -->
        <form id="filterForm" class="flex flex-wrap items-center gap-4 mb-6">
            <input type="date" id="start_date" name="start_date" class="p-3 text-gray-600 border rounded-lg">
            <input type="date" id="end_date" name="end_date" class="p-3 text-gray-600 border rounded-lg">
            <input type="text" id="search" name="search" placeholder="Search..."
                class="p-3 text-gray-600 border rounded-lg w-60">
            <button type="submit" id="searchBtn"
                class="px-6 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                Search
            </button>
            <button type="button" id="clearBtn"
                class="px-6 py-2 text-white transition bg-gray-500 rounded-lg hover:bg-gray-600">
                Clear
            </button>
        </form>

        <!-- Stock Movement Table -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white border rounded-lg shadow-md">
                <thead>
                    <tr class="text-gray-700 bg-gray-300">
                        <th class="px-6 py-3 text-left">Date & Time</th>
                        <th class="px-6 py-3 text-left">Product</th>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Quantity</th>
                        <th class="px-6 py-3 text-left">Remarks</th>
                    </tr>
                </thead>
                <tbody id="stockTable">
                    @foreach ($data as $movement)
                        <tr class="transition-all border-b hover:bg-gray-100">
                            <td class="px-6 py-3">{{ date('Y-m-d H:i:s', strtotime($movement->created_at)) }}</td>
                            <td class="px-6 py-3">{{ $movement->product->name ?? 'N/A' }}</td>
                            <td
                                class="px-6 py-3 font-semibold {{ $movement->type == 'IN' ? 'text-green-600' : ($movement->type == 'OUT' ? 'text-red-600' : 'text-blue-600') }}">
                                {{ $movement->type }}
                            </td>
                            <td class="px-6 py-3">{{ $movement->difference }}</td>
                            <td class="px-6 py-3">{{ $movement->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p id="noDataMessage" class="hidden mt-4 text-center text-red-600">No Data Found</p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Prevent future dates
            let today = new Date().toISOString().split("T")[0];
            $("#start_date, #end_date").attr("max", today);

            // Handle search form submission
            $("#filterForm").on("submit", function(e) {
                e.preventDefault();

                let start_date = $("#start_date").val();
                let end_date = $("#end_date").val();
                let search = $("#search").val();

                $.ajax({
                    url: "/search_pro",
                    method: "GET",
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        search: search
                    },
                    success: function(response) {
                        let rows = "";

                        if (response.data.length > 0) {
                            $.each(response.data, function(index, movement) {
                                rows += `
                                <tr class="transition-all border-b hover:bg-gray-100">
                                    <td class="px-6 py-3">${movement.created_at}</td>
                                    <td class="px-6 py-3">${movement.product ? movement.product.name : 'N/A'}</td>
                                    <td class="px-6 py-3 font-semibold ${movement.type === 'IN' ? 'text-green-600' : (movement.type === 'OUT' ? 'text-red-600' : 'text-blue-600')}">
                                        ${movement.type}
                                    </td>
                                    <td class="px-6 py-3">${movement.difference}</td>
                                    <td class="px-6 py-3">${movement.remarks}</td>
                                </tr>`;
                            });
                            $("#stockTable").html(rows);
                            $("#noDataMessage").addClass(
                                "hidden"); // Hide "No Data Found" message
                        } else {
                            $("#stockTable").html(""); // Clear table
                            $("#noDataMessage").removeClass(
                                "hidden"); // Show "No Data Found" message
                        }
                    }
                });
            });

            // Clear button functionality
            $("#clearBtn").on("click", function() {
                $("#start_date").val("");
                $("#end_date").val("");
                $("#search").val("");
                $("#filterForm").submit(); // Refresh data with cleared filters
            });

            // Retain search field value on page reload
            let urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has("search")) {
                $("#search").val(urlParams.get("search"));
            }
            if (urlParams.has("start_date")) {
                $("#start_date").val(urlParams.get("start_date"));
            }
            if (urlParams.has("end_date")) {
                $("#end_date").val(urlParams.get("end_date"));
            }
        });
    </script>
</body>

</html>
