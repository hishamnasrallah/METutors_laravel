<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\TeacherAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherAvailabilityController extends Controller
{
    public function getAvailability($teacher_id)
    {
        $availabilites = TeacherAvailability::where('user_id', $teacher_id)->get();
        $startDate = Carbon::now()->format('Y-m-d');
        $start_date = Carbon::now()->format('d/m/Y');
        $endDate = Carbon::now()->addDays(30)->format('Y-m-d');
        $end_date = Carbon::now()->addDays(30)->format('d/m/Y');

        $academicClasses = AcademicClass::whereBetween('start_date', [$startDate, $endDate])->where('teacher_id', $teacher_id)->get();

        $weekdays = [];
        foreach ($availabilites as  $avilability) {
            if (!in_array($avilability->day, $weekdays)) {
                array_push($weekdays, $avilability->day);
            }
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


        return response()->json([
            'status' => true,
            'startDate' => $start_date,
            'endDate' => $end_date,
            'weekdays' => array_unique($finalWeekDays),
            // 'availabilites' => $availabilites,
            'availabilites' => $finalAvailabilities,
        ]);
    }
}
