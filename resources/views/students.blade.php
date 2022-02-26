<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>MeTutor</title>
    <link rel="icon" href="./favicon.png" type="image/png" />
    <link rel="apple-touch-icon" href="./apple-touch-icon.png" type="image/png" />

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/default/css/style.css') }}" />
</head>

<body>
    <!-- Container -->
    <div class="container-fluid mt-3 p-3">
        <table>
            @php
                $counter = 1;
            @endphp
            @foreach ($users as $user)
                <tr>
                    <td><span class="fw-bold">(Record #{{ $counter }})</span>
                        {{ $user }}
                    </td>
                </tr>
                @php
                    $counter++;
                @endphp
            @endforeach
        </table>

    </div>



</body>

</html>
