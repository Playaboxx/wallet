<?php

namespace App\Http\Controllers;

use App\Models\Upay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UpayController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'http://localhost:8000/';
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
        //Validate data
        $data = $request->all();
        $validator = Validator::make($data, [
            'merchantId' => 'required',
            'apiKey' => 'required',
            'referenceId' => 'required',
            'itemDesc' => 'required',
            'status' => 'required',
            'statusDesc' => 'required',
            'declineReason' => 'required',
            'transactionId' => 'required',
            'transactionDesc' => 'required',
            'amount' => 'required',
            'transactionAmount' => 'required',
            'currency' => 'required',
            'designatedBank' => 'required',
            'designatedAccountNo' => 'required',
            'designatedAccountName' => 'required',
            'srcAccountNo' => 'required',
            'bankRef' => 'required',
            'requestDate' => 'required',
            'transactionDate' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new UserBank
        Upay::create([
            'merchantId' => $request->merchantId,
            'apiKey' => $request->apiKey,
            'referenceId' => $request->referenceId,
            'itemDesc' => $request->itemDesc,
            'status' => $request->status,
            'statusDesc' => $request->statusDesc,
            'declineReason' => $request->declineReason,
            'transactionId' => $request->transactionId,
            'transactionDesc' => $request->transactionDesc,
            'amount' => $request->amount,
            'transactionAmount' => $request->transactionAmount,
            'currency' => $request->currency,
            'designatedBank' => $request->designatedBank,
            'designatedAccountNo' => $request->designatedAccountNo,
            'designatedAccountName' => $request->designatedAccountName,
            'srcAccountNo' => $request->srcAccountNo,
            'bankRef' => $request->bankRef,
            'requestDate' => $request->requestDate,
            'transactionDate' => $request->transactionDate,
        ]);

        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Payment Success'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Upay  $upay
     * @return \Illuminate\Http\Response
     */
    public function show(Upay $upay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Upay  $upay
     * @return \Illuminate\Http\Response
     */
    public function edit(Upay $upay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Upay  $upay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upay $upay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Upay  $upay
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upay $upay)
    {
        //
    }
}
