<?php

use App\RecordProduct;
?>
<div class="row layout-spacing">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="white_box mb_20">
                    <div class="widget widget-card-four">
                    <div class="widget-heading">
                        <div class="">
                            <h5 class="">
                                Total Product Sales Report
                            </h5>
                        </div>
                        <div class="card-body" id="sidechart">
                            {{-- @foreach ($products as $product) --}}
                            @php
                                // $qty = RecordProduct::where('product_id',$product->id)->sum('qty');
                                $product_record = \DB::table('record_products')
                                    ->select(\DB::raw('sum(qty) as qty, name'))
                                    ->join('products', 'record_products.product_id', '=', 'products.id')
                                    ->groupBy('product_id')
                                    ->get();
                                $per_total = 0;
                                foreach ($product_record as $record_data) {
                                    $array[$record_data->name] = $record_data->qty;
                                    $per_total += $record_data->qty;
                                }

                            @endphp
                            @php
                                arsort($array);
                                $height = count($array) * 48.87;
                            @endphp
                            @foreach ($array as $key => $value)
                                <div class="mb-4">
                                    <div class="w-browser-details">
                                        <div class="w-browser-info">
                                            <h6>
                                                {{ $key }}
                                            </h6>
                                        </div>
                                        @php
                                            $per = round(($value / $per_total) * 100, 0);
                                        @endphp
                                        <div class="w-browser-stats">
                                            <div class="progress" data-toggle="tooltip" data-placement="top"
                                                data-original-title="{{ $per }}%">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                    style="width: {{ $per }}%"
                                                    aria-valuenow="{{ $per }}" aria-valuemin="0"
                                                    aria-valuemax="100">{{ $per }}</div>
                                            </div>
                                        </div>
                                        <p class="browser-count" id="bro" style="float: right;">{{ $value }}L</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 ">
                <div class="white_box mb_20">
                <div class="widget widget-card-four">
                    <div class="widget-heading">
                        <div class="">
                            <h5 class="">
                                Supplier Companies
                            </h5>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div id="s_companies" class="" style="min-height: 370px;"></div>
                    </div>
                </div>
            </div>
            </div>


    </div>
</div>
