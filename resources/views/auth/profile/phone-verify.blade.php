@extends('/layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Verify You Phone</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('profile.two.factor.phone') }}" method="POST">
                        @csrf
                        <div class="form-group">
                          <label for="token">Your Token</label>
                          <input type="text"
                            class="form-control @error('token') is-invalid @enderror" name="token" id="token"

                            aria-describedby="helpId" placeholder="Code ...">
                            @error('token')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          <small id="helpId" class="form-text text-muted">Enter The Code That We Sent To Your Phone</small>
                        </div>
                        <button type="submit" class="btn btn-success">Verify</button>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection


