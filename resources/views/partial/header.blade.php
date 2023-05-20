<!-- BEGIN #header -->
<div id="header" class="app-header">
    <!-- BEGIN desktop-toggler -->
    @if (isset(auth::user()->id))
        <div class="desktop-toggler">
            <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed"
                data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>

        <!-- BEGIN desktop-toggler -->

        <!-- BEGIN mobile-toggler -->
        <div class="mobile-toggler">
            <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled"
                data-toggle-target=".app">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
    @endif
    <!-- END mobile-toggler -->

    <!-- BEGIN brand -->
    <div class="brand">
        <a href="{{ route('home') }}" class="brand-logo">
            @if (!empty(auth::user()->id) || empty($campaigns->userDetails->logo))
                <span class="brand-img">
                    <span class="brand-img-text text-theme">D</span>
                </span>
                <span class="brand-text">DROP THIS
                @else
                    <img src="{{ getFileFromStorage($userPath . $campaigns->user_id . '/' . $campaigns->userDetails->logo) }}"
                        alt="" height="50">
            @endif
        </a>
    </div>
    <!-- END brand -->
    @if (isset(auth::user()->id))
        <!-- BEGIN menu -->
        <div class="menu">
            <div class="menu-item dropdown">
                <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"
                    class="menu-link">
                    <div class="menu-icon"><i class="bi bi-search nav-icon"></i></div>
                </a>
            </div>
            <div class="menu-item dropdown dropdown-mobile-full">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                    <div class="menu-icon"><i class="bi bi-grid-3x3-gap nav-icon"></i></div>
                </a>
                <div class="dropdown-menu fade dropdown-menu-end w-300px text-center p-0 mt-1">
                    <div class="row row-grid gx-0">
                        <div class="col-4">
                            <a href="{{ route('home') }}" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div class="position-relative">
                                    <i class="bi bi-cpu position-absolute text-theme top-0 mt-n2 me-n2 fs-6px d-block text-center w-100"></i>
                                    <i class="bi bi-cpu h2 opacity-5 d-block my-1"></i>
                                </div>
                                <div class="fw-500 fs-10px text-inverse">DASHBAORD</div>
                            </a>
                        </div>
                        
                            <div class="col-4">
                                <a href="{{ route('general-settings') }}"
                                    class="dropdown-item text-decoration-none p-3 bg-none">
                                    <div><i class="bi bi-gear h2 opacity-5 d-block my-1"></i></div>
                                    <div class="fw-500 fs-10px text-inverse">SETTINGS</div>
                                </a>
                            </div>
                        <div class="col-4">
                            {{-- <a href="#" class="dropdown-item text-decoration-none p-3 bg-none">
                            <div><i class="bi bi-collection-play h2 opacity-5 d-block my-1"></i></div>
                            <div class="fw-500 fs-10px text-inverse">WIDGETS</div>
                        </a> --}}
                            <div class="dropdown-item text-decoration-none p-3 bg-none">
                                {{-- <div class="form-check form-switch form-swith-theme-block pt-2 my-1 ps-0 d-flex align-items-center  flex-column">
                                    <input type="checkbox" class="form-check-input ms-0" id="customSwitch1">
                                </div>
                                <label class="form-check-label fw-500 fs-10px text-inverse" for="customSwitch1">DARK</label> --}}

                                <div class="toggle-radio">
                                    <div class="onoffswitch mt-1">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox"
                                            id="myonoffswitch" checked onClick="changeThemeMode()">
                                        <label class="onoffswitch-label" for="myonoffswitch">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="fw-500 fs-10px text-inverse  mt-1" id="mode-text">DARK MODE</div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-grid gx-0">
                        <div class="col-4">
                            <a href="{{ route('links') }}" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div><i class="bi bi-link h2 opacity-5 d-block my-1"></i></div>
                                <div class="fw-500 fs-10px text-inverse">LINKS</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('campaigns') }}" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div><i class="fa fa-bullhorn h2 opacity-5 d-block my-1"></i></div>
                                <div class="fw-500 fs-10px text-inverse">CAMPAIGNS</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('releases') }}" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div class="position-relative">
                                    {{-- <i class="bi bi-circle-fill position-absolute text-theme top-0 mt-n2 me-n2 fs-6px d-block text-center w-100"></i> --}}
                                    <i class="bi bi-rocket h2 opacity-5 d-block my-1"></i>
                                </div>
                                <div class="fw-500 fs-10px text-inverse">RELEASES</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-item dropdown dropdown-mobile-full">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                    <div class="menu-icon"><i class="bi bi-bell nav-icon"></i></div>
                    <div class="menu-badge bg-theme"></div>
                </a>
                <div class="dropdown-menu dropdown-menu-end mt-1 w-300px fs-11px pt-1">
                    <h6 class="dropdown-header fs-10px mb-1">NOTIFICATIONS</h6>
                    <div class="dropdown-divider mt-1"></div>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px">
                            <i class="bi bi-bag text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-inverse">NEW ORDER RECEIVED ($1,299)</div>
                            <div class="small">JUST NOW</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-person-circle text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-inverse">3 NEW ACCOUNT CREATED</div>
                            <div class="small">2 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-gear text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-inverse">SETUP COMPLETED</div>
                            <div class="small">3 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-grid text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-inverse">WIDGET INSTALLATION DONE</div>
                            <div class="small">5 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-credit-card text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-inverse">PAYMENT METHOD ENABLED</div>
                            <div class="small">10 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <hr class="bg-white-transparent-5 mb-0 mt-2" />
                    <div class="py-10px mb-n2 text-center">
                        <a href="#" class="text-decoration-none fw-bold">SEE ALL</a>
                    </div>
                </div>
            </div>
            <div class="menu-item dropdown dropdown-mobile-full">
                @auth
                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                        <div class="menu-img online">
                            <div
                                class="d-flex align-items-center justify-content-center w-100 h-100 bg-white bg-opacity-25 text-inverse text-opacity-50 rounded-circle overflow-hidden">
                                @if (!empty(auth::user()->id) && auth::user()->logo)
                                    <img src="{{ getFileFromStorage(auth::user()::$userProfilePath . auth::user()->id . '/' . auth::user()->logo) }}"
                                        alt="" width="100" height="100">
                                @else
                                    <i class="bi bi-person-fill fs-32px mb-n3"></i>
                                @endif
                            </div>
                        </div>
                        <div class="menu-text d-sm-block d-none">{{ auth()->user()->name }}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-lg-3 fs-11px mt-1">
                        {{-- <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">PROFILE <i
                                class="bi bi-person-circle ms-auto text-theme fs-16px my-n1"></i></a> --}}
                        {{-- <a class="dropdown-item d-flex align-items-center" href="#">INBOX <i class="bi bi-envelope ms-auto text-theme fs-16px my-n1"></i></a>
                    <a class="dropdown-item d-flex align-items-center" href="#">CALENDAR <i class="bi bi-calendar ms-auto text-theme fs-16px my-n1"></i></a> --}}
                        {{-- @if (auth()->user()->user_role == USER_ROLE_ADMIN) --}}
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('general-settings') }}">
                                SETTINGS <i class="bi bi-gear ms-auto text-theme fs-16px my-n1"></i>
                            </a>
                        {{-- @endif --}}
                        @if (auth()->user()->user_role == USER_ROLE_USER)
                            <a class="dropdown-item d-flex align-items-center" href="#">Credit <label
                                    class="ms-auto text-theme">{{ auth()->user()->credits }}</label>
                                {{-- <i class="bi bi-gear ms-auto text-theme fs-16px my-n1"></i> --}}
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LOGOUT <i
                                class="bi bi-toggle-off ms-auto text-theme fs-16px my-n1"></i></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                @endauth
            </div>
        </div>
        <!-- END menu -->
        <!-- BEGIN menu-search -->
        <form class="menu-search" method="POST" name="header_search_form">
            <div class="menu-search-container">
                <div class="menu-search-icon"><i class="bi bi-search"></i></div>
                <div class="menu-search-input">
                    <input type="text" class="form-control form-control-lg" placeholder="Search menu..." />
                </div>
                <div class="menu-search-icon">
                    <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i
                            class="bi bi-x-lg"></i></a>
                </div>
            </div>
        </form>
        <!-- END menu-search -->
    @endif
</div>
@push('js')
    <script src="/assets/plugins/spectrum-colorpicker2/dist/spectrum.min.js"></script>
    <script>
        $(document).ready(function() {
            let getMode = Cookies.get(app.themePanel.themeMode.cookieName);
            if(getMode === undefined){
                Cookies.set(app.themePanel.themeMode.cookieName, 'dark');
                document.querySelector('html').setAttribute(app.themePanel.themeMode.attr, 'dark');
            }
            if (getMode == 'light') {
                $("#myonoffswitch").attr('checked', false);
                $('#mode-text').text('LIGHT MODE');
            } else if (getMode == "dark") {
                $('#mode-text').text('DARK MODE');
                $("#myonoffswitch").attr('checked', true);
            }
        });

        function changeThemeMode() {
            let getMode = Cookies.get(app.themePanel.themeMode.cookieName);
            if (getMode == "dark") {
                Cookies.set(app.themePanel.themeMode.cookieName, 'light');
                document.querySelector('html').setAttribute(app.themePanel.themeMode.attr, 'light');
                $('#dark-mode').removeClass('active');
                $('#light-mode').addClass('active');
                $('#mode-text').text('LIGHT MODE');
            } else if (getMode == "light") {
                Cookies.set(app.themePanel.themeMode.cookieName, 'dark');
                document.querySelector('html').setAttribute(app.themePanel.themeMode.attr, 'dark');
                $('#dark-mode').addClass('active');
                $('#light-mode').removeClass('active');
                $('#mode-text').text('DARK MODE');
            }
            handleCssVariable();
            document.dispatchEvent(new CustomEvent('theme-reload'));
        }
    </script>
@endpush
<!-- END #header -->
