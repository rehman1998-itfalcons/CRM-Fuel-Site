<?php
                    $sno = 1;
                    $sales = json_decode($records,true);
                    $count = count($sales);
                    $i = 1;
                    $total_tax = 0;
                    $total_gross = 0;
                    $total_net = 0;
                    $total_delivereis = 0;

                    $whole_tax = 0;
                    $whole_gross = 0;
                    $whole_net = 0;
                    $whole_delivereis = 0;

                    $g_dates = [];
                    $g_gross = [];
                    $g_tax = [];
                    $g_net = [];
                    ?>
                    @foreach ($sales as $key => $sale)
                        <?php
                        $date = \Carbon\Carbon::parse($key)->format('d-m-Y');
                        foreach ($sale as $k => $v) {
                            $total_delivereis++;
                            $total_tax = $total_tax + $v['gst'];
                            $total_gross = $total_gross + $v['total_amount'];
                            $total_net = $total_net + $v['total_without_gst'];
                        }

                        array_push($g_dates,$key);
                        array_push($g_gross,$total_gross);
                        array_push($g_tax,$total_tax);
                        array_push($g_net,$total_net);

                        $whole_tax = $whole_tax + $total_tax;
                        $whole_gross = $whole_gross + $total_gross;
                        $whole_net = $whole_net + $total_net;
                        $whole_delivereis = $whole_delivereis + $total_delivereis;
                        ?>
                        <tr role="row">
                            <td class="sorting">{{ $sno++ }}</td>
                            <td class="sorting">{{ $date }}</td>
                            <td class="sorting">{{ count($sale) }}</td>
                            <td class="sorting">${{ number_format($total_gross,2) }}</td>
                            <td class="sorting">${{ number_format($total_tax,2) }}</td>
                            <td class="sorting">${{ number_format($total_net,2) }}</td>
                            <td class="sorting">
                                <a href="{{ route('income.statistics.details',$date) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                 