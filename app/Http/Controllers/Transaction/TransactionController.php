<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $transactions = Transaction::all();

        return $this->showAll($transactions);
    }

    /**
     * Display the specified resource.
     *
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }
}
