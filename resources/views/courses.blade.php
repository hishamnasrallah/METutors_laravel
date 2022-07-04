<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Courses</title>
</head>

<body>
    <div class="mt-3 container d-flex justify-content-between align-items-center">
        <h1>Courses</h1>
        <a href="{{ route('notifications') }}" type="button" class="btn btn-primary position-relative">
            Notifications
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                id="notiCounter">
                {{ $notifications }}
            </span>
        </a>
    </div>

    <div class="container">
        <table class="table table-striped mt-5">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Code</th>
                    <th scope="col">Teacher</th>
                    <th scope="col">Student</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr>
                        <th scope="row">{{ $course->id }}</th>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->course_code }}</td>
                        <td>{{ $course->teacher_id }}</td>
                        <td>{{ $course->student_id }}</td>
                        <td>
                            <button {{-- href="{{ route('accept_course', $course->id) }}" --}} class="btn btn-primary btn-sm accept"
                                data-attr={{ $course->id }}>Accept</button>
                            <button {{-- href="{{ route('accept_course', $course->teacher_id) }}" --}} class="btn btn-danger btn-sm">Reject</button>
                        </td>

                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>





    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"
        integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g=="
        crossorigin="anonymous"></script>

    <script src="https://js.pusher.com/7.1/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('6c0c6f9d5928cda87e65', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('MeTutors');


        channel.bind('pusher:subscription_succeeded', function(members) {
            alert('successfully subscribed MeTutors!');
        });

        //Test BroadCast Without Listener
        channel.bind('test-broadcast', function(members) {
            alert('This is Test Queed Broadcast!');
        });

        //Binding Course Accepted Event
        channel.bind('course-accepted', function(data) {
            alert("event fired and binded Successfully!");
            console.log(data);
            if (data.userid == '1149') {
                alert('its your notification i am adding it!');
                counter = parseInt($('#notiCounter').text()) + 1;
                $('#notiCounter').text(counter);
            }
        });

        //Accept Course On Click Function
        $('.accept').click(function(e) {
            e.preventDefault();
            alert($(this).attr('data-attr'));
            courseId = $(this).attr('data-attr');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ URL::to('course/accept') }}" + '/' + courseId,
                type: 'get',
                data: {
                    "courseId": courseId,
                },

                success: function(response) {
                    console.log(channel);
                    alert('sucessfully accepted');
                }
            });
        });
    </script>


</body>

</html>
