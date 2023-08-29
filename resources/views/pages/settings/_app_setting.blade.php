<div class="col-lg-9 email-content">
    <div class="email-head">
        <div class="email-head-subject">
            <div class="title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a class="active" href="#"><span class="icon"><i data-feather="grid"
                                                                     class="text-primary-muted"></i></span></a>
                    <span>{{ __t('app_setting') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-sm-12">
            <form action="{{ route('setting') }}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="app_name" class="col-sm-2 col-form-label text-right">{{ __t('app_name') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="setting[app_setting][name]" value="{{ $setting['name'] }}" placeholder="{{ __t('app_name') }}" id="app_name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label text-right">{{ __t('email') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="setting[app_setting][email]" value="{{ $setting['email'] }}" placeholder="{{ __t('email') }}" id="email">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile" class="col-sm-2 col-form-label text-right">{{ __t('mobile') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="setting[app_setting][mobile]" value="{{ $setting['mobile'] }}" placeholder="{{ __t('mobile') }}" id="mobile">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-sm-2 col-form-label text-right">{{ __t('address') }}</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="setting[app_setting][address]" placeholder="{{ __t('address') }}" id="address">{{ $setting['address'] }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <button class="btn btn-sm btn-primary float-right">
                            <i data-feather="check"></i> Save
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
