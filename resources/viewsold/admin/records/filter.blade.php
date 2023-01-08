                    <?php $sno = 1; ?>
                    @forelse ($records as $sale)
                    	<tr>
                    		<td>{{ $sno++}}</td>	
                    		<td>{{ $sale->invoice_number }}</td>	
                    		<td>{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y H:i') }}</td>	
                    		<td>{{ $sale->load_number }}</td>
                    		<td>{{ $sale->order_number }}</td>
                          	<td>{{ $sale->category->name }}</td>
                            <td>{{ $sale->subCompany->name }}</td>
                    		<td>{{ $sale->gst_status }}</td>	
                    		<td>${{ number_format($sale->total_amount,2) }}</td>	
                          <td style="display:inline-flex;">
                          		<a href="{{ route('records.show',$sale->id) }}" class="badge badge-primary" style="margin-right:4px;">View</a>
                          		{{--<a href="{{ route('records.edit',$sale->id) }}" class="badge badge-primary">Edit</a>--}}
                          	</td>
                    	</tr>
					@empty
						<tr>
							<td colspan="10">No record found.</td>
						</tr>
                    @endforelse