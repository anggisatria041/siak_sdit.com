
    <!-- BEGIN: Left Aside -->
    <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
        <i class="la la-close"></i>
    </button>
    <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
        <?php
            $active_menu = in_array($page_name, [
            'V_dashboard',
        ]);
        ?>

        <!-- BEGIN: Aside Menu -->
        <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
            <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                <li class="m-menu__item <?= $active_menu ? 'm-menu__item--active' : '' ?>" aria-haspopup="true">
                    <a href="<?= base_url() . 'C_dashboard' ?>" class="m-menu__link ">
                        <i class="m-menu__link-icon flaticon-line-graph"></i>
                        <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                                <span class="m-menu__link-text">Dashboard</span>
                            </span>
                        </span>
                    </a>
                </li>
                <li class="m-menu__item <?= $page_name == "V_pelanggan" ? "m-menu__item--active" : "" ?>" aria-haspopup="true" m-menu-link-redirect="1">
                    <a href="<?= site_url('dir/C_pelanggan'); ?>" class="m-menu__link ">
                        <span class="m-menu__item-here"></span>
                        <i class="m-menu__link-icon flaticon-list-2"></i>
                        <span class="m-menu__link-text">Fuel Ticket</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- END: Aside Menu -->
    </div>

    <!-- END: Left Aside -->