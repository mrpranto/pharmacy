<div class="col-sm-12 col-md-12 col-lg-9">
    <div class="card w-100 h-100 d-inline-block">
        <div class="card-body">
            <h6 class="card-title">
                <span class="icon"><i data-feather="user"></i></span> {{ __t('personal_info') }}
            </h6>
            <hr>
            <form class="forms-sample" action="{{ route('profile') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label text-right">{{ __t('name') }} :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required id="name"
                               name="name"
                               value="{{ auth()->user()->name }}"
                               placeholder="{{ __t('name') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label text-right">{{ __t('email') }}
                        :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required id="email"
                               name="email"
                               value="{{ auth()->user()->email }}"
                               placeholder="{{ __t('email') }}">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="phone_number"
                           class="col-sm-2 col-form-label text-right">{{ __t('phone_number') }} :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required readonly id="phone_number"
                               name="phone_number"
                               value="{{ auth()->user()->phone_number }}"
                               placeholder="{{ __t('phone_number') }}">
                        @error('phone_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-sm-2 col-form-label text-right">{{ __t('gender') }}
                        :</label>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" required name="gender" id="male"
                                           {{ auth()->user()->gender === 'male' ? 'checked' : '' }}
                                           value="male">
                                    {{ __t('male') }}
                                    <i class="input-frame"></i></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" required name="gender" id="female"
                                           {{ auth()->user()->gender === 'female' ? 'checked' : '' }}
                                           value="female">
                                    {{ __t('female') }}
                                    <i class="input-frame"></i></label>
                            </div>
                            @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-sm-2 col-form-label text-right">{{ __t('address') }}
                        :</label>
                    <div class="col-sm-10">
                                    <textarea class="form-control" id="address"
                                              name="address"
                                              placeholder="{{ __t('address') }}">{{ auth()->user()->address }}</textarea>
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="phone_number"
                           class="col-sm-2 col-form-label text-right">{{ __t('profile_picture') }} :</label>
                    <div class="col-sm-10">
                        <input type="file" name="profile_picture" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled=""
                                   placeholder="{{ __t('upload') }}">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">
                                    <i class="mdi mdi-upload"></i> {{ __t('upload') }}
                                </button>
                            </span>
                        </div>
                        @error('profile_picture')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="offset-sm-2 col-sm-10">
                        <img
                            src="{{ optional(auth()->user()->profilePicture)->full_url ?? asset('/images/avatar.png') }}"
                            id="imgPreview"
                            class="img-thumbnail"
                            style="width: 200px; height: 200px">
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="mdi mdi-content-save"></i> {{ __t('save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
