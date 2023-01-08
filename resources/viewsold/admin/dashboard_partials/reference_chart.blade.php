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
                        <div class="card-body" id= "sidechart">


                                <div class="mb-4">
                                    <div class="w-browser-details">
                                        <div class="w-browser-info">
                                            <h6 id="h6key">

                                            </h6>
                                        </div>

                                        <div class="w-browser-stats">
                                            <div class="progress" id="progress" data-toggle="tooltip" data-placement="top"
                                                data-original-title=" %">
                                                <div class="progress-bar bg-gradient-primary" id="progress-bar" role="progressbar"
                                                    style="width:  %"
                                                    aria-valuenow="" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <p class="browser-count" id="browse" style="float: right;">L</p>
                                    </div>
                                </div>

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
