<div class="col-sm-12 col-md-12 col-lg-9">
    <div class="card w-100 h-100 d-inline-block">
        <div class="card-body">
            <h6 class="card-title">
                <span class="icon"><i data-feather="key"></i></span> {{ __t('password_change') }}
            </h6>
            <hr>
            <form class="forms-sample" action="{{ route('change-password') }}" method="post">
                @csrf

                <div class="form-group row">
                    <label for="current_password" class="col-sm-2 col-form-label text-right">{{ __t('current_password') }} :</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="{{ __t('current_password') }}">
                        @error('current_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_password" class="col-sm-2 col-form-label text-right">{{ __t('new_password') }} :</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="{{ __t('new_password') }}">
                        @error('new_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="confirm_password" class="col-sm-2 col-form-label text-right">{{ __t('confirm_password') }} :</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="confirm_password" name="password_confirmation" placeholder="{{ __t('confirm_password') }}">
                        @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="mdi mdi-check"></i> {{ __t('change_password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
