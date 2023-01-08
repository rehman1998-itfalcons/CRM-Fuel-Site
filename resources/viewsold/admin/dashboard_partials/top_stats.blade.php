<div class="col-lg-12">
    <div class="single_element">
        <div class="quick_activity">
            <div class="row">
                <div class="col-12">
                    <div class="quick_activity_wrap">
                        <div class="single_quick_activity d-flex">
                            <div class="icon">
                                <img src="{{URL::asset('img/icon/truck.png')}}" alte="">
                            </div>
                            <div class="count_content">
                                <h3><span
                                        class="counter">{{ $sales->where('deleted_status', 0)->where('status', 1)->count() }}</span>
                                </h3>
                                <p>TOTAL DELIVERIES</p>
                            </div>
                        </div>
                        <div class="single_quick_activity d-flex">
                            <div class="icon">
                                <img src="{{URL::asset('img/icon/data.png')}}" alte="">
                            </div>
                            <div class="count_content">
                                <h3><span class="counter">
                                        {{ $sales->where('status', 0)->where('deleted_status', 0)->count() }}</span>
                                </h3>
                                <p>PENDING RECORDS</p>
                            </div>
                        </div>
                        <div class="single_quick_activity d-flex">
                            <div class="icon">
                                <img src="{{URL::asset('img/icon/inv.png')}}" alte="">
                            </div>
                            <div class="count_content">
                                <h3><span
                                        class="counter">{{ $sales->where('status', 1)->where('paid_status', 1)->where('deleted_status', 0)->count() }}</span>
                                </h3>
                                <p>PAID INVOICES</p>
                            </div>
                        </div>
                        <div class="single_quick_activity d-flex">
                            <div class="icon">
                                <img src="{{URL::asset('img/icon/dollar.png')}}" alte="">
                            </div>
                            <div class="count_content">
                                <h3><span class="counter">{{ number_format($gross, 2) }}</span> </h3>
                                <p>BALANCE AMOUNT</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
