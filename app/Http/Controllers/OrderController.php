<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Carbon\Carbon;
use Facade\FlareClient\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::from('orders as or')
            ->leftJoin('order_statuses as os', 'or.order_status_id', '=', 'os.id')
            ->leftJoin('providers as printing_provider', 'or.printing_provider_id', '=', 'printing_provider.id')
            ->leftJoin('providers as shipping_provider', 'or.shipping_agency_provider_id', '=', 'shipping_provider.id')
            ->leftJoin('contracts as con', 'or.id', '=', 'con.order_id')
            ->select(
                'or.id',
                'or.order_code as code',
                'or.shipping_agency_provider_id as shipping_agency',
                'or.printing_provider_id as printing_provider',
                'or.order',
                'os.id as status_id',
                'os.status',
                'os.color',
                'printing_provider.name as printname',
                'printing_provider.phone as printphone',
                'printing_provider.address as printaddress',
                'printing_provider.email as printemail',
                'shipping_provider.phone as shipphone',
                'shipping_provider.address as shipaddress',
                'shipping_provider.email as shipemail',
                'shipping_provider.name as shipname',
                'con.enrollment_number'
            )
            ->orderBy('or.created_at', 'desc')
            ->get();


        $pageTitle = 'Editorial';


        return view('admin.order.index', compact('pageTitle', 'orders'));
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
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {

        /* $order = new Order();
        $order->order = $request->order;
        $order->order_status_id = 1;
        $order->created_at = Carbon::now();
        $order->updated_at = Carbon::now();
        $order->save();
       
       return back()->with('info','Orden realizada'); */ }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, $id)
    {

        $order = Order::findorFail($id);
        $order->order_code = $request->order_code;
        $order->shipping_agency_provider_id = $request->shipping_list;
        $order->printing_provider_id = $request->printing_list;
        $order->order = $request->order_text;
        $order->order_status_id = $request->order_status;
        $order->updated_at = Carbon::now();
        $order->save();

        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::findorFail($id)->delete();

        return  redirect()->route('order.index');
    }

    public function find($id)
    {
        return Order::findorFail($id);
    }

    public function notes($id)
    {
        $order = Order::find($id);
        $notes = $order->orderNotes;
        $pageTitle = 'Notes';
        $code = $order->order_code;

        //return view('admin.order.modals.order_notes_details', compact('pageTitle', 'notes', 'code'));
        return compact('pageTitle', 'notes', 'code');
    }
}
