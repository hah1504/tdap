<!DOCTYPE html>
<html>
      
    <style>
        table{
            font-size: 8px;
            text-align: center;  
            width: 100%;
        }

        table, tr, th , td {
            margin: 0px;
            border: 1px solid black;          
            border-collapse: collapse;
        }

        .tdapLogo{
            padding-top: 12px;
        }
    </style>
<head>
    <title>Attendance Report</title>
</head>
<body>

    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-3" style="float: left;">
                    <img src="dist/img/tdap.jpeg" class="tdapLogo" width="75px" height="75px" alt="TDAP">
                </div>
                
                <div class="col-md-9">
                    <h2 align="center">Attendance Report</h2>
                    <h4 align="center" style="margin-top:-20px!important">Trade Development Authority of Pakistan</h4>
                    <h5 align="center" style="margin-top:-20px!important">Report Date Range : {{$date_range}}</h5>
                    <h5 align="center" style="margin-top:-20px!important">Printed On : {{date('d,M Y h:i:s:A')}}</h5>
                </div>

            </div>

            <div class="row">

                <div class="col-md-12">
                
                    <div class="card">            
                        <div class="card-body p-0" align="center">
                            <table class="table" style="font-size: 8px;" align="center">
                                <thead>
                                    <tr>
                                        <th scope='col'>Name</th>
                                        <th scope='col'>Total Days</th>
                                        <th scope='col'>Holiday Days</th>
                                        <th scope='col'>PRESENT</th>
                                        <th scope='col'>OFF</th>
                                        <th scope='col'>LATE</th>
                                        <th scope='col'>ABSENT</th>
                                        <th scope='col'>Leaves</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>@php echo $ataData[$emp_id]['emp_name']; @endphp</td>
                                        <td>{{ $total_days }}</td>
                                        <td>{{ isset($holidayDays) ? $holidayDays : 0 }}</td>                                        
                                        <td>{{ isset($ataData[$emp_id]['presentDays']) ? $ataData[$emp_id]['presentDays'] : 0 }}</td>                                        
                                        <td>{{ isset($weedendDays) ? $weedendDays : 0 }}</td>                                        
                                        <td>{{ isset($ataData[$emp_id]['lateDays']) ? $ataData[$emp_id]['lateDays'] : 0 }}</td>                                        
                                        <td>{{ isset($ataData[$emp_id]['absentDays']) ? $ataData[$emp_id]['absentDays'] : 0 }}</td>                                        
                                        <td>{{ isset($leaveDays) ? $leaveDays : 0 }}</td>                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>                            
                    </div>
                </div>
            </div>  
            <div class="row" style="margin-top:20px">

                <div class="col-md-12">
                
                    <div class="card">              
                        
                        <div class="card-body p-0" align="center">
                            <table class="table" style="font-size: 8px;" align="center">
                                <thead>
                                    <tr>
                                        <th scope='col'>Date</th>
                                        <th scope='col'>IN</th>
                                        <th scope='col'>OUT</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    foreach($ataData[$emp_id]['ataData'] as $key =>$value){                                            
                                            echo "<tr>";
                                            echo "<td>".$key."</td>";
                                            echo "<td>".$value['in']."</td>";
                                            echo "<td>".$value['out']."</td>";
                                            echo "</tr>";
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
    
    
</body>
</html>