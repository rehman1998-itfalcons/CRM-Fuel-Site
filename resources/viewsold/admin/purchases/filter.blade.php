                    <?php $sno = 1; ?>
					@forelse ($records as $purchase)
                    	<tr>
                    		<td>{{ $sno++ }}</td>	
                    		<td>{{ $purchase->invoice_number }}</td>	
                    		<td>{{ $purchase->datetime }}</td>	
                    		<td>{{ $purchase->category->name }}</td>	
                    		<td>{{ $purchase->fuelCompany->name }}</td>	
                    		<td>{{ $purchase->total_quantity }}</td>	
                    		<td>${{ number_format($purchase->total_amount,2) }}</td>
                          	<td>
                              @if ($purchase->total_amount - $purchase->paid_amount == 0)
                                  <span class="badge badge-success">Paid</span>
                              @else
                                  <span class="badge badge-danger">Unpaid</span>
                              @endif
                          </td>
                    		<td>
                          		<a href="{{ route('purchases.show',$purchase->id) }}" class="badge badge-primary">View</a>
                          		<a href="{{ route('purchases.edit',$purchase->id) }}" class="badge badge-primary">Edit</a>
                          	</td>	
                    	</tr>
					@empty
						<tr><td colspan="8">No purchase record found</td></tr>
                    @endforelse