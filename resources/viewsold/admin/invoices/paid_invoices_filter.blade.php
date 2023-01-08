                    <?php $sno = 1; ?>
                    @forelse ($records as $sale)
                    	<tr>
                    		<td>{{ $sno++}}</td>	
                    		<td>{{ $sale->invoice_number }}</td>	
                            <td>{{ $sale->user->name }}</td>
                    		<td>{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y H:i') }}</td>
                            <td>{{ $sale->subCompany->company->name }}</td>
                            <td>{{ $sale->subCompany->name }}</td>
                    		<td>{{ $sale->load_number }}</td>
                    		<td>{{ $sale->order_number }}</td>
                            <td>${{ number_format($sale->total_amount,2) }}</td>
                            <td>
                            @if ($sale->email_status == 1)
                                <span class="badge badge-success">Sent</span>
                            @else
                                <span class="badge badge-danger">Not Sent</span>
                            @endif
                            </td>
                            <td>
                            @if ($sale->paid_status == 1)
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-danger">Unpaid</span>
                            @endif
                            </td>
                           <td>
                           <a href="{{ route('invoice.details',$sale->id) }}" class="btn btn-sm btn-primary btn-custom">View</a>
                           </td>
                    	</tr>
					@empty
						<tr>
							<td colspan="10">No record found.</td>
						</tr>
                    @endforelse