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

        .customWidth {
            width: 25px;
        }

        .tdapLogo{
            padding-top: 12px;
        }
    </style>
<head>
    <title>Attendance Single Summary Report</title>
</head>
<body>

    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-3" style="float: left;">
                    <img src="dist/img/tdap.jpeg" class="tdapLogo" width="75px" height="75px" alt="TDAP">
                </div>
                
                <div class="col-md-9">
                    <h2 align="center">Attendance Single Summary Report</h2>
                    <h4 align="center" style="margin-top:-20px!important">Trade Development Authority of Pakistan</h4>
                    <h5 align="center" style="margin-top:-20px!important">Report Date Range : {{$date_range}}</h5>
                    <h5 align="center" style="margin-top:-20px!important">Printed On : {{date('d,M Y h:i:s:A')}}</h5>
                </div>

            </div>

            <div class="row" >

                <div class="col-md-12">
                
                    <div class="card">              
                        
                        <div class="card-body p-0" align="center" style="margin-right:0px">
                            <table class="table" style="font-size: 7px;" align="center">
                                <thead>
                                    <tr>
                                        <th scope='col'>ID</th>
                                        <th scope='col' class="customWidth">NAME</th>
                                        <th scope='col'>IN / OUT</th>
                                        @php
                                            $startDate = explode(' - ',$date_range)[0];
                                            $endDate = explode(' - ',$date_range)[1];

                                            $begin = new DateTime($startDate);
                                            $end = new DateTime($endDate);
                                            $end = $end->modify('+1 day');

                                            $interval = DateInterval::createFromDateString('1 day');
                                            $period = new DatePeriod($begin, $interval, $end);
                                            foreach ($period as $dt) {
                                                echo "<th scope='col'>".$dt->format('d')."</th>";
                                                
                                            }
                                        @endphp
                                        <th scope='col'>LATE</th>
                                        <!-- <th scope='col'>PRST</th>
                                        <th scope='col'>OFF</th>
                                        <th scope='col'>HODY</th>
                                        <th scope='col'>ABST</th>
                                        <th scope='col'>Leave</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    foreach($ataData as $key =>$value){
                                        echo "<tr>";
                                            
                                            echo "<td>".$key."</td>";
                                            echo "<td class='customWidth'>".$value['emp_name']."</td>";
                                            echo "<td>In<br>Out</td>";
                                            
                                            foreach($value['ataData'] as $subkey =>$subvalue){
                                                $tempData = '';
                                                $tempData = $subvalue['in'] . '<br>'.$subvalue['out'];
                                                echo "<td>".$tempData."</td>";
                                            }
                                            echo "<td>".(isset($value['lateDays']) ? $value['lateDays'] : 0)."</td>";
                                           
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