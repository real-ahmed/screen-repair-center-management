<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ScreenComponent;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScreenComponentController extends Controller
{
    public function index(Request $request){
        $pageTitle = 'قطع الغيار';
        $search = $request->input('search');
        $components = ScreenComponent::where('name', 'like', '%'.$search.'%')
            ->orWhere('code', 'like', '%'.$search.'%')
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orWhereHas('subcategory', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orWhereHas('brand', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orderBy('id','desc')->paginate(getPaginate());

        $categories = Category::orderBy('id','desc')->paginate(getPaginate());
        $subcategories = Subcategory::orderBy('id','desc')->paginate(getPaginate());
        $brands = Brand::orderBy('id','desc')->paginate(getPaginate());



        return view('component.index',compact('pageTitle','components','categories','subcategories','brands'));
    }


    public function save(Request $request,$id = 0){
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
            'auto_request_quantity' => 'nullable|numeric',
        ]);



        ScreenComponent::updateOrCreate(
            ['id'=>$id],
            [
                'name'=>$request->name,
                'code'=>$request->code,
                'category_id'=>$request->category_id,
                'subcategory_id'=> $request->subcategory_id,
                'auto_request_quantity'=> $request->auto_request_quantity,
                'brand_id'=>$request->brand_id,
                'selling_price'=>$request->selling_price
            ]
        );

        return back()->with('success','تم حفظ البيانات');


    }


    public function delete($id)
    {
        $component = ScreenComponent::findOrFail($id);
        if ($component->unlinked()) {
            $component->delete();
            return back()->with('success', 'تم حذف المنتج');
        }
        throw ValidationException::withMessages(['لا يمكن حذف المنتج الارتباطه بعناصر اخرى']);

    }
}
