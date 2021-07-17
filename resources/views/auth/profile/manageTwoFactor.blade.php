@extends('auth.profile.main')

@section('cardBody')

<h5>Two Factor Authentication:</h5>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

<form action="" method="POST">
@csrf
    <div class="form-group">
      <label for="two_factor_auth">Type</label>
      <select class="form-control" name="two_factor_auth" id="two_factor_auth">
        <option value="off" <?php if (Auth::user()->two_factor_auth == "off") echo "selected"?>>OFF</option>
        <option value="sms" <?php if (Auth::user()->two_factor_auth == "sms") echo "selected"?>>SMS</option>
      </select>
    </div>

    <div class="form-group">
      <label for="phone"></label>
      <input type="text"
        class="form-control" name="phone" id="phone" aria-describedby="helpId"
        value="{{ old('phone') ?? Auth::user()->phone }}"
        placeholder="Your Phone Number">
      <small id="helpId" class="form-text text-muted">Like 09*********</small>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>

</form>


@endsection
