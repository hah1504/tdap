<?php

namespace App\Http\Controllers;

use App\Models\att_machine;
use App\Models\Attendance;
use App\Http\Requests\Storeatt_machineRequest;
use App\Http\Requests\Updateatt_machineRequest;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\DB;

class AttMachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     
    public function index()

    {
        return view('machine');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeatt_machineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeatt_machineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\att_machine  $att_machine
     * @return \Illuminate\Http\Response
     */
    public function show(att_machine $att_machine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\att_machine  $att_machine
     * @return \Illuminate\Http\Response
     */
    public function edit(att_machine $att_machine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateatt_machineRequest  $request
     * @param  \App\Models\att_machine  $att_machine
     * @return \Illuminate\Http\Response
     */
    public function update(Updateatt_machineRequest $request, att_machine $att_machine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\att_machine  $att_machine
     * @return \Illuminate\Http\Response
     */
    public function destroy(att_machine $att_machine)
    {
        //
    }

    public function extractAtendance(){

        ini_set('max_execution_time',-1);
        
        $machine_id = $_GET['machine_id'];
        $machine = att_machine::findOrFail($machine_id);
        try{

            $zk = new ZKTeco($machine->ip, $machine->port);
            $zk->connect();
            $atts = $zk->getAttendance(); 

            $data = [];
            $count = 0;
            foreach ($atts as $key => $value) {
                $attRecord = Attendance::where(['uid'=>$value['uid'],'machine_id'=>$machine_id])->first();        
                if(!empty($attRecord)){
                    continue;
                }
                $data[$count]['uid'] = $value['uid'];
                $data[$count]['user_id'] = $value['id'];
                $data[$count]['punch'] = $value['timestamp'];
                $data[$count]['punch_type'] = $value['type'];
                $data[$count]['punch_state'] = $value['state'];
                $data[$count]['machine_id'] = $machine_id;

                $count = $count + 1;

            }

            Attendance::insert($data);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
        }
        
        
        return view('machine.extractAttendance',compact('atts'));
        
    }

    public function clearData(){

        $machine_id = $_GET['machine_id'];
        $machine = att_machine::findOrFail($machine_id);
        try{

            $zk = new ZKTeco($machine->ip, $machine->port);
            $zk->connect();
            $zk->clearAttendance(); 
            session()->flash('message', 'Attendance Machine Log Deleted Successfully.');
           $this->index();

           
        }catch(\Exception $e){
           
            dd($e);
        }
        return view('machine.extractAttendance',compact('atts'));
        
    }
}
