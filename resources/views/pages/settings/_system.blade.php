<div class="col-lg-9 email-content">
    <div class="email-head">
        <div class="email-head-subject">
            <div class="title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a class="active" href="#"><span class="icon"><i data-feather="airplay"
                                                                     class="text-primary-muted"></i></span></a>
                    <span>{{ __t('system') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-sm-12">
            <form action="{{ route('setting') }}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="notification_show_position" class="col-sm-3 col-form-label text-right">{{ __t('pos_design') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[system][pos_design]" id="cart_design"
                                           {{ $setting['pos_design'] == 'cart_design' ? 'checked' : '' }}
                                           value="cart_design">
                                    Cart Design
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[system][pos_design]" id="list_design"
                                           {{ $setting['pos_design'] == 'list_design' ? 'checked' : '' }}
                                           value="list_design">
                                    List Design
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="notification_show_position" class="col-sm-3 col-form-label text-right">{{ __t('variant') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[system][variant]" id="variant_yes"
                                           {{ $setting['variant'] == 'yes' ? 'checked' : '' }}
                                           value="yes">
                                    Yes
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[system][variant]" id="variant_no"
                                           {{ $setting['variant'] == 'no' ? 'checked' : '' }}
                                           value="no">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="notification_show_position" class="col-sm-3 col-form-label text-right">{{ __t('opening_stock') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[system][opening_stock]" id="opening_stock_yes"
                                           {{ $setting['opening_stock'] == 'yes' ? 'checked' : '' }}
                                           value="yes">
                                    Yes
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[system][opening_stock]" id="opening_stock_no"
                                           {{ $setting['opening_stock'] == 'no' ? 'checked' : '' }}
                                           value="no">
                                    No
                                </label>
                            </div>
                        </div>
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
