<?php
                    $sno = 1;
?>
@foreach ($records as $key => $purchase)

                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>{{ $purchase->invoice_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->datetime)->format('d-m-Y H:i') }}</td>
                            <td>{{ $purchase->category->name }}</td>
                            <td>{{ $purchase->fuelCompany->name }}</td>
                            <td>{{ $purchase->total_quantity }}</td>
                            <td>${{ number_format($purchase->total_amount,2) }}</td>
                            <td style="white-space: nowrap !important;">
                                 <a href="{{ route('purchases.show',$purchase->id) }}" class="btn btn-sm btn-primary btn-custom">View</a>
                            </td>
                        </tr>
                        @endforeach
