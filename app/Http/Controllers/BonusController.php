<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BonusController extends Controller
{
    public function index(Request $request)
    {

        $pageTitle = "سجل البونص";
        $search = $request->input('search');
        $bonusQuery = Bonus::query();

        if ($search) {
            $bonusQuery->where(function ($query) use ($search) {
                $query->whereHas('employee', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');

            });
        }
        if (!auth()->user()->isreceptionist) {
            $bonusQuery->where('employee_id', auth()->user()->id);
        }
        $bonuses = $bonusQuery->orderBy('id', 'desc')->paginate(getPaginate());
        return view('bonus.index', compact('pageTitle', 'bonuses'));
    }

    public function save(Request $request, $id = null)
    {
        $this->validateBonus($request, $id);

        if ($id) {
            $this->validateBonusDeletion($id);
        }

        Bonus::updateOrCreate(
            ['id' => $id],
            [
                'employee_id' => $request->employee_id,
                'amount' => $request->amount
            ]
        );

        return back()->with('success', 'تم حفظ البونص');
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
        $bonus = Bonus::findOrFail($id);

        if (!$bonus->unlinked()) {
            throw ValidationException::withMessages(['لا يمكن تعديل البونص بعد القبض']);
        }
    }



    public function delete($id)
    {
        $bonus = Bonus::findOrFail($id);
        if ($bonus->unlinked()) {
            $bonus->delete();
                isset($bonus->screenBonus) ?? $bonus->screenBonus->delete();
            return back()->with('success', 'تم حذف البونص');
        }
        throw ValidationException::withMessages(['لا يمكن حذف البونص بعد القبض']);
    }
}
