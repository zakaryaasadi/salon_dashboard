<?php

namespace App\Http\Controllers;
use App\Booking;
use App\Product;
use App\Service;
use App\Customer;
use App\BookingDetail;
use App\ProductDetail;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    function get(){
        $all = Booking::orderBy('created_at', 'desc')->get();
        $today = Booking::whereDate('date', '=', date('Y-m-d'))->get()->count();
        $month = Booking::whereMonth('date', '=', date('m'))
                        ->whereYear('date', '=', date('Y'))->get()->count();  
        $year = Booking::whereYear('date', '=', date('Y'))->get()->count();

        $info_this_week = $this->getWeek(date('Y-m-d'));
        $info_last_week = $this->getWeek($this->getLastWeekDate(date("Y-m-d")));
        $info_year = $this->getYear();

        
        foreach($all as $i){
            $customer = Customer::find($i->customer_id);
            $i->name = $customer["full_name"];
            $i->phone = $customer["phone"];
            $i->email = $customer["email"];
        }
        return view('booking')->with('bookings', $all)
                ->with("today", $today)
                ->with("month", $month)
                ->with("year", $year)
                ->with("info_this_week", $info_this_week)
                ->with("info_last_week", $info_last_week)
                ->with("info_year", $info_year);
    }


    function view($id){
        $b = Booking::find($id);
        $customer = Customer::find($b->customer_id);
        $b->name = $customer["full_name"];
        $b->phone = $customer["phone"];
        $b->email = $customer["email"];
        $b->details = BookingDetail::where([
            'booking_id' => $b->id])->get();
        $min = 0;
        $max = 0;
        foreach($b->details as $i){
            $i->service_name = Service::find($i->service_id)["name"];
            $i->product_name = Product::find($i->product_id)["name"];
            $i->product_detail_name = ProductDetail::find($i->product_detail_id)["name"];
            $i->min_price = ProductDetail::find($i->product_detail_id)['from_price'];
            $i->max_price = ProductDetail::find($i->product_detail_id)['to_price'];
            $min += $i->min_price;
            $max += $i->max_price;
        }

        $b->min_cost = $min;
        $b->max_cost = $max;
        return view('booking_view')->with('b', $b);
    }


    public function approve(Request $request, $id)
    {
        $date = $request->input('date');
        $b = Booking::find($id);
        $b->date = date("Y-m-d H:i", strtotime($date));
        $b->is_approved = 1;
        $b->save();
        return redirect()->route('index');
    }


    private function getLastWeekDate($today){
        $day_of_number = date("w", strtotime($today)) + 1;
        $date = date('Y-m-d', strtotime($today. ' - ' . $day_of_number . 'days'));
        return $date;
    }

    private function getWeek($today){
        $day_of_number = date("w", strtotime($today));
        $start = date('Y-m-d', strtotime($today. ' - ' . $day_of_number . 'days'));

        $jbr = [];
        $wasl = [];
        $burj = [];

        for($i = 0; $i < 7; $i += 1){
             $date = date('Y-m-d', strtotime($start. ' + ' . $i . 'days'));
             $key = $i;

             $jbr[$key] = Booking::whereDate('date', '=', $date)
                                ->where('location', '=', "JBR")->get()->count();

            $wasl[$key] = Booking::whereDate('date', '=', $date)
                                ->where('location', '=', "Al Wasl")->get()->count();

            $burj[$key] = Booking::whereDate('date', '=', $date)
                                ->where('location', '=', "Burj Al Arab")->get()->count();
        }

        return [
            $jbr,
            $wasl,
            $burj
        ];
    }


    private function getYear(){
        $jbr = [];
        $wasl = [];
        $burj = [];

        for($i = 1; $i < 13; $i += 1){
            $key = $i - 1;
            $jbr[$key] = Booking::whereMonth('date', '=', $i)
                                ->whereYear('date', '=', date('Y'))
                                ->where('location', '=', "JBR")->get()->count();

            $wasl[$key] = Booking::whereMonth('date', '=', $i)
                                ->whereYear('date', '=', date('Y'))
                                ->where('location', '=', "Al Wasl")->get()->count();

            $burj[$key] = Booking::whereMonth('date', '=', $i)
                                ->whereYear('date', '=', date('Y'))
                                ->where('location', '=', "Burj Al Arab")->get()->count();
        }

        return [
            $jbr,
            $wasl,
            $burj
        ];
    }

    private function getMonthByNumber($num){
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        return $months[$num - 1];
    }

}
