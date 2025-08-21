<!-- resources/views/farmers/businessinfo.blade.php -->

<form action="{{ route('farmer.businessinfo.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Business Info -->
    <h3>Business Info</h3>

    <div>
        <label for="business_name">Registered Business Name</label>
        <input type="text" name="business_name" value="{{ old('business_name') }}" required>
    </div>

    <div>
        <label for="registered_address">Registered Address</label>
        <input type="text" name="registered_address" value="{{ old('registered_address') }}">
    </div>

    <div>
        <label for="taxpayer_id">Taxpayer ID Number</label>
        <input type="text" name="taxpayer_id" value="{{ old('taxpayer_id') }}">
    </div>

    <div>
        <label for="business_registration_certificate">Certificate of Registration</label>
        <input type="file" name="business_registration_certificate" accept=".pdf,.jpg,.jpeg,.png">
    </div>

    <div>
        <label for="proof_of_identity">Proof of Identity</label>
        <input type="file" name="proof_of_identity" accept=".pdf,.jpg,.jpeg,.png">
    </div>

    <div>
        <label for="seller_type">Seller Type</label>
        <select name="seller_type">
            <option value="individual">Individual</option>
            <option value="partnership">Partnership</option>
            <option value="corporation">Corporation</option>
        </select>
    </div>

    <button type="submit">Submit</button>
</form>
