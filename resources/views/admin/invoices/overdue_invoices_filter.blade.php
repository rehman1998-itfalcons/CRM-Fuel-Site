                    <?php $sno = 1; ?>
                    @forelse ($records as $sale)
 					@php
                 	$date = \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y');
          			$days = $sale->subCompany->inv_due_days;   
                 	if($days > 0)
                    {
                      $date = \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y');
                      $due = date('d-m-Y', strtotime($date. ' + '.$days.' days'));
                    }
                     elseif($days < 0){

                      $timestamp = strtotime($date);
                      $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
                      $positive_value =  abs($days); 
                      $original_date = $positive_value+$daysRemaining;

                      $date = substr($date,0,10);
                      $due = date('d-m-Y', strtotime($date. ' + '.$original_date.' days'));                                       
                    }
                    $remaining_date = strtotime($due) - strtotime(date('d-m-Y'));
                    if ($remaining_date >= 0)
                 		continue;
                     @endphp
                  <tr class="yellow-tooltip" @if($sale->follows_note) data-bs-toggle="tooltip" data-placement="top" data-original-title="{{ $sale->follows_note }}"  @endif>
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