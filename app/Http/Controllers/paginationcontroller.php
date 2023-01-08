public function purchasepagination()
    {

        $purchase_record = PurchaseRecord::get();

        if (coupon_status() == false) {
        }
        $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;


        $record_uid = '';
        $get_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item', 'get', '', $token);
        $purchase_record_list = json_decode($get_data['response'], true);

        foreach ($purchase_record_list as $pr_list) {
            if (is_array($pr_list) || is_object($pr_list)) {
                foreach ($pr_list as $list) {
                    foreach ($purchase_record as $prl) {
                        if ($prl->invoice_number === $list['Number']) {
                            $record_uid = $list['UID'];

                            DB::table('purchase_records')->where('invoice_number', $prl->invoice_number)->update([
                                'invoice_uid' => $record_uid


                            ]);

                            Session::flash('success', 'Purchase record added successfully.');
                            return redirect('/purchases');
                        }
                    }
                }
            }
        }
    }
