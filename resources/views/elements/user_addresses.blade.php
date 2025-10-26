@if(!$userAddresses->isEmpty())
	@foreach($userAddresses as $key=> $address)
		<div class='col-md-4 col-sm-6'>
			<div class="radio styled-selector equal-height">
				<input id="{{ $address->id }}" value="{{ $address->id }}" type="radio" name="deliver_address" @if($key==0) checked @endif>
				<label for="{{ $address->id }}">
					{{ $address->full_name }}
					<br>
					{{ $address->address }}
					<br>
					{{ $address->pin_code}}
					<br>
					{{ $address->phone_number}}
				</label>
			</div>
		</div>
		
	@endforeach
@endif
<div class="clearfix"></div>
<div class="form-seprator" id="sep"> <img src="{{ WEBSITE_IMG_URL }}form-sep.png" alt="form-seprator"> </div>
