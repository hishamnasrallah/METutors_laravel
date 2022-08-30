<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\TeacherAvailability;
use App\TeachingSpecification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class TeacherAvailabilityController extends Controller
{
    public function getAvailability($teacher_id)
    {
        $availabilites = TeacherAvailability::where('user_id', $teacher_id)->get();
        $startDate = Carbon::now()->format('Y-m-d');
        $start_date = Carbon::now()->format('d/m/Y');
        $endDate = Carbon::now()->addDays(30)->format('Y-m-d');
        $end_date = Carbon::now()->addDays(30)->format('d/m/Y');

        $academicClasses = AcademicClass::whereBetween('start_date', [$startDate, $endDate])
            ->where('teacher_id', $teacher_id)
            ->where('status', '!=', 'completed')
            ->get();

        $weekdays = [];
        foreach ($availabilites as  $avilability) {
            if (!in_array($avilability->day, $weekdays)) {
                array_push($weekdays, $avilability->day);
            }
        }
        if (count($academicClasses) == 0) {
            return response()->json([
                'status' => true,
                'startDate' => $start_date,
                'endDate' => $end_date,
                'weekdays' => $weekdays,
                'availabilites' => $availabilites,
            ]);
        }

        $weekdays = [];
        $weekdays_days = [];
        foreach ($availabilites as  $avilability) {
            if (!in_array($avilability->day, $weekdays)) {
                array_push($weekdays, $avilability->day);
                //to convert it in days
                if ($avilability->day == 1) {
                    array_push($weekdays_days, 'Sun');
                } else if ($avilability->day == 2) {
                    array_push($weekdays_days, 'Mon');
                } else if ($avilability->day == 3) {
                    array_push($weekdays_days, 'Tue');
                } else if ($avilability->day == 4) {
                    array_push($weekdays_days, 'Wed');
                } else if ($avilability->day == 5) {
                    array_push($weekdays_days, 'Thu');
                } else if ($avilability->day == 6) {
                    array_push($weekdays_days, 'Fri');
                } else {
                    array_push($weekdays_days, 'Sat');
                }
            }
        }

        $begin = new DateTime($startDate);
        $end   = new DateTime($endDate);

        // return $weekdays_day;
        $finalAvailabilities = [];
        $totalAvailability = new stdClass();
        $totalAvailabilities = [];
        $finalWeekDays = [];
        for ($date = $begin; $date <= $end; $date->modify('+1 day')) {

            foreach ($weekdays as $day) {

                if ($day == 1) {
                    $Day =  'Sun';
                } else if ($day == 2) {
                    $Day =   'Mon';
                } else if ($day == 3) {
                    $Day =   'Tue';
                } else if ($day == 4) {
                    $Day =   'Wed';
                } else if ($day == 5) {
                    $Day =   'Thu';
                } else if ($day == 6) {
                    $Day =   'Fri';
                } else {
                    $Day =   'Sat';
                }

                // if date is equal to availability weekday
                if ($date->format("D") == $Day) {
                    // echo $date->format("Y-m-d D") . ',';
                    $weekdayClasses = $academicClasses->where('day', $day);
                    $weekAvailabilites = $availabilites->where('day', $day);
                    //if weekday classees are greater than 0
                    foreach ($weekAvailabilites as $weekAvailability) {
                        $time_from = Carbon::parse($weekAvailability->time_from)->format('G:i');
                        $time_to = Carbon::parse($weekAvailability->time_to)->format('G:i');
                        $counter = 0;
                        //No Classses on weekday
                        if (count($weekdayClasses) == 0) {
                            array_push($totalAvailabilities, [
                                'availabilty_date' => $date->format('Y-m-d'),
                                // 'time_from' => Carbon::parse($weekAvailability->time_from)->format('h:i a'),
                                'time_from' => $weekAvailability->time_from,
                                // 'time_to' =>  Carbon::parse($weekAvailability->time_to)->format('h:i a'),
                                'time_to' => $weekAvailability->time_to,

                                'day' => $weekAvailability->day,
                            ]);
                        } else {
                            //If Classes Are Scheduled on Weeekdays
                            // foreach ($weekdayClasses as $weekdayClass) {

                            // $start_time = Carbon::parse($weekdayClass->start_time);
                            // $end_time = Carbon::parse($weekdayClass->end_time);

                            // return $time_to;
                            $check_classes = AcademicClass::whereBetween('start_date', [$startDate, $endDate])
                                ->where('teacher_id', $teacher_id)
                                ->where('status', '!=', 'completed')
                                ->where('day', $weekAvailability->day)
                                // ->where(function ($q) use ($time_from, $time_to) {
                                ->whereBetween('start_time', [$weekAvailability->time_from, $weekAvailability->time_to])
                                ->whereBetween('end_time', [$weekAvailability->time_from, $weekAvailability->time_to])
                                // })
                                ->get();

                            if (count($check_classes) == 0) {
                                array_push($totalAvailabilities, [
                                    'availabilty_date' => $date->format('Y-m-d'),
                                    // 'time_from' => Carbon::parse($weekAvailability->time_from)->format('h:i a'),
                                    'time_from' => $weekAvailability->time_from,
                                    // 'time_to' =>  Carbon::parse($weekAvailability->time_to)->format('h:i a'),
                                    'time_to' => $weekAvailability->time_to,

                                    'day' => $weekAvailability->day,
                                ]);
                            }
                            // }
                        }
                    }
                }
            }
        }
        //Finding unique available weekdays
        $grand_availabilities = [];
        foreach ($totalAvailabilities as $availability) {
            array_push($grand_availabilities, $availability['day']);
        }
        $grand_availabilities = array_unique($grand_availabilities);
        $availability_days = [];
        // converting unique days objects to array
        foreach ($grand_availabilities as $availability) {
            array_push($availability_days, $availability);
        }
        return response()->json([
            'status' => true,
            'startDate' => $start_date,
            'endDate' => $end_date,
            'weekdays' => $availability_days,
            'availabilites' => $totalAvailabilities,
        ]);

        // //Previous Function of Availabiitty starts
        // $availabilites = TeacherAvailability::where('user_id', $teacher_id)->get();
        // $startDate = Carbon::now()->format('Y-m-d');
        // $start_date = Carbon::now()->format('d/m/Y');
        // $endDate = Carbon::now()->addDays(30)->format('Y-m-d');
        // $end_date = Carbon::now()->addDays(30)->format('d/m/Y');

        // $academicClasses = AcademicClass::whereBetween('start_date', [$startDate, $endDate])->where('teacher_id', $teacher_id)->where('status', '!=', 'completed')->get();

        // $weekdays = [];
        // foreach ($availabilites as  $avilability) {
        //     if (!in_array($avilability->day, $weekdays)) {
        //         array_push($weekdays, $avilability->day);
        //     }
        // }

        // if (count($academicClasses) == 0) {
        //     return response()->json([
        //         'status' => true,
        //         'startDate' => $start_date,
        //         'endDate' => $end_date,
        //         'weekdays' => $weekdays,
        //         'availabilites' => $availabilites,
        //     ]);
        // }

        // $finalAvailabilities = [];
        // $finalWeekDays = [];
        // foreach ($weekdays as  $weekday) {
        //     $weekdayClasses = $academicClasses->where('day', $weekday);
        //     $weekAvailabilites = $availabilites->where('day', $weekday);

        //     foreach ($weekAvailabilites as $weekAvailability) {
        //         $time_from = Carbon::parse($weekAvailability->time_from)->format('G:i');
        //         $time_to = Carbon::parse($weekAvailability->time_to)->format('G:i');
        //         $counter = 0;
        //         foreach ($weekdayClasses as $weekdayClass) {

        //             $start_time = Carbon::parse($weekdayClass->start_time)->format('G:i');
        //             $end_time = Carbon::parse($weekdayClass->end_time)->format('G:i');
        //             if (($time_from >= $start_time) && ($time_from <= $end_time) || ($time_to >= $start_time) && ($time_to <= $end_time)) {
        //             } else {
        //                 $counter++;

        //                 if ($counter == count($weekdayClasses)) {
        //                     array_push($finalAvailabilities, $weekAvailability);
        //                     array_push($finalWeekDays, $weekAvailability->day);
        //                 }
        //             }
        //         }
        //     }
        // }
        // $unique_weekdays = array_unique($finalWeekDays);
        // $grand_availabilities = [];
        // foreach ($unique_weekdays as $weekday) {
        //     array_push($grand_availabilities, $weekday);
        // }


        // return response()->json([
        //     'status' => true,
        //     'startDate' => $start_date,
        //     'endDate' => $end_date,
        //     'weekdays' => $grand_availabilities,
        //     'availabilites' => $finalAvailabilities,
        // ]);
    }

    public function checkAvailability($teacher_id)
    {
        $availabilites = TeacherAvailability::where('user_id', $teacher_id)->get();
        $specification = TeachingSpecification::where('user_id', $teacher_id)->first();
        $startDate = Carbon::now()->format('Y-m-d');
        $start_date = Carbon::now()->format('d/m/Y');
        $endDate = $specification->availability_end_date;
        $end_date =  Carbon::parse($specification->availability_end_date)->format('d/m/Y');

        $academicClasses = AcademicClass::whereBetween('start_date', [$startDate, $endDate])->where('teacher_id', $teacher_id)->where('status', '!=', 'completed')->get();


        $weekdays = [];
        foreach ($availabilites as  $avilability) {
            if (!in_array($avilability->day, $weekdays)) {
                array_push($weekdays, $avilability->day);
            }
        }

        if (count($academicClasses) == 0) {
            return response()->json([
                'status' => true,
                'startDate' => $start_date,
                'endDate' => $end_date,
                'weekdays' => $weekdays,
                // 'availabilites' => $availabilites,
                'availabilites' => $availabilites,
            ]);
        }

        $finalAvailabilities = [];
        $finalWeekDays = [];
        foreach ($weekdays as  $weekday) {
            $weekdayClasses = $academicClasses->where('day', $weekday);
            $weekAvailabilites = $availabilites->where('day', $weekday);

            foreach ($weekAvailabilites as $weekAvailability) {
                $time_from = Carbon::parse($weekAvailability->time_from)->format('G:i');
                $time_to = Carbon::parse($weekAvailability->time_to)->format('G:i');
                $counter = 0;
                foreach ($weekdayClasses as $weekdayClass) {

                    $start_time = Carbon::parse($weekdayClass->start_time)->format('G:i');
                    $end_time = Carbon::parse($weekdayClass->end_time)->format('G:i');
                    if (($time_from >= $start_time) && ($time_from <= $end_time) || ($time_to >= $start_time) && ($time_to <= $end_time)) {
                    } else {
                        $counter++;

                        if ($counter == count($weekdayClasses)) {
                            array_push($finalAvailabilities, $weekAvailability);
                            array_push($finalWeekDays, $weekAvailability->day);
                        }
                    }
                }
            }
        }
        $unique_weekdays = array_unique($finalWeekDays);
        $grand_availabilities = [];
        foreach ($unique_weekdays as $weekday) {
            array_push($grand_availabilities, $weekday);
        }


        return response()->json([
            'status' => true,
            'startDate' => $start_date,
            'endDate' => $end_date,
            'weekdays' => $grand_availabilities,
            // 'availabilites' => $availabilites,
            'availabilites' => $finalAvailabilities,
        ]);
    }

    public function available_slots(Request $request)
    {
        $rules = [
            'class_id' => 'required',
            'date' =>  'required',
            'day' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }
        $class = AcademicClass::findOrFail($request->class_id);
        $date = Carbon::parse($request->date);
        $availabilites = TeacherAvailability::where('user_id', $class->teacher_id)->where('day', $request->day)->get();

        if ($availabilites->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => "Teacher is not Available at this day! Please check teacher availability first.",
            ], 400);
        }
        // Checking if teacher is booked
        $classes = AcademicClass::where('id', '!=', $class->id)
            ->where('teacher_id', $class->teacher_id)
            ->where('day', $request->day)
            ->where('start_date', $request->date)
            ->where('status', '!=', 'completed')
            ->get();

        if ($classes->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Available time slots!',
                'availabile_slots' => $availabilites
            ]);
        }
        $final_availabilities = [];
        foreach ($availabilites as $availability) {
            $time_from = Carbon::parse($availability->time_from)->format('G:i');
            $time_to = Carbon::parse($availability->time_to)->format('G:i');

            $counter  = 0;
            foreach ($classes as $academic_class) {
                $start_time = Carbon::parse($academic_class->start_time)->format('G:i');
                $end_time = Carbon::parse($academic_class->end_time)->format('G:i');
                // return $academic_class;
                if (($start_time >= $time_from) && ($end_time >= $time_from) && ($start_time <=  $time_to) && ($end_time <=  $time_to)) {
                } else {
                    $counter++;
                    if ($counter == count($classes)) {
                        array_push($final_availabilities, $availability);
                    }
                }
            }
        }




        return response()->json([
            'status' => true,
            'message' => 'Available time slots!',
            'availabile_slots' => $final_availabilities
        ]);
    }
}
