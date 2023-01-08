<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use App\Product;
use Session;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        return view('admin.products.index', compact('products'));
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:products'
        ]);
        $myob = new MyobCurl;
        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $DisplayID  = "9-" . rand(1234, 9999);

        Product::create([
            'name' => $request->name,
            'number' => $DisplayID,
        ]);
        $myob1 = DB::table('myob')->first();
        if ($myob1->status == 1) {
            $token = myobtokens::find(1)->access_token;
            // dump($token);


            //dd($unique_number);

            $post_json_data = [

                'Number' => $DisplayID,
                'Name' => $request->name,
                'IsActive' => true,
                'Description' => $request->name
            ];
            // dump('after array');
            $post_data = $myob->FileGetContents(company_uri() . 'Inventory/Item', 'post', json_encode($post_json_data), $token);

            // dump($post_data);

            //after creted product now synced code in the same method
            $db_product = Product::get();
            $Get_item_uid = 0;
            $item_uid = '';
            $get_data =     $myob->FileGetContents(company_uri() . 'Inventory/Item', 'get', '', $token);
            $item_list = json_decode($get_data['response'], true); //ture due to array
            // dd($item_list);
            foreach ($item_list as $items) {
                if (is_array($items) || is_object($items)) {
                    foreach ($items as $item) {
                        foreach ($db_product as $product) {
                            if ($product->number === $item['Number']) {
                                $item_uid = $item['UID'];
                                // Update Query $product->id;
                                DB::table('products')->where('number', $product->number)->update([
                                    'item_uid' => $item_uid,
                                    'status' => 1


                                ]);
                            }
                        }
                    }
                }
            }
            // dd('synced data');
            \Session::flash('success', 'Product created');
            return redirect()->route('products.index');
        } else {
            \Session::flash('error', 'Myob Disabled Data only stored into Database ');
            return redirect()->route('products.index');
        }
    }
    public function syncproduct()
    {
        // dd('here');

        // \Session::flash('success', 'Product Syncing started...');
        // return redirect()->back();



        $db_product = Product::get();

        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $myob1 = DB::table('myob')->first();
        // dump($myob1->status);
        if ($myob1->status == 1) {
            // dump('im in');
            $myob = new MyobCurl;
            $token = myobtokens::find(1)->access_token;
            $get_data =     $myob->FileGetContents(company_uri() . 'Inventory/Item', 'get', '', $token);
            $item_list = json_decode($get_data['response'], true);
              //dump($item_list);
            $not_sync = [];
            $sync = [];
           // dump($db_product);
            foreach ($db_product as $product_local) {
                $not_sync[$product_local->id] = $product_local;
                foreach ($item_list['Items'] as $item) {
                    if (isset($item['Number']) && isset($product_local->number) && $product_local->number == $item['Number']) {
                        $sync[$product_local->id] = $product_local;
                    }
                }
            }
          //  dump($sync);
            foreach ($sync as $key => $all_key) {
                unset($not_sync[$key]);
            }
             //dump($not_sync);
            foreach ($not_sync as $product_notsync) {
                $post_json_data = [

                    'Number' => $product_notsync->number,
                    'Name' => $product_notsync->name,
                    'IsActive' => true,
                    'Description' => $product_notsync->name
                ];
                $post_data = $myob->FileGetContents(company_uri() . 'Inventory/Item', 'post', json_encode($post_json_data), $token);
                 dump($post_data);
            }
            //dd('out');
            $get_data =     $myob->FileGetContents(company_uri() . 'Inventory/Item', 'get', '', $token);
            $item_list = json_decode($get_data['response'], true);
            foreach ($db_product as $product_local) {
                foreach ($item_list['Items'] as $item) {
                    if (isset($item['Number']) && isset($product_local->number) && $product_local->number == $item['Number']) {
                        DB::table('products')->where('id', $product_local->id)->update([
                            'item_uid' => $item['UID'],
                            'number' => $item['Number'],
                            'status' => 1
                        ]);
                        // dump('updated');
                    }
                }
            }

            // \Session::flash('success', 'Product Synced');
            // return redirect()->back();
        }
       // dd('IM NOT IN IF CONDITION');
        // dd('not ento myob status');
    }
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {

        $product = Product::find($request->id);
        $this->validate($request, [
            'name' => 'required|unique:products,name,' . $product->id,
        ]);

        DB::table('products')->where('id', $request->id)->update([
            'name' => $request->name
        ]);
        \Session::flash('success', 'Product updated');
        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        DB::table('products')->where('id', $request->id)->update([
            'status' => $request->status,
        ]);

        Session::flash('success', 'Status changed.');

        return back();
    }
}
