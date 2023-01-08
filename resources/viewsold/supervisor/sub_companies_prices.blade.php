                                    <table class="table table-bordered mb-4">
                                        <thead>
                                           <tr>
                                             @php
                              	 $total = 0;
                                 $category = $record->category;
                              	@endphp
                                                <th>Products</th>
                                                <th>Quantity</th>
                                              @if($category->rate_whole_sale != '')
                                              	<th>Whole Sale</th>
                                              @endif
                                             @if($category->rate_discount != '')
                                              	<th>Discount</th>
                                              @endif
                                              @if($category->rate_delivery_rate != '')
                                              	<th>Delivery Rate</th>
                                              @endif
                                               @if($category->rate_brand_charges != '')
                                              	<th>Brand Charges</th>
                                              @endif
                                              @if($category->rate_cost_of_credit != '')
                                              	<th>COC/limit</th>
                                              @endif
                                            </tr>
                                        </thead>
                                      	<tbody>
                              	@foreach ($record->products as $product)
                                          @php
                                          $rate = \App\SubCompanyRateArea::where('sub_company_id',$subcompany_id)->where('product_id',$product->product_id)->first();
                                          @endphp
                                          @if($product->qty != 0)
                                              <tr>
                                                <td>{{ $product->product->name }}</td>
                                                <td>
                                                  {{ $product->qty }}
                                                </td>
                                                @if($category->rate_whole_sale != '')
                                                <td>
                                                  <input type="text" name="whole_sale_price_{{$product->id}}" value="{{($rate->whole_sale ? $rate->whole_sale: '')}}" class="form-control" @if($category->whole_sale_display == 2) disabled @endif>
                                                </td>
                                                @endif
                                                @if($category->rate_discount != '')
                                                <td>
                                                  <input type="text" @if($rate) value="{{ $rate->discount }}" @else value="" @endif class="form-control" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_delivery_rate != '')
                                                <td>
                                                  <input type="text"  @if($rate)  value="{{ $rate->delivery_rate }}" @else value="" @endif class="form-control" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_brand_charges != '')
                                                <td>
                                                  <input type="text"  @if($rate)  value="{{ $rate->brand_charges }}" @else value="" @endif class="form-control" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_cost_of_credit != '')
                                                <td>
                                                  <input type="text"  @if($rate)  value="{{ $rate->cost_of_credit }}" @else value="" @endif class="form-control" disabled>
                                                </td>
                                                @endif
                                              </tr>
                                          @endif
                                           @php
                                          $total = $total + $product->qty;
                                        @endphp
                              	@endforeach
                                     </tbody>
                         </table>
