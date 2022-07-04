@props(['columns'])

<div class="overflow-auto">
    <table class="table text-gray-800 border-separate space-y-6 text-sm">
        <thead class="bg-gray-800 text-gray-500">
            <tr>
                @foreach ($columns as $name)
                    <th class="p-3 text-left">{{ $name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-xs">
            {{ $slot }}
        </tbody>
    </table>
</div>
<style>
    .table {
        border-spacing: 0 15px;
    }

    i {
        font-size: 1rem !important;
    }

    .table tr {
        border-radius: 20px;
    }

    tr td:nth-child(n+6),
    tr th:nth-child(n+6) {
        border-radius: 0 .625rem .625rem 0;
    }

    tr td:nth-child(1),
    tr th:nth-child(1) {
        border-radius: .625rem 0 0 .625rem;
    }

</style>
