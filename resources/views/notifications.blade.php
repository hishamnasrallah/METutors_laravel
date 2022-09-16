<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="my-3 container d-flex justify-content-between align-items-center">
            <h1>Notifications</h1>
            <a href="{{ route('notifications') }}" type="button" class="btn btn-primary position-relative">
                Notifications
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    id="notiCounter">
                    {{ count($notifications) }}
                </span>
            </a>
        </div>
        <div class="col-6">
            @foreach ($notifications as $notification)
                {{-- <div class="d-flex justify-content-between align-items-center"> --}}
                <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                    <div>
                        {{ $notification->message }}
                        <span class="text-primary "
                            style="font-size: 12px">({{ $notification->created_at->diffForHumans() }})
                        </span>
                    </div>

                    <a href="{{ route('mark_as_read', $notification->id) }}" class="btn btn-success btn-sm">Mark as
                        read</a>
                </div>
                {{-- <div>

                    </div> --}}
                {{-- </div> --}}
            @endforeach

        </div>
    </div>
</body>

</html>
