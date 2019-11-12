<?php

namespace App\Http\Controllers\Api\Savings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Savings\StoreSavingsRequest;
use App\Repository\SavingsRepository;
use App\Repository\SavingsExpenseRepository;
use App\Repository\SavingsAllocationRepository;
use App\Repository\FamilyRepository;

class SavingsController extends Controller
{
    /**
     * create instance
     */
    public function __construct(SavingsRepository $savings,
                                SavingsAllocationRepository $savingsAllocation,
                                SavingsExpenseRepository $savingExpense)
    {
        $this->middleware('auth:api');
        $this->savings = $savings;
        $this->savingsAllocations = $savingsAllocation;
        $this->savingsExpenses = $savingsExpenses;
    }

    public function addSavings(StoreSavingsRequest $request, FamilyRepository $families)
    {

        $user = $request->user();
        $family = $families->findFamily($user);

        // if user has already a code defined return a status of 302
        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        $data = [
            'author_id' => $user->id,
            'savings_type' => $request->savingsType,
            'principal_amount' => $request->principalAmount,
            'remarks' => $request->remarks,
            'family_code' => $family->code,
        ];

        $saving = $this->savings->store($data);

        return response()
            ->json([
                'message' => trans('api.success_add_savings'),
            ], 201);
    }

    public function addAllocation (Request $request)
    {
        $data = [
            'savings_id' => $request->savingsId,
            'vision_id' => $request->visionId,
            'amount' => $request->allocationAmount
        ];

        $savingsAllocation = $this->savingsAllocations->store($data);

        return response()
            ->json([
                'message' => trans('api.success_add_savings'),
            ], 201);
    }

    public function addExpenses (Request $request)
    {
        $data = [
            'savings_id' => $request->savingsId,
            'expense_title' => $request->expenseTitle,
            'amount' => $request->expenseAmount
        ];

        $savingsExpense = $this->savingsExpenses->store($data);

        return response()
            ->json([
                'message' => trans('api.success_add_savings'),
            ], 201);
    }

    protected function checkSavings ($principalAmount, $expenseAmount = 0, $savingsAmount = 0)
    {
        //todo
    }
}