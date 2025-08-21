<!-- resources/views/farmers/shopinfo.blade.php -->

<form action="{{ route('farmer.shopinfo.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Shop Info -->
    <h3>Shop Info</h3>

    <div>
        <label for="farmname">Farm Name</label>
        <input type="text" name="farmname" value="{{ old('farmname') }}" required>
    </div>

    <div>
        <label for="pickup_address">Pickup Address</label>
        <input type="text" name="pickup_address" value="{{ old('pickup_address') }}">
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label for="phone">Phone Number</label>
        <input type="text" name="phone" value="{{ old('phone') }}">
    </div>

    <button type="submit">Next</button>
</form>
