@php
	$quantities = $record->products->sum('qty');
@endphp

<tr id="record_body_{{ $record->id }}" class="sales_records">
  <input type="hidden" class="invoices" id="record_data_{{ $record->id }}" name="record_qty_{{ $record->id }}" value="{{ $quantities }}">
  <input type="hidden" name="sale_qty_{{ $record->id }}" value="{{ $quantities }}">
  <input type="hidden" name="invoices[]" value="{{ $record->id }}" id="invoices_{{$record->id}}">
	<td>{{ $record->invoice_number }}</td>
	<td>{{ $record->subCompany->company->name ?? ''}}</td>
	<td>{{ $record->subCompany->name  ?? ''}}</td>
	<td>{{ $record->supplierCompany->name ?? '' }}</td>
	<td>{{ $record->load_number }}</td>
	<td>{{ $record->order_number }}</td>
	<td id="qty_{{ $record->id }}">{{ $quantities }}</td>
  	<td><a class="btn btn-sm btn-danger" onclick="removeInvoice('{{ $record->id }}')">X</a></td>
</tr>
