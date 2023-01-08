<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use App\Product;
use Session;
use DB;
class ProductController extends Controller
{
  	public function index()
  	{
     	$products = Product::get();
 		return view('admin.products.index', compact('products'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function store(Request $request)
    { 	
    //   $myob = new MyobCurl;
    //   if (coupon_status() == false){
    //      return redirect('/myob');
    //  }
    //  $token = myobtokens::find(1)->access_token;
    //       $this->validate($request,[
    //           'name' => 'required|unique:products'
    //       ]);
    //       $unique_number = substr(md5($request->name),1,25);
    //       // dd($unique_number);
    //           $post_json_data = [
    //             'Number'=>$unique_number,
    //             'Name'=>$request->name,
    //             'IsActive'=>true,
    //             'Description'=>$request->name
    //           ];
    //           $post_data =     $myob->FileGetContents(
    //                   company_uri().'Inventory/Item',
    //                   'post',
    //                   json_encode($post_json_data)
    //               ,
    //               $token
    //               );
          $unique_number = rand(1,1000);
          
          Product::create([
              	'name' => $request->name,
                'number' => $unique_number,
          ]);
          Session::flash('success','Product created'); 
      	  return redirect()->route('products.index');
    }
    public function syncproduct(){
      // Getl all product list === db_product
      $db_product =Product::get();
      
         if (coupon_status() == false){
          }
          $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;
          
                      $Get_item_uid=0;
                      $item_uid='';
                      $get_data =     $myob->FileGetContents(company_uri().'Inventory/Item', 'get','', $token);
                      $item_list = json_decode($get_data['response'],true);//ture due to array
                      // dd($item_list);
                      foreach($item_list as $items){
                        if (is_array($items) || is_object($items))
                          {
                          foreach($items as $item) { 
                            foreach ($db_product as $product){
                              if ($product->number === $item['Number']){
                                $item_uid = $item['UID'];
                                // Update Query $product->id;
                                DB::table('products')->where('number',$product->number)->update([
                                  'item_uid' => $item_uid
                                  
                        
                                ]);
  
                                
                                
                                
                              }                         
                            }
                          }
                        }
                      }
    }
  	public function edit($id)
    {
      //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
  
        $product = Product::find($request->id);
        $this->validate($request, [
            'name' => 'required|unique:products,name,'.$product->id,
        ]);
          
     	DB::table('products')->where('id',$request->id)->update([
      		'name' => $request->name
      	]);
      	Session::flash('success','Product updated');
    	return redirect()->route('products.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//
    }
  
  	public function changeStatus(Request $request)
    {
          DB::table('products')->where('id',$request->id)->update([
            'status' => $request->status,
          ]);
          
          Session::flash('success','Status changed.');
      
      	return back();
    }
  
}
