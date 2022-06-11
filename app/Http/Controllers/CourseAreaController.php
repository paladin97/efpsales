<?php

namespace App\Http\Controllers;

use App\Models\CourseArea;
use Illuminate\Http\Request;

class CourseAreaController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseArea  $courseArea
     * @return \Illuminate\Http\Response
     */
    public function show(CourseArea $courseArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseArea  $courseArea
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
       $courseArea = CourseArea::find($id);

       return response()->json($courseArea);
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseArea  $courseArea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseArea $courseArea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseArea  $courseArea
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseArea $courseArea)
    {
        //
    }
}
