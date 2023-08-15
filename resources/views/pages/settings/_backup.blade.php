<div class="col-lg-10 email-content">
    <div class="email-head">
        <div class="email-head-subject">
            <div class="title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a class="active" href="#"><span class="icon"><i data-feather="download-cloud"
                                                                     class="text-primary-muted"></i></span></a>
                    <span>{{ __t('backup') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-sm-12">
            <form action="{{ route('setting.backup') }}" method="post">
                @csrf

                <div class="grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body d-flex flex-column align-items-center">
                            <h1 class="card-description">
                                <i data-feather="download-cloud"></i>
                            </h1>
                            <p class="card-description">{{ __t('please_click_here') }}</p>
                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-cloud-download"></i> {{ __t('download') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
