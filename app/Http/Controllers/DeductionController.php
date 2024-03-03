<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeductionController extends Controller
{
    public function index(Request $request)
    {

        $pageTitle = "سجل الخصم";
        $search = $request->input('search');
        $deductionQuery = Deduction::query();

        if ($search) {
            $deductionQuery->where(function ($query) use ($search) {
                $query->whereHas('employee', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');

            });
        }
        if (!auth()->user()->isreceptionist) {
            $deductionQuery->where('employee_id', auth()->user()->id);
        }
        $deductions = $deductionQuery->orderBy('id', 'desc')->paginate(getPaginate());
        return view('deduction.index', compact('pageTitle', 'deductions'));
    }

    public function save(Request $request, $id = null)
    {
        $this->validateBonus($request, $id);

        if ($id) {
            $this->validateBonusDeletion($id);
        }

        Deduction::updateOrCreate(
            ['id' => $id],
            [
                'employee_id' => $request->employee_id,
                'amount' => $request->amount
            ]
        );

        return back()->with('success', 'تم حفظ الخصم');
    }

    private function validateBonus(Request $request, $id)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:0',
            'employee_id' => 'exists:users,id',
        ]);
    }

    private function validateBonusDeletion($id)
    {
        $deduction = Deduction::findOrFail($id);

        if (!$deduction->unlinked()) {
            throw ValidationException::withMessages(['لا يمكن تعديل الخصم بعد القبض']);
        }
    }


    public function delete($id)
    {
        $deduction = Deduction::findOrFail($id);
        if ($deduction->unlinked()) {
            $deduction->delete();
            return back()->with('success', 'تم حذف الخصم');
        }
        throw ValidationException::withMessages(['لا يمكن حذف الخصم بعد القبض']);
    }


}
