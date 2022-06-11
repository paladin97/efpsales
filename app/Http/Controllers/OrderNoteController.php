<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderNote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order_note = new OrderNote();
        $order_note->order_id = $request->order_id;
        $order_note->observation = $request->order_note_text;
        $order_note->created_at = Carbon::now();
        $order_note->updated_at = Carbon::now();
        $order_note->save();

        $order = Order::find($request->order_id);
        $order->order_status_id = $request->order_status;
        $order->updated_at = Carbon::now();
        $order->save();

        return redirect()->route('order.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        $notes = $order->orderNotes;
        $pageTitle = 'Notes';
        $code = $order->order_code;

        //return view('admin.order.modals.order_notes_details', compact('pageTitle', 'notes', 'code'));
        return compact('pageTitle', 'notes', 'code');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $orderNote = OrderNote::findorFail($id);
        $orderNote->observation = $request->order_note_text;
        $orderNote->updated_at = Carbon::now();
        $orderNote->save();

        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        OrderNote::findorFail($id)->delete();

        if ($request->ajax()){
            
            return ['pageTitle'=> 'nota eliminada.'];
        }
        else {

            return  redirect()->route('order.index');
        }
        
    }

    public function find($id)
    {
        return OrderNote::findorFail($id);
    }
}
