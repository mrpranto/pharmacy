<div class="col-lg-9 email-content">
    <div class="email-head">
        <div class="email-head-subject">
            <div class="title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a class="active" href="#"><span class="icon"><i data-feather="inbox"
                                                                     class="text-primary-muted"></i></span></a>
                    <span>General Setting</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-sm-12">
            <form action="{{ route('setting') }}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="date_format" class="col-sm-2 col-form-label text-right">Date
                        format</label>
                    <div class="col-sm-10">
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
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_format" class="col-sm-2 col-form-label text-right">Time format</label>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][time_format]" id="24_hours" {{ $setting['time_format'] == 'H:i:s A' ? 'checked' : '' }} value="H:i:s A">
                                    24 Hours
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[general][time_format]" id="12_hours" {{ $setting['time_format'] == 'h:i:s A' ? 'checked' : '' }} value="h:i:s A">
                                    12 Hours
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_format" class="col-sm-2 col-form-label text-right">Currency
                        Symbol</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="setting[general][currency_symbol]">
                            <option {{ $setting['currency_symbol'] == 'BDT' ? 'selected' : '' }} value="BDT">BDT</option>
                            <option {{ $setting['currency_symbol'] == '৳' ? 'selected' : '' }} value="৳">৳</option>
                            <option {{ $setting['currency_symbol'] == '$' ? 'selected' : '' }} value="$">$</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_format" class="col-sm-2 col-form-label text-right">Currency
                        Symbol Position</label>
                    <div class="col-sm-10">
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
                    <label for="date_format" class="col-sm-2 col-form-label text-right">Pagination</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="setting[general][pagination]">
                            <option {{ $setting['pagination'] == '10' ? 'selected' : '' }} value="10">10</option>
                            <option {{ $setting['pagination'] == '15' ? 'selected' : '' }} value="15">15</option>
                            <option {{ $setting['pagination'] == '30' ? 'selected' : '' }} value="30">30</option>
                            <option {{ $setting['pagination'] == '50' ? 'selected' : '' }} value="50">50</option>
                            <option {{ $setting['pagination'] == '100' ? 'selected' : '' }} value="100">100</option>
                        </select>
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
