<?php

namespace App\Http\Controllers;

use App\Models\SaleMaster;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\SaleDetail;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class SaleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)

    {


        // if ($request->ajax()) {
        //     $data = SaleDetail::latest()->get();
        //     return Datatables::of($data)
        //         ->addIndexColumn()
        //         ->addColumn('action', function ($data) {

        //             //   $btn =  '<a href="/products/' . $data->id . '/edit" class="btn btn-primary"><i class="bi bi-pencil"></i></a>';
        //             $btn = ' <a href="/saledetails/' . $data->id . '/edit"  class="btn btn-primary btn-md "><i class="fas fa-pen text-white"></i></a>';
        //             $btn = $btn . ' <a href="/saledetails/" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-md deleteDetails"><i class="far fa-trash-alt text-white" data-feather="delete"></i></a>';

        //             return $btn;
        //         })
        //         // ->editColumn('sale_master_id', function ($row) {
        //         //     return $row->customer()->first()->name;
        //         // })
        //         ->rawColumns(['action'])->make(true);
        // }


        $customers = Customer::with('sale_details')->get();

        $products = Product::with('sale_details')->get();

        return view('saledetails.index', compact('customers', 'products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('saledetails.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->get('id') != '') {
            //perform Edit
            $id = $request->get('id');
            $saleDetail = SaleDetail::find($id);



            $saleDetail->quantity = $request->quantity;

            $saleDetail->price = $request->price;

            $saleDetail->sale_master_id = $request->sale_master_id;

            $saleDetail->product_id = $request->product_id;

            $saleDetail->save();

            // $product->categories()->attach($request->category);


            return redirect()->route('saledetails.index')
                ->with('success', 'SaleDetails updated successfully.');
        } else {


            //Perform Create
            $request->validate([

                'quantity' => 'required',
                'price' => 'required',
                'sale_master_id' => 'required',
                'product_id' => 'required',
                // 'customer_id' => 'required',




            ]);
            SaleDetail::create($request->all());
        }

        return redirect()->route('saledetails.index')
            ->with('success', 'Sale Details has been Added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleMaster  $saleMaster
     * @return \Illuminate\Http\Response
     */
    public function show(SaleDetail $saleDetail)
    {
        //
        return view('saleDetails.show', compact('saleMaster'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleMaster  $saleMaster
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return view('products.index', compact('product'));

        $saleDetail = SaleDetail::find($id);
        $saleDetails = SaleDetail::latest()->paginate(5);
        $salemasters = SaleMaster::with('sale_details')->get();

        // $categories = Category::latest()->paginate(5);

        return view('saledetails.index', compact('saleDetail', 'saleDetails', 'salemasters'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleMaster  $saleMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleDetail $saleDetail)
    {
        $request->validate([
            'date' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'sale_master_id' => 'required',
            'product_id' => 'required',

        ]);

        $saleDetail->update($request->all());

        return redirect()->route('saledetails.index')
            ->with('success', 'SaleMaster updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleMaster  $saleMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleDetail $saleDetail)
    {
        $saleDetail->delete();

        return redirect()->route('saledetails.index')
            ->with('success', 'sale Details deleted successfully');
    }
}
