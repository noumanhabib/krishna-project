<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function show()
    {
        return view('status_list');
    }

    public function add_status()
    {
        return view('add_status');
    }

    public function save_status(Request $request)
    {
        //dd($request->all());

        $check_exist_status = Status::select('status_name')->where('status_name', $request->status_name)->get()->toarray();
        if (count($check_exist_status) > 0) {
            $response['code']       = 502;
            $response['error']      = False;
            $response['message']    = "Status Already Exist";

            $res  = json_encode($response);

            echo $res;
            die;
        } else {


            $status_name = $request->status_name;

            $obj = new Status;
            $obj->status_name = $status_name;
            $obj->status = '1';
            $obj->save();

            $code             = 200;
            $error            = false;
            $message          = "Status Add Successfully";
        }
        $response['code']       = $code;
        $response['message']    = $message;
        $response['error']      = $error;
        return json_encode($response);
    }

    public function FetchStatusList(Request $request)
    {
        $status_list = Status::where('deleted_at', null)->orderBy('id', 'DESC')->get()->toarray();
        $arr = array("code" => 200, "message" => 'success', "data" => $status_list);
        return \Response::json($arr);
    }

    public function  UpdateStatus(Request $request)
    {
        //dd($request->all());
        $status_id = $request->id;
        $status        = $request->status;

        Status::where('id', $status_id)->update(['status' => $status]);
        $get_update_status = Status::select('status')->where('id', $status_id)->first();


        if ($get_update_status['status'] == 1) {
            $arr = array("code" => 200, "message" => "Status is Active Successfully", "data" => array());
            return \Response::json($arr);
        } elseif ($get_update_status['status'] == 0) {
            $arr = array("code" => 200, "message" => "Status is InActive Successfully", "data" => array());
            return \Response::json($arr);
        } else {
            $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
            return \Response::json($arr);
        }
    }

    public function EditStatus(Request $request)
    {
        $id = $request->id;

        $data = Status::where('id', $id)->first()
            ->toarray();
        $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
        );
        return \Response::json($arr);
    }

    public function Update_Status(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['status_name'] = $request->status_name;

        $check_exist_status = status::select('status_name')->where('status_name', $request->status_name)->get()->toarray();
        if (count($check_exist_status) > 0) {
            $response['code']       = 502;
            $response['error']      = False;
            $response['message']    = "Status Already Exist";

            $res  = json_encode($response);

            echo $res;
            die;
        } else {
            $data = Status::where('id', $id)->update($obj);
            $arr = array(
                "code" => 200,
                "message" => "Status has been Updated Successfully",
                "data" => array()
            );
            return \Response::json($arr);
        }
    }

    public function DeleteStatus(Request $request)
    {
        $id = $request->id;
        $delete = date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['status'] = '0';

        //$data = MasterBrandModel::where('id', $id)->delete();
        $data = status::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}