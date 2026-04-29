<!DOCTYPE html>
<html>

<head>
    <title>Invites Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #667eea;
            color: white;
            padding: 10px;
        }

        td {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-size: 12px;
        }

        .active { background: green; }
        .used { background: gray; }
        .expired { background: red; }

        .btn-back {
            display: inline-block;
            margin-bottom: 10px;
            text-decoration: none;
            background: #6f42c1;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="/" class="btn-back">← Back</a>

    <h2>Invite Dashboard (Live Search)</h2>

    <!-- SEARCH -->
    <input type="text" id="search" placeholder="Search invite code...">

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Status</th>
                <th>Uses</th>
            </tr>
        </thead>

        <tbody id="result">
            @foreach($invites as $invite)
                <tr>
                    <td>{{ $invite->id }}</td>
                    <td>{{ $invite->code }}</td>
                    <td>
                        <span class="badge {{ $invite->status ?? 'active' }}">
                            {{ $invite->status ?? 'active' }}
                        </span>
                    </td>
                    <td>{{ $invite->uses }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>

</div>

<!-- AJAX SCRIPT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('#search').on('keyup', function () {

    $.ajax({
        url: "/search-invites",
        type: "GET",
        data: {
            query: $(this).val()
        },

        success: function (data) {

            let html = "";

            if (data.length > 0) {

                data.forEach(function (item) {

                    html += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.code}</td>
                            <td>${item.status ?? 'active'}</td>
                            <td>${item.uses}</td>
                        </tr>
                    `;
                });

            } else {
                html = `<tr><td colspan="4">No invites found</td></tr>`;
            }

            $('#result').html(html);
        }
    });

});
</script>

</body>
</html>