<?php

	use App\RecordProduct;
?>
	<div class="row layout-spacing">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="widget widget-card-four">
                <div class="widget-heading">
                    <div class="">
                        <h5 class="">
                            Total Product Sales Report
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($products as $product)
                            @php
                                $qty = RecordProduct::where('product_id',$product->id)->sum('qty');
                                $array[$product->name] = $qty;
                                $per_total = $per_total + $qty;
                            @endphp
                        @endforeach
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
                                    //    $per = round(($value / $per_total) * 100,0);
                                    $per = $per_total;
                                    @endphp
                                    <div class="w-browser-stats">
                                        <div class="progress" data-bs-toggle="tooltip" data-placement="top" data-original-title="{{ $per }}%">
                                            <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {{ $per }}%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>              </div>
                                    </div>
                                    <p class="browser-count" style="float: right;">{{ $value }}L</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
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
