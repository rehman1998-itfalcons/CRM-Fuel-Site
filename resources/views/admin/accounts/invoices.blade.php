<link href="{{ URL::asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<style>
      	.bootstrap-select.btn-group.show-tick .dropdown-menu a.selected {
          background: #dedede !important;
        }
  		.bootstrap-select.btn-group.show-tick .dropdown-menu a.selected span.dropdown-item-inner span.check-mark {
          margin-top: 0px !important;
        }

</style>
<select class="form-control selectpicker" multiple data-live-search="true" multiple name="invoices[]" required>
  @if($invoices)
  @foreach ($invoices as $invoice)
  	<option id="option_{{$invoice->id }}" data-{{$invoice->id }}-amount="{{ $invoice->total_amount - $invoice->paid_amount }}" value="{{ $invoice->id }}">
    	{{ $invoice->invoice_number }}
  	</option>
  @endforeach
  @endif
</select>
<script src="{{ URL::asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>

	$('select.selectpicker').selectpicker();
	$('select.selectpicker').change(function () {
    	var string = $(this).val();
      	countTotal(string);
    });
  	 $('.check-mark').html('X');
</script>
