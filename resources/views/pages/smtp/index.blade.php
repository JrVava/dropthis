<div id="smtp-section" class="mb-5">
    <h4><i class="bi bi-envelope fa-fw text-theme"></i> SMTP</h4>
    <p>View and update your SMTP account information and settings.</p>
    <div class="card">
        <div class="list-group list-group-flush">
            <div class="dropthis-mail-system">
                <form method="post" action="{{ route('smtp-setting-save') }}" autocomplete="off">
                    @csrf
                    <input type="hidden" value="@if (isset($smtp->id)) {{ $smtp->id }} @endif"
                        name="id">
                    @if ($authDetails->user_role != USER_ROLE_ADMIN)
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-1 text-break">
                                <div class="text-inverse text-opacity-70 d-flex align-items-center">Use Dropthis Mailing
                                    System
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="dropthis_mail_or_own_mail"
                                        id="dropthis_mail_or_own_mail"
                                        @if (isset($authDetails->dropthis_mail_or_own_mail) && $authDetails->dropthis_mail_or_own_mail == 1) checked @elseif(old('dropthis_mail_or_own_mail') == 1) checked @endif>
                                    <label class="form-check-label" for="dropthis_mail_or_own_mail">
                                        Use Own Mailing System
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Mailer</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($smtp->mail_mailer)) {{ $smtp->mail_mailer }}@else{{old('mail_mailer') }} @endif"
                                id="mail_mailer" name="mail_mailer" placeholder="Mailer (e.g. smtp)">
                            @if ($errors->has('mail_mailer'))
                                <span class="text-danger">{{ $errors->first('mail_mailer') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Host</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($smtp->mail_host)) {{ $smtp->mail_host }}@else{{ old('mail_host') }} @endif"
                                id="mail_host" name="mail_host" placeholder="Host (e.g. smtp.gmail.com)">
                            @if ($errors->has('mail_host'))
                                <span class="text-danger">{{ $errors->first('mail_host') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Port</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($smtp->mail_port)) {{ $smtp->mail_port }}@else{{ old('mail_port') }} @endif"
                                id="mail_port" name="mail_port" placeholder="Port (e.g. 587)">
                            @if ($errors->has('mail_port'))
                                <span class="text-danger">{{ $errors->first('mail_port') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Username</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($smtp->mail_username)) {{ $smtp->mail_username }}@else{{ old('mail_username') }} @endif"
                                id="mail_username" name="mail_username" placeholder="Username (e.g. John)">
                            @if ($errors->has('mail_username'))
                                <span class="text-danger">{{ $errors->first('mail_username') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Password</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($smtp->mail_password)) {{ $smtp->mail_password }} @endif"
                                id="mail_password" name="mail_password" placeholder="Password (e.g. eabedc54f903ac)">
                            @if ($errors->has('mail_password'))
                                <span class="text-danger">{{ $errors->first('mail_password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Encryption</div>
                        </div>
                        <div class="flex-1">
                            <select class="form-select" name="mail_encryption">
                                <option value="">Select Encryption</option>
                                <option value="tls"
                                    @if (isset($smtp->mail_encryption) && $smtp->mail_encryption == 'tls') selected @elseif(old('mail_encryption') == 'tls') selected @endif>
                                    TLS
                                </option>
                                <option value="ssl"
                                    @if (isset($smtp->mail_encryption) && $smtp->mail_encryption == 'ssl') selected @elseif(old('mail_encryption') == 'ssl') selected @endif>
                                    SSL
                                </option>
                            </select>
                            @if ($errors->has('mail_encryption'))
                                <span class="text-danger">{{ $errors->first('mail_encryption') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Email From</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($smtp->mail_from_address)) {{ $smtp->mail_from_address }}@else{{ old('mail_from_address') }} @endif"
                                id="mail_from_address" name="mail_from_address"
                                placeholder="Address (e.g. test@test.com)">
                            @if ($errors->has('mail_from_address'))
                                <span class="text-danger">{{ $errors->first('mail_from_address') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-1 text-break">
                            <div class="text-inverse text-opacity-70 d-flex align-items-center">Name</div>
                        </div>
                        <div class="flex-1">
                            <input type="text" class="form-control plaintext"
                                value="@if (isset($smtp->mail_from_name)) {{ $smtp->mail_from_name }}@else{{ old('mail_from_name') }} @endif"
                                id="mail_from_name" name="mail_from_name" placeholder="Name (e.g. Dropthis)">
                            @if ($errors->has('mail_from_name'))
                                <span class="text-danger">{{ $errors->first('mail_from_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="d-flex align-items-center mb-md-3 mb-2">
                                <div class="ms-auto">
                                <button type="submit" class="btn btn-outline-theme" id="smtp-submit-btn">Save
                                    Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
