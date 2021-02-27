<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Booking;
use App\BookingDetail;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    function add(Request $request){
        $b = new Booking();
        $b->customer_id = $request->customer_id;
        $b->location = $request->location;
        $b->date = $request->date;
        $b->save();
        
        foreach($request->details as $i){
            $d = new BookingDetail();
            $d->booking_id = $b->id;
            $d->service_id = $i["service_id"];
            $d->product_id = $i["product_id"];
            $d->product_detail_id = $i["product_detail_id"];
            $d->save();
        }
        return $this->responseUtf8($request);
    }


}
