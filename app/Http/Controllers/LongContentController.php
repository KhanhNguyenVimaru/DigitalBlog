<?php

namespace App\Http\Controllers;

use App\Models\long_content;
use App\Http\Requests\Storelong_contentRequest;
use App\Http\Requests\Updatelong_contentRequest;
use Illuminate\Http\Request;

class LongContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storelong_contentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(long_content $long_content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(long_content $long_content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatelong_contentRequest $request, long_content $long_content)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(long_content $long_content)
    {
        //
    }
}
