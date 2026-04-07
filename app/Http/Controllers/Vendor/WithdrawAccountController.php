<?php

namespace App\Http\Controllers\Vendor;

use App\Models\PartnerWithdrawAccount;
use Illuminate\Http\Request;
use Validator;

class WithdrawAccountController extends VendorBaseController
{
    public function index()
    {
        try {
            $accounts = PartnerWithdrawAccount::where('user_id', $this->user->id)
                ->where('user_type', 'vendor')
                ->orderBy('is_default', 'desc')
                ->get();
        } catch (\Exception $e) {
            $accounts = collect();
        }
        return view('vendor.withdraw_accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('vendor.withdraw_accounts.create');
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'method' => 'required',
                'acc_number' => 'required',
                'acc_name' => 'required',
            ];

            if ($request->method == 'Bank') {
                $rules['bank_name'] = 'required';
                $rules['iban'] = 'required';
            }

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }

            $input = $request->all();
            $input['user_id'] = $this->user->id;
            $input['user_type'] = 'vendor';

            // If this is the first account, make it default
            $count = PartnerWithdrawAccount::where('user_id', $this->user->id)
                ->where('user_type', 'vendor')
                ->count();
            if ($count == 0) {
                $input['is_default'] = 1;
            }

            PartnerWithdrawAccount::create($input);

            return response()->json(__('Withdrawal account added successfully.'));
        } catch (\Exception $e) {
            return response()->json(['errors' => [__('Could not add withdrawal account. Please try again later.')]]);
        }
    }

    public function edit($id)
    {
        try {
            $account = PartnerWithdrawAccount::where('user_id', $this->user->id)
                ->where('user_type', 'vendor')
                ->findOrFail($id);
            return view('vendor.withdraw_accounts.edit', compact('account'));
        } catch (\Exception $e) {
            return back()->with('error', __('Withdrawal account not found.'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $account = PartnerWithdrawAccount::where('user_id', $this->user->id)
                ->where('user_type', 'vendor')
                ->findOrFail($id);

            $rules = [
                'method' => 'required',
                'acc_number' => 'required',
                'acc_name' => 'required',
            ];

            if ($request->method == 'Bank') {
                $rules['bank_name'] = 'required';
                $rules['iban'] = 'required';
            }

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }

            $account->update($request->all());

            return response()->json(__('Withdrawal account updated successfully.'));
        } catch (\Exception $e) {
            return response()->json(['errors' => [__('Could not update withdrawal account. Please try again later.')]]);
        }
    }

    public function destroy($id)
    {
        $account = PartnerWithdrawAccount::where('user_id', $this->user->id)
            ->where('user_type', 'vendor')
            ->findOrFail($id);
        
        $wasDefault = $account->is_default;
        $account->delete();

        if ($wasDefault) {
            $next = PartnerWithdrawAccount::where('user_id', $this->user->id)
                ->where('user_type', 'vendor')
                ->first();
            if ($next) {
                $next->update(['is_default' => 1]);
            }
        }

        return response()->json(__('Withdrawal account deleted successfully.'));
    }

    public function setDefault($id)
    {
        PartnerWithdrawAccount::where('user_id', $this->user->id)
            ->where('user_type', 'vendor')
            ->update(['is_default' => 0]);

        $account = PartnerWithdrawAccount::where('user_id', $this->user->id)
            ->where('user_type', 'vendor')
            ->findOrFail($id);
        
        $account->update(['is_default' => 1]);

        return redirect()->back()->with('success', __('Default account set successfully.'));
    }
}
