@php
	$quantities = $record->products->sum('qty');
@endphp

<tr id="purchase_record_body_{{ $record->id }}">
  <input type="hidden" class="purchases" id="purchase_record_data_{{ $record->id }}" name="purchase_record_qty_{{ $record->id }}" value="{{ $quantities }}">
  <input type="hidden" name="purchase_qty_{{ $record->id }}" value="{{ $quantities }}">
  <input type="hidden" name="purchases[]" value="{{ $record->id }}" id="purchases_{{$record->id}}">
	<td>{{ $record->invoice_number }}</td>
	<td>{{ $company->name }}</td>
	<td id="purchase_qty_{{ $record->id }}">{{ $quantities }}</td>
  	<td><a class="btn btn-sm btn-danger" onclick="purchaseRemoveInvoice('{{ $record->id }}')">X</a></td>
</tr>