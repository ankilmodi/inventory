<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use Session;


class ItemContorller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Item::create([
            "name"=>$request->name,
            "quantity"=>$request->quantity,
            "manufacture_date"=>$request->manufactureDate,
        ]);
        Session::flash('message', 'Item is stored successfully!');
        return redirect('/all-items');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item=Item::find($id);
        return view('item.edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $item = Item::find($request->id);
        $item->name=$request->name;
        $item->quantity=$request->quantity;
        $item->manufacture_date=$request->manufactureDate;
        $item->save();
        Session::flash('message', 'Item is updated successfully!');
        return redirect('/all-items');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item =Item::find($id);
        $item->delete();

        Session::flash('message', 'Item is deleted successfully'); 
        return redirect('/all-items');

    }

    public function getItemData(Request $request)
    {
       
     $columns = array( 
                            0 =>'id', 
                            1 =>'name',
                            2=> 'quantity',
                            3=> 'manufacture_date',
                            4=> 'created_at',
                        );
  
        $totalData = Item::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        { 
            $posts = Item::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $search = $request->input('search.value');
            $posts =  Item::where('name','LIKE',"%{$search}%")
                            ->orWhere('quantity', 'LIKE',"%{$search}%")
                            ->orWhere('manufacture_date', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();


            $totalFiltered = Item::where('name','LIKE',"%{$search}%")
                            ->orWhere('quantity', 'LIKE',"%{$search}%")
                            ->orWhere('manufacture_date', 'LIKE',"%{$search}%")
                             ->count();
        }
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['quantity'] = $post->quantity;
                $nestedData['manufacture_date'] = $post->manufacture_date;
                $nestedData['created_at'] =$post->created_at->format('Y-m-d'); 
                // $nestedData['image'] = "<img src=".$post->image.">";
                // $nestedData['action']="<a>Delete</a>";
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
        

   }
}
