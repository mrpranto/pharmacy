<div class="col-lg-9 email-content">
    <div class="email-head">
        <div class="email-head-subject">
            <div class="title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a class="active" href="#"><span class="icon"><i data-feather="bell"
                                                                     class="text-primary-muted"></i></span></a>
                    <span>{{ __t('sounds_and_notification') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-sm-12">
            <form action="{{ route('setting') }}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="notification_sound" class="col-sm-3 col-form-label text-right">{{ __t('notification_sound') }}</label>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[notification][notification_sound]" id="notification_sound"
                                           {{ $setting['notification_sound'] == 'on' ? 'checked' : '' }}
                                           value="on">
                                    On
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[notification][notification_sound]" id="notification_sound"
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
                                           name="setting[notification][notification_show_position]" id="top"
                                           {{ $setting['notification_show_position'] == 'top' ? 'checked' : '' }}
                                           value="top">
                                    Top
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[notification][notification_show_position]" id="topLeft"
                                           {{ $setting['notification_show_position'] == 'topLeft' ? 'checked' : '' }}
                                           value="topLeft">
                                    Top Left
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[notification][notification_show_position]" id="topRight"
                                           {{ $setting['notification_show_position'] == 'topRight' ? 'checked' : '' }}
                                           value="topRight">
                                    Top Right
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[notification][notification_show_position]" id="bottom"
                                           {{ $setting['notification_show_position'] == 'bottom' ? 'checked' : '' }}
                                           value="bottom">
                                    Bottom
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[notification][notification_show_position]" id="bottomLeft"
                                           {{ $setting['notification_show_position'] == 'bottomLeft' ? 'checked' : '' }}
                                           value="bottomLeft">
                                    Bottom Left
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           name="setting[notification][notification_show_position]" id="bottomRight"
                                           {{ $setting['notification_show_position'] == 'bottomRight' ? 'checked' : '' }}
                                           value="bottomRight">
                                    Bottom Right
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
