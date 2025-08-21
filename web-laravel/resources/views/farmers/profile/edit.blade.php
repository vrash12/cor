<!-- resources/views/farmer/profile/edit.blade.php -->

<form action="{{ route('farmer.profile.update') }}" method="POST">
    @csrf
    @method('POST')
    <div>
        <label for="farmname">Farm Name</label>
        <input type="text" name="farmname" value="{{ old('farmname', $farmer->farmname) }}">
    </div>
    
    <div>
        <label for="certification">Certification</label>
        <input type="text" name="certification" value="{{ old('certification', $farmer->certification) }}">
    </div>

    <div>
        <label for="pickup_address">Pickup Address</label>
        <input type="text" name="pickup_address" value="{{ old('pickup_address', $farmer->pickup_address) }}">
    </div>

    <div>
        <label for="business_name">Business Name</label>
        <input type="text" name="business_name" value="{{ old('business_name', $farmer->business_name) }}">
    </div>

    <div>
        <label for="registered_address">Registered Address</label>
        <input type="text" name="registered_address" value="{{ old('registered_address', $farmer->registered_address) }}">
    </div>

    <div>
        <label for="taxpayer_id">Taxpayer ID</label>
        <input type="text" name="taxpayer_id" value="{{ old('taxpayer_id', $farmer->taxpayer_id) }}">
    </div>

    <div>
        <label for="seller_type">Seller Type</label>
        <select name="seller_type">
            <option value="individual" {{ $farmer->seller_type == 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="partnership" {{ $farmer->seller_type == 'partnership' ? 'selected' : '' }}>Partnership</option>
            <option value="corporation" {{ $farmer->seller_type == 'corporation' ? 'selected' : '' }}>Corporation</option>
        </select>
    </div>

    <button type="submit">Update Profile</button>
</form>
