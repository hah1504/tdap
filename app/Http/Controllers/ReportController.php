<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holidays;
use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use PDF;
use Carbon\Carbon;
use App\Models\Leaves;

class ReportController extends Controller
{
    public function summaryReport(){
        return view('report.summaryReport');
    }
    
    public function searchSummaryReport(){
        
        ini_set('max_execution_time',-1);
        $requestData = $_POST;

        $startDate = explode(' - ',$requestData['date_range'])[0];
        $endDate = explode(' - ',$requestData['date_range'])[1];

        $empData = Employee::leftJoin('designations', 'employees.desgination', '=', 'designations.id')->where('status',1);
        
        if(isset($requestData['desgination']) && !empty($requestData['desgination'])){
            $empData->where('desgination',$requestData['desgination']);
        }
        $empData = $empData->orderBy('sort_no')->get();
        $hDays = Holidays::whereRaw("date >= '".$startDate."' and date <='".$endDate."'")->pluck('date')->toArray();

        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $ignore = [];
        array_push($ignore,6,0); // 6 and 0 are saturday and sunday

        $weedendDays = 0;
        $totalDays = 0;
        $holidayDays = 0;

        $reportData = [];
        $isWeekend = false;
        foreach ($period as $dt) {
            $isWeekend = false;
            $isHoliday = false;

            if(in_array($dt->format("w"), $ignore)){
                $weedendDays ++;
                $isWeekend = true;
            }
            if(in_array($dt->format("Y-m-d"), $hDays) && !$isWeekend){
                $holidayDays ++;
                $isHoliday = true;
            }
            $totalDays ++;

            foreach ($empData as $emp) {

                $presentDay = 0;
                $absentDay = 0;
                $lateDay = 0;
                $leaveDays = 0;

                $attData = Attendance::select(DB::raw('user_id,punch_type,CASE WHEN punch_type = 0 THEN min(punch) ELSE max(punch) END punch'))->where(DB::raw('DATE(punch)'),$dt->format("Y-m-d"))
                ->where(['user_id'=>$emp->attendance_machine_id])
                // ->where(['punch_type'=>0])//Attendance In                
                ->orderBy('punch','ASC')
                ->groupBy('user_id','punch_type')
                ->get();

                $lDays = Leaves::whereRaw("date >= '".$startDate."' and date <='".$endDate."' and emp_id = ".$emp->attendance_machine_id)->pluck('date')->toArray();    
                $reportData[$emp->attendance_machine_id]['leaveDays'] = count($lDays);
                
                if(!isset($reportData[$emp->attendance_machine_id]['emp_name']))
                    $reportData[$emp->attendance_machine_id]['emp_name'] = $emp->full_name ." <br> <b>".$emp->empDesignation."</b>";
                
                if(in_array($dt->format("Y-m-d"), $lDays)){
                    $l = Leaves::whereRaw("date = '".$dt->format("Y-m-d")."' and emp_id = ".$emp->attendance_machine_id)->first();
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = $l->l_type;
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $l->l_type;
                    continue;
                    
                }
                if($isHoliday){
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'H';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'H';
                    continue;
                }
                if($isWeekend){
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'W';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'W';
                    continue;
                }                    
                if(count($attData) > 0){
                    if(isset($reportData[$emp->attendance_machine_id]['presentDays'])){
                        $reportData[$emp->attendance_machine_id]['presentDays']++;
                    }else{
                        $reportData[$emp->attendance_machine_id]['presentDays'] = 1;
                    }
                    foreach ($attData as $data) {
                        if($data->punch_type == 0){
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] =  $punchTime->format('H:i');

                            
                            $lateTime = $punchTime->format('Y-m-d 09:30:00');
                            //dd($punchTime->diffInMinutes($lateTime));
                            //dd($punchTime->diffInMinutes($lateTime));
                            if($punchTime->gt($lateTime)){
                                if(!isset($reportData[$emp->attendance_machine_id]['lateDays']))
                                    //$reportData[$emp->attendance_machine_id]['lateDays'] = $lateTime;
                                    $reportData[$emp->attendance_machine_id]['lateDays'] = 1;
                                else                                
                                    $reportData[$emp->attendance_machine_id]['lateDays']++;
                            }
                          
                            // if($punchTime->gt($lateTime)){
                            //     if(!isset($reportData[$emp->attendance_machine_id]['ataData']['lateDays']))
                            //         $reportData[$emp->attendance_machine_id]['lateDays'] = 1;
                            //     else                                
                            //         $reportData[$emp->attendance_machine_id]['lateDays']++;
                            // }
                        }else{
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $punchTime->format('H:i');
                        }
                    }


                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'MC';
                    
                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'MC';
                    
                }else{

                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'A';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'A';

                    if(isset($reportData[$emp->attendance_machine_id]['absentDays'])){
                        $reportData[$emp->attendance_machine_id]['absentDays']++;
                    }else{
                        $reportData[$emp->attendance_machine_id]['absentDays'] = 1;
                    }

                    if($isWeekend)
                    $reportData[$emp->attendance_machine_id]['absentDays']--;
                }
      
                
            }


        }

        
        $ataData = $reportData;

        $data = [
            'total_days' => $totalDays,
            'date_range' => $requestData['date_range'],
            'ataData' => $ataData,
            'weedendDays' => $weedendDays,
            'holidayDays' => $holidayDays,
        ];

        // dd($data);

        $pdf = PDF::loadView('pdfreports.attendanceSummaryReport', $data);
        
        // return $pdf->download('attendanceSummaryReport.pdf');
        // return $pdf->stream();
        return $pdf->stream('download.pdf');
    }

    
    // new Late report -----------------


    public function singleSummaryReport(){
        return view('report.singleSummaryReport');
    }
    
    public function searchSingleSummaryReport(){
        
        ini_set('max_execution_time',-1);
        $requestData = $_POST;

        $startDate = explode(' - ',$requestData['date_range'])[0];
        $endDate = explode(' - ',$requestData['date_range'])[1];

        $empData = Employee::leftJoin('designations', 'employees.desgination', '=', 'designations.id')->where('status',1);
        
        if(isset($requestData['desgination']) && !empty($requestData['desgination'])){
            $empData->where('desgination',$requestData['desgination']);
        }
        $empData = $empData->orderBy('sort_no')->get();
        $hDays = Holidays::whereRaw("date >= '".$startDate."' and date <='".$endDate."'")->pluck('date')->toArray();

        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $ignore = [];
        array_push($ignore,6,0); // 6 and 0 are saturday and sunday

        $totalDays = 0;
 

        $reportData = [];

        foreach ($period as $dt) {
            

            
            $totalDays ++;

            foreach ($empData as $emp) {

                
                $lateDay = 0;
                

                $attData = Attendance::select(DB::raw('user_id,punch_type,CASE WHEN punch_type = 0 THEN min(punch) ELSE max(punch) END punch'))->where(DB::raw('DATE(punch)'),$dt->format("Y-m-d"))
                ->where(['user_id'=>$emp->attendance_machine_id])
                // ->where(['punch_type'=>0])//Attendance In                
                ->orderBy('punch','ASC')
                ->groupBy('user_id','punch_type')
                ->get();

                $lDays = Leaves::whereRaw("date >= '".$startDate."' and date <='".$endDate."' and emp_id = ".$emp->attendance_machine_id)->pluck('date')->toArray();    
                $reportData[$emp->attendance_machine_id]['leaveDays'] = count($lDays);
                
                if(!isset($reportData[$emp->attendance_machine_id]['emp_name']))
                    $reportData[$emp->attendance_machine_id]['emp_name'] = $emp->full_name ." <br> <b>".$emp->empDesignation."</b>";
                
                if(in_array($dt->format("Y-m-d"), $lDays)){
                    $l = Leaves::whereRaw("date = '".$dt->format("Y-m-d")."' and emp_id = ".$emp->attendance_machine_id)->first();
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = $l->l_type;
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $l->l_type;
                    continue;
                    
                }
                      
                if(count($attData) > 0){
                    
                    foreach ($attData as $data) {
                        if($data->punch_type == 0){
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] =  $punchTime->format('H:i');

                            
                            $lateTime = $punchTime->format('Y-m-d 09:30:00');
                            $punchTime->diffInMinutes($lateTime);
                            //dd($punchTime->diffInMinutes($lateTime));
                          
                            if($punchTime->gt($lateTime)){
                                if(!isset($reportData[$emp->attendance_machine_id]['ataData']['lateDays']))
                                    //$reportData[$emp->attendance_machine_id]['lateDays'] = 1;
                                    $reportData[$emp->attendance_machine_id]['lateDays'] = $punchTime->diffInMinutes($lateTime);
                                else                                
                                    $reportData[$emp->attendance_machine_id]['lateDays']++;
                            }
                        }else{
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $punchTime->format('H:i');
                        }
                    }


                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'MC';
                    
                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'MC';
                    
                }else{

                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'A';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'A';

                    
                }
      
                
            }


        }

        
        $ataData = $reportData;

        $data = [
            'total_days' => $totalDays,
            'date_range' => $requestData['date_range'],
            'ataData' => $ataData,
            'lateDay' => $lateTime,
            
        ];

        // dd($data);

        $pdf = PDF::loadView('pdfreports.singleSummaryReport', $data);
        
        // return $pdf->download('attendanceSummaryReport.pdf');
        // return $pdf->stream();
        return $pdf->stream('download.pdf');
    }

    // new report ends

   
    public function singleAttendanceReport(){
        return view('report.singleAttendanceReport');
    }
    
    public function searchSingleAttendanceReport(){
        $requestData = $_POST;

        $startDate = explode(' - ',$requestData['date_range'])[0];
        $endDate = explode(' - ',$requestData['date_range'])[1];

        $empData = Employee::where('status',1)->where('attendance_machine_id',$requestData['emp_id'])->get();
        
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        $hDays = Holidays::whereRaw("date >= '".$startDate."' and date <='".$endDate."'")->pluck('date')->toArray();
        $lDays = Leaves::whereRaw("date >= '".$startDate."' and date <='".$endDate."' and emp_id = ".$requestData['emp_id'])->pluck('date')->toArray();

        $end = $end->modify('+1 day');
        
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $ignore = [];
        array_push($ignore,6,0); // 6 and 0 are saturday and sunday

        $weedendDays = 0;
        $totalDays = 0;
        $holidayDays = 0;

        $reportData = [];
        $isWeekend = false;
        $leaveDays = 0;
        $onleave = false;
        foreach ($period as $dt) {
            $isWeekend = false;
            $isHoliday = false;
            $onleave = false;
            if(in_array($dt->format("Y-m-d"), $lDays)){
                $leaveDays ++;
                $onleave = true;
            }
            if(in_array($dt->format("w"), $ignore)){
                $weedendDays ++;
                $isWeekend = true;
            }
            if(in_array($dt->format("Y-m-d"), $hDays)){
                $holidayDays ++;
                $isHoliday = true;
            }
            
            $totalDays ++;

            foreach ($empData as $emp) {

                $presentDay = 0;
                $absentDay = 0;
                $lateDay = 0;

                $attData = Attendance::select(DB::raw('user_id,punch_type,CASE WHEN punch_type = 0 THEN min(punch) ELSE max(punch) END punch'))->where(DB::raw('DATE(punch)'),$dt->format("Y-m-d"))
                ->where(['user_id'=>$emp->attendance_machine_id])
                // ->where(['punch_type'=>0])//Attendance In                
                ->orderBy('punch','ASC')
                ->groupBy('user_id','punch_type')
                ->get();
                
                
                if(!isset($reportData[$emp->attendance_machine_id]['emp_name']))
                    $reportData[$emp->attendance_machine_id]['emp_name'] = $emp->full_name ." <br> <b>".$emp->empDesignation."</b>";
                
                if($isHoliday){
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'H';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'H';
                    continue;
                }
                if($isWeekend){
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'W';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'W';
                    continue;
                }
                    
                if(count($attData) > 0){

                    

                    if(isset($reportData[$emp->attendance_machine_id]['presentDays'])){
                        $reportData[$emp->attendance_machine_id]['presentDays']++;
                    }else{
                        $reportData[$emp->attendance_machine_id]['presentDays'] = 1;
                    }
                    foreach ($attData as $data) {
                        if($data->punch_type == 0){
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] =  $punchTime->format('H:i');

                            
                            $lateTime = $punchTime->format('Y-m-d 09:30:00');
                            
                            if($punchTime->gt($lateTime)){
                                if(!isset($reportData[$emp->attendance_machine_id]['lateDays']))
                                    //$reportData[$emp->attendance_machine_id]['lateDays'] = $lateTime;
                                    $reportData[$emp->attendance_machine_id]['lateDays'] = 1;
                                else                                
                                    $reportData[$emp->attendance_machine_id]['lateDays']++;
                            }
                        }else{
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $punchTime->format('H:i');
                        }
                    }


                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'MC';
                    
                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'MC';
                    
                }else if($onleave){
                    $l = Leaves::whereRaw("date = '".$dt->format("Y-m-d")."' and emp_id = ".$requestData['emp_id'])->first();
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = $l->l_type;
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $l->l_type;
                }else{

                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'A';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'A';

                    if(isset($reportData[$emp->attendance_machine_id]['absentDays'])){
                        $reportData[$emp->attendance_machine_id]['absentDays']++;
                    }else{
                        $reportData[$emp->attendance_machine_id]['absentDays'] = 1;
                    }

                    if($isWeekend)
                    $reportData[$emp->attendance_machine_id]['absentDays']--;
                }
      
                
            }


        }

        $ataData = $reportData;
        //dd($ataData);

        $data = [
            'total_days' => $totalDays,
            'date_range' => $requestData['date_range'],
            'ataData' => $ataData,
            'weedendDays' => $weedendDays,
            'holidayDays' => $holidayDays,
            'leaveDays' => $leaveDays,
        
            'emp_id' => $requestData['emp_id'],
        ];

        // dd($data);

        $pdf = PDF::loadView('pdfreports.singleattendanceReport', $data);
        
        return $pdf->stream('download.pdf');
    }

    // daily attendance report

    public function dailyAttendanceReport(){
        return view('report.dailyAttendanceReport');
    }
    
    public function searchDailyReport(){
        
        ini_set('max_execution_time',-1);
        $requestData = $_POST;

        $startDate = explode(' - ',$requestData['date_range'])[0];
        $endDate = explode(' - ',$requestData['date_range'])[1];

        $empData = Employee::leftJoin('designations', 'employees.desgination', '=', 'designations.id')->where('status',1);
        
        if(isset($requestData['desgination']) && !empty($requestData['desgination'])){
            $empData->where('desgination',$requestData['desgination']);
        }
        $empData = $empData->orderBy('sort_no')->get();
        //$hDays = Holidays::whereRaw("date >= '".$startDate."' and date <='".$endDate."'")->pluck('date')->toArray();

        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $ignore = [];
        array_push($ignore,6,0); // 6 and 0 are saturday and sunday

        //$weedendDays = 0;
        $totalDays = 0;
        //$holidayDays = 0;

        $reportData = [];
        //$isWeekend = false;
        foreach ($period as $dt) {
            // $isWeekend = false;
            // $isHoliday = false;

            // if(in_array($dt->format("w"), $ignore)){
            //     $weedendDays ++;
            //     $isWeekend = true;
            // }
            // if(in_array($dt->format("Y-m-d"), $hDays) && !$isWeekend){
            //     $holidayDays ++;
            //     $isHoliday = true;
            // }
            // $totalDays ++;

            foreach ($empData as $emp) {

                //$presentDay = 0;
                $absentDay = 0;
                $lateDay = 0;
                $leaveDays = 0;

                $attData = Attendance::select(DB::raw('user_id,punch_type,CASE WHEN punch_type = 0 THEN min(punch) ELSE max(punch) END punch'))->where(DB::raw('DATE(punch)'),$dt->format("Y-m-d"))
                ->where(['user_id'=>$emp->attendance_machine_id])
                // ->where(['punch_type'=>0])//Attendance In                
                ->orderBy('punch','ASC')
                ->groupBy('user_id','punch_type')
                ->get();

                $lDays = Leaves::whereRaw("date >= '".$startDate."' and date <='".$endDate."' and emp_id = ".$emp->attendance_machine_id)->pluck('date')->toArray();    
                $reportData[$emp->attendance_machine_id]['leaveDays'] = count($lDays);
                
                if(!isset($reportData[$emp->attendance_machine_id]['emp_name']))
                    $reportData[$emp->attendance_machine_id]['emp_name'] = $emp->full_name ." <br> <b>".$emp->empDesignation."</b>";
                
                if(in_array($dt->format("Y-m-d"), $lDays)){
                    $l = Leaves::whereRaw("date = '".$dt->format("Y-m-d")."' and emp_id = ".$emp->attendance_machine_id)->first();
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = $l->l_type;
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $l->l_type;
                    continue;
                    
                }
                // if($isHoliday){
                //     $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'H';
                //     $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'H';
                //     continue;
                // }
                // if($isWeekend){
                //     $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'W';
                //     $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'W';
                //     continue;
                // }                    
                if(count($attData) > 0){
                    // if(isset($reportData[$emp->attendance_machine_id]['presentDays'])){
                    //     $reportData[$emp->attendance_machine_id]['presentDays']++;
                    // }else{
                    //     $reportData[$emp->attendance_machine_id]['presentDays'] = 1;
                    // }
                    foreach ($attData as $data) {
                        if($data->punch_type == 0){
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] =  $punchTime->format('H:i');

                            
                            $lateTime = $punchTime->format('Y-m-d 09:30:00');
                            $punchTime->diffInMinutes($lateTime);
                            //dd($punchTime->diffInMinutes($lateTime));
                          
                            if($punchTime->gt($lateTime)){
                                if(!isset($reportData[$emp->attendance_machine_id]['ataData']['lateDays']))
                                    //$reportData[$emp->attendance_machine_id]['lateDays'] = 1;
                                    $reportData[$emp->attendance_machine_id]['lateDays'] = $punchTime->diffInMinutes($lateTime);
                                else                                
                                    $reportData[$emp->attendance_machine_id]['lateDays']++;
                            }
                        // if($data->punch_type == 0){
                        //     $punchTime = Carbon::parse($data->punch);
                        //     $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] =  $punchTime->format('H:i');

                            
                        //     $lateTime = $punchTime->format('Y-m-d 09:30:00');
                        //     //dd($punchTime->diffInMinutes($lateTime));
                        //     //dd($punchTime->diffInMinutes($lateTime));
                          
                        //     if($punchTime->gt($lateTime)){
                        //         if(!isset($reportData[$emp->attendance_machine_id]['ataData']['lateDays']))
                        //             $reportData[$emp->attendance_machine_id]['lateDays'] = 1;
                        //         else                                
                        //             $reportData[$emp->attendance_machine_id]['lateDays']++;
                        //     }
                        }else{
                            $punchTime = Carbon::parse($data->punch);
                            $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = $punchTime->format('H:i');
                        }
                    }


                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'MC';
                    
                    if(!isset($reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out']))
                        $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'MC';
                    
                }else{

                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['in'] = 'A';
                    $reportData[$emp->attendance_machine_id]['ataData'][$dt->format("Y-m-d")]['out'] = 'A';

                    if(isset($reportData[$emp->attendance_machine_id]['absentDays'])){
                        $reportData[$emp->attendance_machine_id]['absentDays']++;
                    }else{
                        $reportData[$emp->attendance_machine_id]['absentDays'] = 1;
                    }

                    // if($isWeekend)
                    // $reportData[$emp->attendance_machine_id]['absentDays']--;
                }
      
                
            }


        }

        
        $ataData = $reportData;

        $data = [
            'total_days' => $totalDays,
            'date_range' => $requestData['date_range'],
            'ataData' => $ataData,
            // 'weedendDays' => $weedendDays,
            // 'holidayDays' => $holidayDays,
        ];

        // dd($data);

        $pdf = PDF::loadView('pdfreports.attendanceDailyReport', $data);
        
        // return $pdf->download('attendanceSummaryReport.pdf');
        // return $pdf->stream();
        return $pdf->stream('download.pdf');
    }

}
