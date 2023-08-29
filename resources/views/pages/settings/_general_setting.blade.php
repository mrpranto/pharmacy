<div class="col-lg-9 email-content">
    <div class="email-head">
        <div class="email-head-subject">
            <div class="title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a class="active" href="#"><span class="icon"><i data-feather="globe"
                                                                     class="text-primary-muted"></i></span></a>
                    <span>{{ __t('general_setting') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-sm-12">
            <form action="{{ route('setting') }}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="date_format" class="col-sm-3 col-form-label text-right">{{ __t('date_format') }}</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="setting[general][date_format]">
                            <option {{ $setting['date_format'] == 'd-m-Y' ? 'selected' : '' }} value="d-m-Y">DD-MM-YYYY</option>
                            <option  {{ $setting['date_format'] == 'Y-m-d' ? 'selected' : '' }} value="Y-m-d">YYYY-MM-DD</option>
                            <option  {{ $setting['date_format'] == 'm-d-Y' ? 'selected' : '' }} value="m-d-Y">MM-DD-YYYY</option>
                            <option  {{ $setting['date_format'] == 'd/m/Y' ? 'selected' : '' }} value="d/m/Y">DD/MM/YYYY</option>
                            <option  {{ $setting['date_format'] == 'Y/m/d' ? 'selected' : '' }} value="Y/m/d">YYYY/MM/DD</option>
                            <option  {{ $setting['date_format'] == 'm/d/Y' ? 'selected' : '' }} value="m/d/Y">MM/DD/YYYY</option>
                            <option  {{ $setting['date_format'] == 'd.m.Y' ? 'selected' : '' }} value="d.m.Y">DD.MM.YYYY</option>
                            <option  {{ $setting['date_format'] == 'Y.m.d' ? 'selected' : '' }} value="Y.m.d">YYYY.MM.DD</option>
                            <option  {{ $setting['date_format'] == 'm.d.Y' ? 'selected' : '' }} value="m.d.Y">MM.DD.YYYY</option>
                            <option  {{ $setting['date_format'] == 'F d, Y' ? 'selected' : '' }} value="F d, Y">MM DD, YYYY</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_format" class="col-sm-3 col-form-label text-right">{{ __t('time_format') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][time_format]" id="24_hours" {{ $setting['time_format'] == 'H:i:s A' ? 'checked' : '' }} value="H:i:s A">
                                    {{ __t('24_hours') }}
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][time_format]" id="12_hours" {{ $setting['time_format'] == 'h:i:s A' ? 'checked' : '' }} value="h:i:s A">
                                    {{ __t('12_hours') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_format" class="col-sm-3 col-form-label text-right">{{ __t('currency_symbol') }}</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="setting[general][currency_symbol]">
                            <option {{ $setting['currency_symbol'] == 'BDT' ? 'selected' : '' }} value="BDT">BDT</option>
                            <option {{ $setting['currency_symbol'] == '৳' ? 'selected' : '' }} value="৳">৳</option>
                            <option {{ $setting['currency_symbol'] == '$' ? 'selected' : '' }} value="$">$</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_format" class="col-sm-3 col-form-label text-right">{{ __t('currency_symbol_position') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][currency_symbol_position]" id="before_amount"
                                           {{ $setting['currency_symbol_position'] == 'before_amount' ? 'checked' : '' }}
                                           value="before_amount">
                                    ৳11,0000
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][currency_symbol_position]"
                                           id="before_with_space_amount"
                                           {{ $setting['currency_symbol_position'] == 'before_with_space_amount' ? 'checked' : '' }}
                                           value="before_with_space_amount">
                                    ৳ 11,000
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][currency_symbol_position]" id="after_amount"
                                           {{ $setting['currency_symbol_position'] == 'after_amount' ? 'checked' : '' }}
                                           value="after_amount">
                                    11,0000৳
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][currency_symbol_position]"
                                           id="after_with_space_amount"
                                           {{ $setting['currency_symbol_position'] == 'after_with_space_amount' ? 'checked' : '' }}
                                           value="after_with_space_amount">
                                    11,000 ৳
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_format" class="col-sm-3 col-form-label text-right">{{ __t('pagination') }}</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="setting[general][pagination]">
                            <option {{ $setting['pagination'] == '5' ? 'selected' : '' }} value="5">5</option>
                            <option {{ $setting['pagination'] == '10' ? 'selected' : '' }} value="10">10</option>
                            <option {{ $setting['pagination'] == '20' ? 'selected' : '' }} value="20">20</option>
                            <option {{ $setting['pagination'] == '30' ? 'selected' : '' }} value="30">30</option>
                            <option {{ $setting['pagination'] == '50' ? 'selected' : '' }} value="50">50</option>
                            <option {{ $setting['pagination'] == '100' ? 'selected' : '' }} value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="notification_sound" class="col-sm-3 col-form-label text-right">{{ __t('notification_sound') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][notification_sound]" id="notification_sound"
                                           {{ $setting['notification_sound'] == 'on' ? 'checked' : '' }}
                                           value="on">
                                    On
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][notification_sound]" id="notification_sound"
                                           {{ $setting['notification_sound'] == 'off' ? 'checked' : '' }}
                                           value="off">
                                    Off
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="notification_show_position" class="col-sm-3 col-form-label text-right">{{ __t('notification_show_position') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][notification_show_position]" id="top"
                                           {{ $setting['notification_show_position'] == 'top' ? 'checked' : '' }}
                                           value="top">
                                    Top
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][notification_show_position]" id="topLeft"
                                           {{ $setting['notification_show_position'] == 'topLeft' ? 'checked' : '' }}
                                           value="topLeft">
                                    Top Left
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][notification_show_position]" id="topRight"
                                           {{ $setting['notification_show_position'] == 'topRight' ? 'checked' : '' }}
                                           value="topRight">
                                    Top Right
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
