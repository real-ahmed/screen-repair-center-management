<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExpenseController extends Controller
{
    public function index(Request $request){

        $pageTitle = 'المصروفات العامة';

        $search = $request->input('search');

        $expensesQuery = Expense::query();

        if ($search) {

        }
        $expenses = $expensesQuery->orderBy('id', 'desc')
            ->paginate(getPaginate());

        $receptionists = User::where('role',2)->get();
        return view('expense.index',compact('pageTitle','expenses','receptionists'));

    }


    public function save(Request $request,$id=null){

        $validatedData = $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
            'receptionist_id' => 'nullable|exists:users,id', // Assuming receptionist_id is nullable and exists in receptionists table
        ]);

        if ($id && !auth()->user()->isadmin){
            throw ValidationException::withMessages(['لا يمكنك تعديل البيانات']);


        }

        // Create a new Expense instance
        $expense = $id ? Expense::find($id):new Expense();
        $expense->name = $validatedData['name'];
        $expense->amount = $validatedData['amount'];
        $expense->note = $validatedData['note'];

        // If receptionist_id is provided, set it

        $expense->receptionist_id = isset($validatedData['receptionist_id']) && auth()->user()->isadmin ? $validatedData['receptionist_id'] : auth()->user()->id;


        // Save the expense
        $expense->save();

        // Redirect back with success message or do something else
        return redirect()->back()->with('success', 'تم حفظ المصروفات بنجاح');
    }

    public function delete($id=null){
        $expense =  Expense::findOrFail($id);
        $expense->delete();
        return redirect()->back()->with('success', 'تم حذف المصروفات بنجاح');

    }



}
